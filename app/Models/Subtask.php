<?php

namespace App\Models;

use App\Mail\SubtaskCreatedEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class Subtask extends Model
{
    protected $primaryKey = 'stask_id';

    protected $fillable = [
        'stask_name',
        'stask_desc',
        'stask_avater',
        'stask_attachment',
        'stask_deadline',
        'stask_task',
        'stask_assignedto',
        'stask_priority',
        'stask_accessmod',
        'delete_status',
        'stask_status',
        'stask_overdue',
        'stask_onprogress',
        'stask_complete',
        'stask_emailvia'
    ];

    protected $casts = [
        'stask_onprogress' => 'date',
        'stask_complete' => 'date',
        'stask_deadline' => 'date',
        'stask_attachment' => 'array',
        'stask_accessmod' => 'boolean',
         'stask_emailvia' => 'boolean',
        'delete_status' => 'boolean',
    ];



    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'stask_task', 'task_id');
    }

    public function assignedEmployee()
    {
        return $this->belongsTo(Employee::class, 'stask_assignedto', 'emp_id');
    }

    public function pmtsImages()
    {
        return $this->hasMany(PmtsImage::class, 'menu_id', 'stask_id')
            ->where('menu_type', 'subtask')
            ->where('delete_status', 1);
    }

    public function getFormattedDeadlineAttribute()
    {
        return $this->stask_deadline->format('M d, Y');
    }

    public function getStatusTextAttribute()
    {
        return match ($this->stask_status) {
            0 => 'Created',
            1 => 'On Progress',
            2 => 'Completed',
            default => 'Unknown'
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->stask_status) {
            0 => 'bg-secondary',
            1 => 'bg-warning',
            2 => 'bg-success',
            default => 'bg-secondary'
        };
    }

    public function getPriorityTextAttribute()
    {
        return match ($this->stask_priority) {
            1 => 'Low',
            2 => 'Medium',
            3 => 'High',
            default => 'Unknown'
        };
    }

    public function getPriorityClassAttribute()
    {
        return match ($this->stask_priority) {
            1 => 'bg-success',
            2 => 'bg-warning',
            3 => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    public function getOverdueTextAttribute()
    {
        $overdueDays = $this->getCurrentOverdueDays();

        if ($overdueDays === 0) {
            return 'On Time';
        }

        return $this->formatOverdueText($overdueDays);
    }

    private function formatOverdueText($days)
    {
        if ($days < 30) {
            return $days . ' day' . ($days > 1 ? 's' : '') . ' overdue';
        } else {
            $months = floor($days / 30);
            $remainingDays = $days % 30;

            if ($remainingDays === 0) {
                return $months . ' month' . ($months > 1 ? 's' : '') . ' overdue';
            } else {
                return $months . ' month' . ($months > 1 ? 's' : '') . ' ' . $remainingDays . ' day' . ($remainingDays > 1 ? 's' : '') . ' overdue';
            }
        }
    }

    public function calculateOverdueDays()
    {
        $today = Carbon::now();
        $deadline = Carbon::parse($this->stask_deadline);

        if ($this->stask_status === 2 && $this->stask_complete) {
            $completionDate = Carbon::parse($this->stask_complete);

            if ($completionDate->gt($deadline)) {
                return $deadline->diffInDays($completionDate);
            }
            return 0;
        }

        if ($today->gt($deadline)) {
            return $deadline->diffInDays($today);
        }

        return 0;
    }

    public function getCurrentOverdueDays()
    {
        if ($this->stask_status === 2 && $this->stask_overdue !== null) {
            return max(0, $this->stask_overdue);
        }

        return $this->calculateOverdueDays();
    }

    public function getIsOverdueAttribute()
    {
        return $this->getCurrentOverdueDays() > 0;
    }

    public function updateStatus($newStatus)
    {
        $updates = ['stask_status' => $newStatus];

        if ($newStatus == 1 && !$this->stask_onprogress) {
            $updates['stask_onprogress'] = Carbon::now();
        }

        if ($newStatus == 2 && !$this->stask_complete) {
            $updates['stask_complete'] = Carbon::now();

            $completionDate = Carbon::now();
            $deadline = Carbon::parse($this->stask_deadline);

            if ($completionDate->gt($deadline)) {
                $updates['stask_overdue'] = $deadline->diffInDays($completionDate);
            } else {
                $updates['stask_overdue'] = 0;
            }
        }

        if ($newStatus == 1) {
            $today = Carbon::now();
            $deadline = Carbon::parse($this->stask_deadline);

            if ($today->gt($deadline)) {
                $updates['stask_overdue'] = $deadline->diffInDays($today);
            } else {
                $updates['stask_overdue'] = 0;
            }
        }

        $this->update($updates);
    }

    public function getAttachmentTypeAttribute()
    {
        if (!$this->stask_attachment) return null;

        $extension = pathinfo($this->stask_attachment, PATHINFO_EXTENSION);

        $imageTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $documentTypes = ['pdf', 'doc', 'docx'];
        $presentationTypes = ['ppt', 'pptx'];
        $spreadsheetTypes = ['csv', 'xlsx', 'xls'];

        if (in_array($extension, $imageTypes)) return 'image';
        if (in_array($extension, $documentTypes)) return 'document';
        if (in_array($extension, $presentationTypes)) return 'presentation';
        if (in_array($extension, $spreadsheetTypes)) return 'spreadsheet';

        return 'other';
    }
    public function sendEmailNotification($isEdit = false)
{
    if (!$this->stask_assignedto) {
        return;
    }

    $assignedEmployee = Employee::find($this->stask_assignedto);

    if (!$assignedEmployee || !$assignedEmployee->email_company) {
        return;
    }

    $recipients[] = [
        'email' => $assignedEmployee->email_company,
        'name' => $assignedEmployee->fullname
    ];

    $senderName = auth()->user() ? auth()->user()->fullname : 'Admin';

    foreach ($recipients as $recipient) {
        Mail::to($recipient['email'])
            ->send(new SubtaskCreatedEmail($this, $senderName, $recipient['name'], $isEdit));
    }

    $this->update(['stask_emailvia' => 1]);
}
}
