<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Services\UltraMsgService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NotifySubscriptionEnding3Days extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:notify-3days';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp notifications to customers whose subscription ends in 3 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDate = now()->addDays(3)->format('Y-m-d');
        
        $customers = Customer::whereDate('ending_date', $targetDate)
            ->where('status', 1) // Only active customers
            ->whereNotNull('whatsapp_number')
            ->get();

        if ($customers->isEmpty()) {
            $this->info('No customers with subscriptions ending in 3 days.');
            Log::info('Subscription notification (3 days): No customers found.');
            return Command::SUCCESS;
        }

        $ultraMsgService = new UltraMsgService();
        $successCount = 0;
        $failCount = 0;

        $this->info("Found {$customers->count()} customers with subscriptions ending in 3 days.");

        foreach ($customers as $customer) {
            $customerName = trim($customer->first_name . ' ' . $customer->last_name);
            $endingDate = $customer->ending_date->format('d/m/Y');

            $message = "Hello {$customerName},\n\n";
            $message .= "🔔 URGENT REMINDER: Your subscription will expire in 3 days!\n\n";
            $message .= "📅 Ending Date: {$endingDate}\n";
            $message .= "⏰ Days Remaining: 3 days\n\n";
            $message .= "Please renew your subscription immediately to continue enjoying our services without interruption.\n\n";
            $message .= "Thank you for being with us!";

            try {
                $result = $ultraMsgService->sendMessage($customer->whatsapp_number, $message);
                
                if ($result) {
                    $successCount++;
                    $this->info("✓ Sent to: {$customerName} ({$customer->whatsapp_number})");
                    Log::info("Subscription notification (3 days) sent to customer ID: {$customer->id}");
                } else {
                    $failCount++;
                    $this->error("✗ Failed to send to: {$customerName}");
                    Log::error("Failed to send subscription notification (3 days) to customer ID: {$customer->id}");
                }
            } catch (\Exception $e) {
                $failCount++;
                $this->error("✗ Error sending to {$customerName}: " . $e->getMessage());
                Log::error("Exception sending subscription notification (3 days) to customer ID: {$customer->id}", [
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

        Log::info("Subscription notification (3 days) completed", [
            'total' => $customers->count(),
            'success' => $successCount,
            'failed' => $failCount
        ]);

        return Command::SUCCESS;
    }
}
