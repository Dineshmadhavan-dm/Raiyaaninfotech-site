<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Handover extends Model
{
    protected $primaryKey = 'handover_id';

    protected $fillable = [
        'handover_employee_id',
        'handover_employee_email',
        'handover_department',
        'handover_designation',
        'takeover_employee_id',
        'takeover_employee_email',
        'takeover_department',
        'takeover_designation',
        'reason',
        'reason_other',
        'handover_date',
        'tasks',
        'other_documents',
        'resignation_documents',
        'handover_signature',
        'takeover_signature',
        'delete_status',
    ];

    protected $casts = [
        'handover_date' => 'date',
        'tasks' => 'array',
        'delete_status' => 'boolean',
    ];

    // Relationships
    public function handoverEmployee()
    {
        return $this->belongsTo(Employee::class, 'handover_employee_id', 'emp_id');
    }

    public function takeoverEmployee()
    {
        return $this->belongsTo(Employee::class, 'takeover_employee_id', 'emp_id');
    }

    public function handoverDepartmentRelation()
    {
        return $this->belongsTo(Department::class, 'handover_department', 'dep_id');
    }

    public function handoverDesignationRelation()
    {
        return $this->belongsTo(Designation::class, 'handover_designation', 'des_id');
    }

    public function takeoverDepartmentRelation()
    {
        return $this->belongsTo(Department::class, 'takeover_department', 'dep_id');
    }

    public function takeoverDesignationRelation()
    {
        return $this->belongsTo(Designation::class, 'takeover_designation', 'des_id');
    }

    // ✅ Accessor for readable reason text
    public function getReasonTextAttribute()
    {
        $reasons = [
            1 => 'Vacation',
            2 => 'End of Employment',
            3 => 'Transfer',
            4 => 'Termination',
            0 => 'Other',
        ];

        // If "Other" (5) and a custom reason is provided
        if ($this->reason == 0 && !empty($this->reason_other)) {
            return $this->reason_other;
        }

        return $reasons[$this->reason] ?? 'Unknown';
    }
}
