<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Services\UltraMsgService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NotifySubscriptionEnding7Days extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:notify-7days';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp notifications to customers whose subscription ends in 7 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDate = now()->addDays(7)->format('Y-m-d');
        
        $customers = Customer::whereDate('ending_date', $targetDate)
            ->where('status', 1) // Only active customers
            ->whereNotNull('whatsapp_number')
            ->get();

        if ($customers->isEmpty()) {
            $this->info('No customers with subscriptions ending in 7 days.');
            Log::info('Subscription notification (7 days): No customers found.');
            return Command::SUCCESS;
        }

        $ultraMsgService = new UltraMsgService();
        $successCount = 0;
        $failCount = 0;

        $this->info("Found {$customers->count()} customers with subscriptions ending in 7 days.");

        foreach ($customers as $customer) {
            $customerName = trim($customer->first_name . ' ' . $customer->last_name);
            $endingDate = $customer->ending_date->format('d/m/Y');

            $message = "Hello {$customerName},\n\n";
            $message .= "⚠️ REMINDER: Your subscription will expire in 7 days!\n\n";
            $message .= "📅 Ending Date: {$endingDate}\n";
            $message .= "⏰ Days Remaining: 7 days\n\n";
            $message .= "Please renew your subscription soon to avoid service interruption.\n\n";
            $message .= "Thank you for being with us!";

            try {
                $result = $ultraMsgService->sendMessage($customer->whatsapp_number, $message);
                
                if ($result) {
                    $successCount++;
                    $this->info("✓ Sent to: {$customerName} ({$customer->whatsapp_number})");
                    Log::info("Subscription notification (7 days) sent to customer ID: {$customer->id}");
                } else {
                    $failCount++;
                    $this->error("✗ Failed to send to: {$customerName}");
                    Log::error("Failed to send subscription notification (7 days) to customer ID: {$customer->id}");
                }
            } catch (\Exception $e) {
                $failCount++;
                $this->error("✗ Error sending to {$customerName}: " . $e->getMessage());
                Log::error("Exception sending subscription notification (7 days) to customer ID: {$customer->id}", [
                    'error' => $e->getMessage()
                ]);
            }

            // Small delay to avoid rate limiting
            usleep(500000); // 0.5 second delay
        }

        $this->info("\n--- Summary ---");
        $this->info("Total: {$customers->count()}");
        $this->info("Success: {$successCount}");
        $this->info("Failed: {$failCount}");

        Log::info("Subscription notification (7 days) completed", [
            'total' => $customers->count(),
            'success' => $successCount,
            'failed' => $failCount
        ]);

        return Command::SUCCESS;
    }
}
