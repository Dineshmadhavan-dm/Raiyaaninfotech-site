<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Employee;
use App\Mail\ProjectCreatedEmail;
use Illuminate\Support\Facades\Mail;

class Project extends Model
{
    protected $primaryKey = 'pro_id';
    protected $fillable = [
        'pro_name',
        'pro_desc',
        'pro_avater',
        'pro_attachment',
        'pro_deadline',
        'pro_client',
        'pro_lead',
        'pro_head',
        'pro_member',
        'pro_accessmod',
        'delete_status',
        'pro_status',
        'pro_overdue',
        'pro_onprogress',
        'pro_complete',
        'pro_emailvia'
    ];
    protected $casts = [
        'pro_onprogress' => 'date',
        'pro_complete' => 'date',
        'pro_deadline' => 'date',
        'pro_member' => 'array',
        'pro_attachment' => 'array',
        'pro_accessmod' => 'boolean',
        'pro_emailvia' => 'boolean',
        'delete_status' => 'boolean',
    ];
    public function projectLead()
    {
        return $this->belongsTo(Employee::class, 'pro_lead', 'emp_id');
    }
    public function projectHead()
    {
        return $this->belongsTo(Employee::class, 'pro_head', 'emp_id');
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'pro_client', 'cl_id');
    }
    public function modules()
    {
        return $this->hasMany(Modulo::class, 'mod_project', 'pro_id')
            ->where('delete_status', 1);
    }
    public function pmtsImages()
    {
        return $this->hasMany(PmtsImage::class, 'menu_id', 'pro_id')
            ->where('menu_type', 'project')
            ->where('delete_status', 1);
    }
    public function getFormattedDeadlineAttribute()
    {
        return $this->pro_deadline->format('M d, Y');
    }
    public function getStatusTextAttribute()
    {
        return match ($this->pro_status) {
            0 => 'Created',
            1 => 'On Progress',
            2 => 'Completed',
            default => 'Unknown'
        };
    }
    public function getStatusBadgeClassAttribute()
    {
        return match ($this->pro_status) {
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
        $deadline = Carbon::parse($this->pro_deadline);
        if ($this->pro_status === 2 && $this->pro_complete) {
            $completionDate = Carbon::parse($this->pro_complete);
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
        if ($this->pro_status === 2 && $this->pro_overdue !== null) {
            return max(0, $this->pro_overdue);
        }
        return $this->calculateOverdueDays();
    }
    public function getIsOverdueAttribute()
    {
        return $this->getCurrentOverdueDays() > 0;
    }
    public function updateStatus($newStatus)
    {
        $updates = ['pro_status' => $newStatus];
        if ($newStatus == 1 && !$this->pro_onprogress) {
            $updates['pro_onprogress'] = Carbon::now();
        }
        if ($newStatus == 2 && !$this->pro_complete) {
            $updates['pro_complete'] = Carbon::now();
            $completionDate = Carbon::now();
            $deadline = Carbon::parse($this->pro_deadline);
            if ($completionDate->gt($deadline)) {
                $updates['pro_overdue'] = $deadline->diffInDays($completionDate);
            } else {
                $updates['pro_overdue'] = 0;
            }
        }
        if ($newStatus == 1) {
            $today = Carbon::now();
            $deadline = Carbon::parse($this->pro_deadline);
            if ($today->gt($deadline)) {
                $updates['pro_overdue'] = $deadline->diffInDays($today);
            } else {
                $updates['pro_overdue'] = 0;
            }
        }
        $this->update($updates);
    }
public function sendEmailNotification($isEdit = false)
{
    $recipients = [];
    $projectHead = Employee::find($this->pro_head);
    $projectLead = Employee::find($this->pro_lead);
    $memberIds = is_array($this->pro_member) ? $this->pro_member : json_decode($this->pro_member, true);
    $teamMembers = Employee::whereIn('emp_id', $memberIds)->get();

    if ($projectHead && $projectHead->email_company) {
        $recipients[] = [
            'email' => $projectHead->email_company,
            'name' => $projectHead->fullname
        ];
    }

    if ($projectLead && $projectLead->email_company) {
        $recipients[] = [
            'email' => $projectLead->email_company,
            'name' => $projectLead->fullname
        ];
    }

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
            ->send(new ProjectCreatedEmail($this, $senderName, $recipient['name'], $isEdit));
    }
}
}
