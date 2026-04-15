<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Mail\TaskCreatedEmail;
use Illuminate\Support\Facades\Mail;

class Task extends Model
{
    protected $primaryKey = 'task_id';

    protected $fillable = [
        'task_name',
        'task_desc',
        'task_avater',
        'task_attachment',
        'task_deadline',
        'task_modulo',
        'task_assignedto',
        'task_priority',
        'task_accessmod',
        'delete_status',
        'task_status',
        'task_overdue',
        'task_onprogress',
        'task_complete',
        'task_emailvia'
    ];

    protected $casts = [
        'task_onprogress' => 'date',
        'task_complete' => 'date',
        'task_deadline' => 'date',
        'task_attachment' => 'array',
        'task_accessmod' => 'boolean',
        'task_emailvia' => 'boolean',
        'delete_status' => 'boolean',
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'task_modulo', 'mod_id');
    }

    public function assignedEmployee()
    {
        return $this->belongsTo(Employee::class, 'task_assignedto', 'emp_id');
    }

    public function subtasks()
    {
        return $this->hasMany(Subtask::class, 'stask_task', 'task_id')
            ->where('delete_status', 1);
    }

    public function pmtsImages()
    {
        return $this->hasMany(PmtsImage::class, 'menu_id', 'task_id')
            ->where('menu_type', 'task')
            ->where('delete_status', 1);
    }

    public function getFormattedDeadlineAttribute()
    {
        return $this->task_deadline->format('M d, Y');
    }

    public function getStatusTextAttribute()
    {
        return match ($this->task_status) {
            0 => 'Created',
            1 => 'On Progress',
            2 => 'Completed',
            default => 'Unknown'
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->task_status) {
            0 => 'bg-secondary',
            1 => 'bg-warning',
            2 => 'bg-success',
            default => 'bg-secondary'
        };
    }

    public function getPriorityTextAttribute()
    {
        return match ($this->task_priority) {
            1 => 'Low',
            2 => 'Medium',
            3 => 'High',
            default => 'Unknown'
        };
    }

    public function getPriorityClassAttribute()
    {
        return match ($this->task_priority) {
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
        $deadline = Carbon::parse($this->task_deadline);

        if ($this->task_status === 2 && $this->task_complete) {
            $completionDate = Carbon::parse($this->task_complete);

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
        if ($this->task_status === 2 && $this->task_overdue !== null) {
            return max(0, $this->task_overdue);
        }

        return $this->calculateOverdueDays();
    }

    public function getIsOverdueAttribute()
    {
        return $this->getCurrentOverdueDays() > 0;
    }

    public function updateStatus($newStatus)
    {
        $updates = ['task_status' => $newStatus];

        if ($newStatus == 1 && !$this->task_onprogress) {
            $updates['task_onprogress'] = Carbon::now();
        }

        if ($newStatus == 2 && !$this->task_complete) {
            $updates['task_complete'] = Carbon::now();

            $completionDate = Carbon::now();
            $deadline = Carbon::parse($this->task_deadline);

            if ($completionDate->gt($deadline)) {
                $updates['task_overdue'] = $deadline->diffInDays($completionDate);
            } else {
                $updates['task_overdue'] = 0;
            }
        }

        if ($newStatus == 1) {
            $today = Carbon::now();
            $deadline = Carbon::parse($this->task_deadline);

            if ($today->gt($deadline)) {
                $updates['task_overdue'] = $deadline->diffInDays($today);
            } else {
                $updates['task_overdue'] = 0;
            }
        }

        $this->update($updates);
    }

    public function sendEmailNotification($isEdit = false)
    {
        if (!$this->task_assignedto) {
            return;
        }

        $assignedEmployee = Employee::find($this->task_assignedto);

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
                ->send(new TaskCreatedEmail($this, $senderName, $recipient['name'], $isEdit));
        }

        $this->update(['task_emailvia' => 1]);
    }
}
