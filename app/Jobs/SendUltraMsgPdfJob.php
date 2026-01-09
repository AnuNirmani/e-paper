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

    public $timeout = 120;
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
        $documentBodyToSend = null;

        // Handle Watermarking if requested
        if ($this->watermarkText && $this->outputDir) {
            try {
                // Ensure output directory exists (redundant check but safe)
                if (!file_exists($this->outputDir)) {
                    mkdir($this->outputDir, 0777, true);
                }

                $personalizedFilename = "{$this->customerId}_" . time();
                
                // Add watermark
                $newPath = $watermarkService->addWatermark(
                    $this->originalFilePath,
                    $this->watermarkText,
                    $this->outputDir,
                    $personalizedFilename
                );

                if ($newPath && file_exists($newPath)) {
                    // Convert personalized file to base64
                    $pData = file_get_contents($newPath);
                    $documentBodyToSend = "data:application/pdf;base64," . base64_encode($pData);
                    
                    // Clean up temporary watermarked file
                    @unlink($newPath);
                }
            } catch (\Exception $e) {
                Log::error("Watermarking failed for customer {$this->customerId} in Job: " . $e->getMessage());
                // Fallback to sending original file will happen below if $documentBodyToSend is null
            }
        }

        // Fallback: Send Original File if Watermarking wasn't needed or failed
        if (!$documentBodyToSend) {
            if (file_exists($this->originalFilePath)) {
                $data = file_get_contents($this->originalFilePath);
                $documentBodyToSend = "data:application/pdf;base64," . base64_encode($data);
            } else {
                Log::error("Original file not found for customer {$this->customerId}: {$this->originalFilePath}");
                return; // Cannot send
            }
        }

        // Send via UltraMsg
        $ultraMsgService->sendDocument(
            $this->whatsappNumber,
            $documentBodyToSend,
            $this->filename,
            $this->caption
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
