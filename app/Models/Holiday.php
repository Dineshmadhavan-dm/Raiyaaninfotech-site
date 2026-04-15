<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{

    protected $primaryKey = 'holiday_id';

    // In Holiday.php model

    protected $fillable = [

        'holidaytype',
        'holiday_date',
        'holiday_department',

        'occasion',
        'delete_status'
    ];

    // Add relationships
    public function attendance()
    {
        return $this->belongsTo(Attendance::class, 'attendance_id', 'attendance_id');
    }
    public function employees()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_id');
    }


    public function holidayType()
    {
        return $this->belongsTo(Holidaytype::class, 'holidaytype', 'holidaytype_id');
    }
}
