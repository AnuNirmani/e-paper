<?php

namespace App\Jobs;

use App\Services\UltraMsgService;
use App\Services\WatermarkService;
use App\Models\Copy;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendUltraMsgPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;
    public $tries = 3;

    protected $customerId;
    protected $whatsappNumber;
    protected $originalFilePath; // Path to the original global PDF
    protected $filename; // Filename to show in WhatsApp
    protected $caption;
    protected $publicationId;
    protected $watermarkText;
    protected $outputDir;

    public function __construct($customerId, $whatsappNumber, $originalFilePath, $filename, $caption, $publicationId, $watermarkText = null, $outputDir = null)
    {
        $this->customerId = $customerId;
        $this->whatsappNumber = $whatsappNumber;
        $this->originalFilePath = $originalFilePath;
        $this->filename = $filename;
        $this->caption = $caption;
        $this->publicationId = $publicationId;
        $this->watermarkText = $watermarkText;
        $this->outputDir = $outputDir;
    }

    public function handle(UltraMsgService $ultraMsgService, WatermarkService $watermarkService)
    {
        // Verify customer's subscription is still active before sending
        $customer = \App\Models\Customer::find($this->customerId);
        
        if (!$customer) {
            Log::error("SendUltraMsgPdfJob: Customer {$this->customerId} not found");
            return;
        }

        if (!$customer->isSubscriptionActive()) {
            Log::warning("SendUltraMsgPdfJob: Customer {$this->customerId} subscription has expired on {$customer->ending_date}. Skipping send.");
            return;
        }

        $fileToSend = null;

        Log::info("SendUltraMsgPdfJob: Starting job for customer {$this->customerId}", [
            'watermark_text' => $this->watermarkText,
            'output_dir' => $this->outputDir,
            'original_file' => $this->originalFilePath
        ]);

        if ($this->watermarkText && $this->outputDir) {
            Log::info("SendUltraMsgPdfJob: Attempting to add watermark for customer {$this->customerId}");
            try {
                if (!file_exists($this->outputDir)) {
                    mkdir($this->outputDir, 0777, true);
                }

                $personalizedFilename = "{$this->customerId}_" . time();

                $newPath = $watermarkService->addWatermark(
                    $this->originalFilePath,
                    $this->watermarkText,
                    $this->outputDir,
                    $personalizedFilename
                );

                if ($newPath && file_exists($newPath)) {
                    Log::info("SendUltraMsgPdfJob: Watermark added successfully for customer {$this->customerId}", [
                        'watermarked_file' => $newPath,
                        'file_size' => filesize($newPath)
                    ]);
                    $fileToSend = $newPath;
                } else {
                    Log::warning("SendUltraMsgPdfJob: Watermarked file not created for customer {$this->customerId}", [
                        'returned_path' => $newPath
                    ]);
                }
            } catch (\Exception $e) {
                Log::error("Watermarking failed for customer {$this->customerId} in Job: " . $e->getMessage());
            }
        }

        if (!$fileToSend) {
            if (file_exists($this->originalFilePath)) {
                $fileToSend = $this->originalFilePath;
            } else {
                Log::error("Original file not found for customer {$this->customerId}: {$this->originalFilePath}");
                return;
            }
        }

        // UltraMsg base64 field limit is ~10 MB of chars (~6.9 MB raw). Use public URL for larger files.
        $fileSize = filesize($fileToSend);
        if (($fileSize * 1.37) > 9_500_000) {
            $publicBase = rtrim(str_replace('\\', '/', public_path()), '/');
            $filePath   = str_replace('\\', '/', $fileToSend);
            $relative   = ltrim(str_replace($publicBase, '', $filePath), '/');
            $documentBodyToSend = rtrim(config('app.url'), '/') . '/' . $relative;
            Log::info("SendUltraMsgPdfJob: Sending large file ({$fileSize} bytes) by URL", ['url' => $documentBodyToSend]);
        } else {
            $data = file_get_contents($fileToSend);
            $documentBodyToSend = "data:application/pdf;base64," . base64_encode($data);
        }

        // Send via UltraMsg
        $ultraMsgService->sendDocument(
            $this->whatsappNumber,
            $documentBodyToSend,
            $this->filename,
            $this->caption,
            $customer->country  // Pass customer's country code for phone formatting
        );

        // Record Copy
        Copy::createCopy([
            'customer_id'    => $this->customerId,
            'publication_id' => $this->publicationId,
            'message'        => $this->caption
        ]);

        // 🔒 Anti-ban safety
        sleep(3);
    }
}
