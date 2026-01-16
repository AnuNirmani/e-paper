<?php

namespace App\Services;

use Ilovepdf\Ilovepdf;
use Ilovepdf\WatermarkTask;
use Illuminate\Support\Facades\Log;
use App\Models\WatermarkSetting;

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
     * Get current watermark settings from database
     */
    public function getSettings()
    {
        $setting = WatermarkSetting::first();
        
        if (!$setting) {
            // Create default settings if none exist
            $setting = WatermarkSetting::create([
                'vertical_position'   => 'middle',
                'horizontal_position' => 'center',
                'rotation'            => 0,
                'transparency'        => 30,
                'font_family'         => 'Arial',
                'font_size'           => 40,
                'layer'               => 'above'
            ]);
        }

        return $setting->toArray();
    }

    /**
     * Save watermark settings to database
     */
    public function saveSettings($settings)
    {
        // Validate settings
        $validated = [
            'vertical_position'   => $settings['vertical_position'] ?? 'middle',
            'horizontal_position' => $settings['horizontal_position'] ?? 'center',
            'rotation'            => (int) ($settings['rotation'] ?? 0),
            'transparency'        => min(100, max(0, (int) ($settings['transparency'] ?? 30))),
            'font_family'         => $settings['font_family'] ?? 'Arial',
            'font_size'           => (int) ($settings['font_size'] ?? 40),
            'layer'               => $settings['layer'] ?? 'above'
        ];

        // Update or create settings (we only keep one row)
        $setting = WatermarkSetting::first();
        
        if ($setting) {
            $setting->update($validated);
        } else {
            WatermarkSetting::create($validated);
        }

        return $validated;
    }

    /**
     * Add watermark to a PDF file.
     *
     * @param string $inputPath Absolute path to the input PDF
     * @param string $watermarkText Text to watermark
     * @param string $outputDir Directory to save the output file
     * @param string $outputFilename Optional output filename
     * @param array $settings Custom watermark settings (optional)
     * @return string|null Path to the watermarked file, or null on failure
     */
    public function addWatermark($inputPath, $watermarkText, $outputDir, $outputFilename = null, $settings = null)
    {
        if (!$this->ilovepdf) {
            Log::error('WatermarkService: iLovePDF keys not configured.');
            return null;
        }

        try {
            // Get settings - use provided settings or load from cache
            $watermarkSettings = $settings ?? $this->getSettings();

            // Create a new task
            $task = $this->ilovepdf->newTask('watermark');

            // Add the file
            $fileId = $task->addFile($inputPath);

            // Process with watermark settings
            $task->setText($watermarkText);
            $task->setMode('text');
            $task->setVerticalPosition($watermarkSettings['vertical_position']);
            $task->setHorizontalPosition($watermarkSettings['horizontal_position']);
            $task->setRotation($watermarkSettings['rotation']);
            $task->setTransparency($watermarkSettings['transparency']); // 0-100
            $task->setFontFamily($watermarkSettings['font_family']);
            $task->setFontSize($watermarkSettings['font_size']);
            $task->setLayer($watermarkSettings['layer']);
            
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
