<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chatimage extends Model
{
    use HasFactory;

    protected $table = 'chatimages';
    protected $primaryKey = 'chatimage_id';

    protected $fillable = [
        'task_id',
        'subtask_id',
        'type',
        'file_name',
        'file_type',
        'file_size',
        'delete_status'
    ];

    protected $casts = [
        'delete_status' => 'boolean'
    ];

    protected $appends = ['url', 'is_image'];

    public function getUrlAttribute()
    {
        return asset('chat_files/' . $this->file_name);
    }

    public function getIsImageAttribute()
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
        return in_array(strtolower($this->file_type), $imageExtensions);
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'task_id');
    }

    public function subtask()
    {
        return $this->belongsTo(Subtask::class, 'subtask_id', 'stask_id');
    }
}
