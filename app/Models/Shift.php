<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $table = 'shifts';
    protected $primaryKey = 'shift_id';

    protected $fillable = [
        'employee_id',

        'department_name',
        'department_admin',
        'employee_name',
        'shift_type',
        'shift_from_time',
        'shift_to_time',
        'assign_shift',
        'date_no',
        'date_range_from',
        'date_range_to',
        'month_year',
        'notes',
         // ✅ NEW
    'holiday_type',
    'occasion',

        'delete_status'
    ];

    public function employees()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_id');
    }
}
