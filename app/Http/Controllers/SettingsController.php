<?php

namespace App\Http\Controllers;

use App\Services\WatermarkService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    protected $watermarkService;

    public function __construct(WatermarkService $watermarkService)
    {
        $this->watermarkService = $watermarkService;
    }

    /**
     * Show watermark settings page
     */
    public function watermark()
    {
        $settings = $this->watermarkService->getSettings();
        
        $verticalOptions = ['top', 'middle', 'bottom'];
        $horizontalOptions = ['left', 'center', 'right'];
        $fontFamilies = [
            'Arial',
            'Arial Unicode MS',
            'Verdana',
            'Courier',
            'Times New Roman',
            'Comic Sans MS',
            'WenQuanYi Zen Hei',
            'Lohit Marathi'
        ];
        $layerOptions = ['above', 'below'];

        return view('settings.watermark', compact('settings', 'verticalOptions', 'horizontalOptions', 'fontFamilies', 'layerOptions'));
    }

    /**
     * Update watermark settings
     */
    public function updateWatermark(Request $request)
    {
        $validated = $request->validate([
            'vertical_position'   => 'required|in:top,middle,bottom',
            'horizontal_position' => 'required|in:left,center,right',
            'rotation'            => 'required|integer|min:0|max:360',
            'transparency'        => 'required|integer|min:0|max:100',
            'font_family'         => 'required|string',
            'font_size'           => 'required|integer|min:8|max:72',
            'layer'               => 'required|in:above,below'
        ]);

        $this->watermarkService->saveSettings($validated);

        return redirect()->route('settings.watermark')
                       ->with('success', 'Watermark settings updated successfully!');
    }
}
