<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PmtsImage extends Model
{
    protected $primaryKey = 'pmts_id';

    protected $fillable = [
        'pmtsimage_name',
        'menu_id',
        'menu_type',
        'delete_status'
    ];

    protected $casts = [
        'delete_status' => 'boolean',
    ];

    // Relationship to project
    public function project()
    {
        return $this->belongsTo(Project::class, 'menu_id', 'pro_id')
            ->where('menu_type', 'project');
    }
}
