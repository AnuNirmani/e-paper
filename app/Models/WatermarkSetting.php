<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatermarkSetting extends Model
{
    protected $table = 'watermark_settings';

    protected $fillable = [
        'vertical_position',
        'horizontal_position',
        'rotation',
        'transparency',
        'font_family',
        'font_size',
        'layer'
    ];

    protected $casts = [
        'rotation' => 'integer',
        'transparency' => 'integer',
        'font_size' => 'integer',
    ];
}
