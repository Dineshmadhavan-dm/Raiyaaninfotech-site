<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Employee;
use App\Mail\ModuleCreatedEmail;
use Illuminate\Support\Facades\Mail;

class Modulo extends Model
{
    protected $primaryKey = 'mod_id';

    protected $fillable = [
        'mod_name',
        'mod_desc',
        'mod_avater',
        'mod_attachment',
        'mod_deadline',
        'mod_project',
        'mod_member',
        'mod_accessmod',
        'delete_status',
        'mod_status',
        'mod_overdue',
        'mod_onprogress',
        'mod_complete',
        'mod_emailvia'
    ];

    protected $casts = [
        'mod_onprogress' => 'date',
        'mod_complete' => 'date',
        'mod_deadline' => 'date',
        'mod_member' => 'array',
        'mod_attachment' => 'array',
        'mod_accessmod' => 'boolean',
        'mod_emailvia' => 'boolean',
        'delete_status' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'mod_project', 'pro_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'task_modulo', 'mod_id')
            ->where('delete_status', 1);
    }

    public function pmtsImages()
    {
        return $this->hasMany(PmtsImage::class, 'menu_id', 'mod_id')
            ->where('menu_type', 'modulo')
            ->where('delete_status', 1);
    }

    public function getFormattedDeadlineAttribute()
    {
        return $this->mod_deadline->format('M d, Y');
    }

    public function getStatusTextAttribute()
    {
        return match ($this->mod_status) {
            0 => 'Created',
            1 => 'On Progress',
            2 => 'Completed',
            default => 'Unknown'
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->mod_status) {
            0 => 'bg-secondary',
            1 => 'bg-warning',
            2 => 'bg-success',
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
        $deadline = Carbon::parse($this->mod_deadline);

        if ($this->mod_status === 2 && $this->mod_complete) {
            $completionDate = Carbon::parse($this->mod_complete);

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
        if ($this->mod_status === 2 && $this->mod_overdue !== null) {
            return max(0, $this->mod_overdue);
        }

        return $this->calculateOverdueDays();
    }

    public function getIsOverdueAttribute()
    {
        return $this->getCurrentOverdueDays() > 0;
    }

    public function updateStatus($newStatus)
    {
        $updates = ['mod_status' => $newStatus];

        if ($newStatus == 1 && !$this->mod_onprogress) {
            $updates['mod_onprogress'] = Carbon::now();
        }

        if ($newStatus == 2 && !$this->mod_complete) {
            $updates['mod_complete'] = Carbon::now();

            $completionDate = Carbon::now();
            $deadline = Carbon::parse($this->mod_deadline);

            if ($completionDate->gt($deadline)) {
                $updates['mod_overdue'] = $deadline->diffInDays($completionDate);
            } else {
                $updates['mod_overdue'] = 0;
            }
        }

        if ($newStatus == 1) {
            $today = Carbon::now();
            $deadline = Carbon::parse($this->mod_deadline);

            if ($today->gt($deadline)) {
                $updates['mod_overdue'] = $deadline->diffInDays($today);
            } else {
                $updates['mod_overdue'] = 0;
            }
        }

        $this->update($updates);
    }

public function sendEmailNotification($isEdit = false)
{
    $recipients = [];
    $memberIds = is_array($this->mod_member) ? $this->mod_member : json_decode($this->mod_member, true);
    $teamMembers = Employee::whereIn('emp_id', $memberIds)->get();

    foreach ($teamMembers as $member) {
        if ($member->email_company) {
            $recipients[] = [
                'email' => $member->email_company,
                'name' => $member->fullname
            ];
        }
    }

    $recipients = array_unique($recipients, SORT_REGULAR);

    $senderName = auth()->user() ? auth()->user()->fullname : 'Admin';

    foreach ($recipients as $recipient) {
        Mail::to($recipient['email'])
            ->send(new ModuleCreatedEmail($this, $senderName, $recipient['name'], $isEdit));
    }

    $this->update(['mod_emailvia' => 1]);
}
}
