<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Model


{

    use HasRoles;
    protected static function boot()
    {
        parent::boot();

        static::created(function ($employee) {
            // If employee_id is not already set
            if (!$employee->employee_id) {
                $employee->employee_id = 'RAEMP' . str_pad($employee->emp_id, 4, '0', STR_PAD_LEFT);
                $employee->save();
            }
        });
    }
    public function getLoginAccessTextAttribute(): string
    {
        return match ($this->login_access) {
            1 => 'Yes',
            0 => 'No',
            default => 'Unknown',
        };
    }

    // In Employee model



    // In your Employee or Pastemployee model

    const STEP_PERSONAL = 1;
    const STEP_FAMILY = 2;
    const STEP_EDUCATION = 3;
    const STEP_PAST_EMPLOYMENT = 4;
    const STEP_CURRENT_EMPLOYMENT = 5;
    const STEP_REFERENCES = 6;

    protected $table = 'employees';
    protected $primaryKey = 'emp_id';

    protected $fillable = [



        'fullname',
        'fathername',
        'image',
        'address',
        'personal_email',
        'personal_mobile',
        'bloodgroup',
        'gender',
        'marital_status',
        'dob',
        'pancard_no',
        'aadhaar_no',
        'status_for_stepform',

        'pincode',
        'country',
        'state',
        'city',
        'c_person_emergency',
        'relationship',
        'emergency_contact',


        'flatno',
        'street',



        'jobtype',


        'cur_department',
        'cur_designation',

        'cur_location',
        'email_company',
        'password_company',
        'cur_annual_ctc',
        'dojprovision_from_date',
        'provision_to_date',
        'inmonth',
        'login_access',

        'delete_status'



    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    protected $casts = [
        'column_preferences' => 'array',

    ];

    // In your Employee model
    public function bloodGroupid()
    {
        return $this->belongsTo(BloodGroup::class, 'bloodgroup', 'bloodgroup_id');
    }
    public function relationShipid()
    {
        return $this->belongsTo(Relationship::class, 'relationship', 'relationship_id');
    }

    public function jobTypeid()
    {
        return $this->belongsTo(Jobtype::class, 'jobtype', 'jobtype_id');
    }
    public function Branchid()
    {
        return $this->belongsTo(Branch::class, 'cur_location', 'branch_id');
    }
    public function Departmentid()
    {
        return $this->belongsTo(Department::class, 'cur_department', 'dep_id');
    }
    public function Designationid()
    {
        return $this->belongsTo(Designation::class, 'cur_designation', 'des_id');
    }



    //above code sub table








    public function shifts()
    {
        return $this->hasMany(Shift::class, 'employee_id', 'emp_id');
    }
    public function attendances()
    {
        return $this->hasMany(Shift::class, 'employee_id', 'emp_id');
    }
    public function families()
    {
        return $this->hasMany(Family::class, 'employee_id', 'emp_id');
    }
    public function educations()
    {
        return $this->hasMany(Education::class, 'employee_id', 'emp_id');
    }
    public function pastemps()
    {
        return $this->hasMany(Pastemployee::class, 'employee_id', 'emp_id');
    }

    public function prorefs()
    {
        return $this->hasMany(Professionalreference::class, 'employee_id', 'emp_id');
    }




    public function resignation()
{
    return $this->hasOne(Resignation::class, 'employee_id', 'emp_id')
        ->where('delete_status', 1);
}


    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'employee_id', 'emp_id');
    }
    public function terminations()
    {
        return $this->hasMany(Termination::class, 'employee_id', 'emp_id');
    }
}
