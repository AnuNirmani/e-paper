<?php

namespace App\Services;

use Ilovepdf\Ilovepdf;
use Ilovepdf\WatermarkTask;
use Illuminate\Support\Facades\Log;

class WatermarkService
{
    protected $ilovepdf;

    public function __construct()
    {
        $publicKey = env('ILOVEPDF_PROJECT_KEY_PUBLIC');
        $secretKey = env('ILOVEPDF_SECRET_KEY');

        if ($publicKey && $secretKey) {
            $this->ilovepdf = new Ilovepdf($publicKey, $secretKey);
        }
    }

    /**
     * Add watermark to a PDF file.
     *
     * @param string $inputPath Absolute path to the input PDF
     * @param string $watermarkText Text to watermark
     * @param string $outputDir Directory to save the output file
     * @return string|null Path to the watermarked file, or null on failure
     */
    public function addWatermark($inputPath, $watermarkText, $outputDir, $outputFilename = null)
    {
        if (!$this->ilovepdf) {
            Log::error('WatermarkService: iLovePDF keys not configured.');
            return null;
        }

        try {
            // Create a new task
            $task = $this->ilovepdf->newTask('watermark');

            // Add the file
            $fileId = $task->addFile($inputPath);

            // Process with watermark settings
            $task->setText($watermarkText);
            $task->setMode('text');
            $task->setVerticalPosition('middle');
            $task->setHorizontalPosition('center');
            $task->setRotation(0);
            $task->setTransparency(30); // 0-100
            $task->setFontFamily('Arial');
            $task->setFontSize(40);
            $task->setLayer('above');
            
            if ($outputFilename) {
                $task->setOutputFilename($outputFilename);
            }
            
            $task->execute();

            // Download the result
            $task->download($outputDir);
            
            // Return the actual file path saved by the SDK
            return $outputDir . '/' . $task->outputFileName; 

        } catch (\Exception $e) {
            Log::error('WatermarkService Error: ' . $e->getMessage());
            return null;
        }
    }
}
