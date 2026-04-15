<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chattask extends Model
{
    use HasFactory;

    protected $table = 'chattasks';
    protected $primaryKey = 'chat_id';

    protected $fillable = [
        'task_id',
        'subtask_id',
        'sender_id',
        'receiver_id',
        'message',
        'chatfile',
        'delete_status',
        'is_read'
    ];

    protected $casts = [
        'chatfile' => 'array',
        'delete_status' => 'boolean',
          'is_read' => 'boolean'
    ];

  protected $appends = ['files_info'];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'task_id');
    }

    public function subtask()
    {
        return $this->belongsTo(Subtask::class, 'subtask_id', 'stask_id');
    }

    public function sender()
    {
        return $this->belongsTo(Employee::class, 'sender_id', 'emp_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Employee::class, 'receiver_id', 'emp_id');
    }

    public function chatImages()
    {
        return Chatimage::whereIn('chatimage_id', $this->chatfile ?? [])
            ->where('delete_status', 1)
            ->get();
    }



public function getFilesInfoAttribute()
{
    if (empty($this->chatfile)) {
        return [];
    }

    $files = [];
    $fileIds = is_string($this->chatfile) ? json_decode($this->chatfile, true) : $this->chatfile;

    if (is_array($fileIds) && !empty($fileIds)) {
        foreach ($fileIds as $chatimageId) {
            $chatImage = Chatimage::where('chatimage_id', $chatimageId)
                ->where('delete_status', 1)
                ->first();

            if ($chatImage) {
                $isImage = in_array(strtolower($chatImage->file_type), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg']);

                $files[] = [
                    'id' => $chatImage->chatimage_id,
                    'name' => $chatImage->file_name,
                    'url' => asset('chat_files/' . $chatImage->file_name),
                    'type' => $chatImage->file_type,
                    'size' => $chatImage->file_size,
                    'is_image' => $isImage,
                    'icon' => $this->getFileIcon($chatImage->file_type, $isImage)
                ];
            }
        }
    }

    return $files;
}

private function getFileIcon($fileType, $isImage)
{
    if ($isImage) {
        return 'bi-file-image';
    }

    $extension = strtolower($fileType);
    $icons = [
        'pdf' => 'bi-file-earmark-pdf',
        'doc' => 'bi-file-earmark-word',
        'docx' => 'bi-file-earmark-word',
        'xls' => 'bi-file-earmark-excel',
        'xlsx' => 'bi-file-earmark-excel',
        'csv' => 'bi-file-earmark-spreadsheet',
        'ppt' => 'bi-file-earmark-ppt',
        'pptx' => 'bi-file-earmark-ppt',
        'zip' => 'bi-file-earmark-zip',
        'rar' => 'bi-file-earmark-zip',
        'txt' => 'bi-file-earmark-text',
    ];

    return $icons[$extension] ?? 'bi-file-earmark';
}

    public function hasFiles()
    {
        return !empty($this->chatfile) && count($this->chatfile) > 0;
    }


}
