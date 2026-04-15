<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $primaryKey = 'set_id';
    protected $table = 'settings';

    protected $fillable = [
        'user_id',
        'weblogo',
        'webname',
        'favlogo',
        'p_color',
        's_color',
        'nh_color',
        'h_color',
        'selected_palette',
        'enable_palettes',
        'enable_custom_colors'
    ];

    protected $casts = [
        'enable_palettes' => 'boolean',
        'enable_custom_colors' => 'boolean'
    ];
}
