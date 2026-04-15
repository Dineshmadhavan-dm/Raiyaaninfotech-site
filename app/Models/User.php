<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public const CATEGORIES = [
        1 => 'Super admin',
        2 => 'Admin',
        3 => 'Employee',
    ];
    public function getLoginAccessTextAttribute(): string
    {
        return match ($this->login_access) {
            1 => 'Yes',
            0 => 'No',
            default => 'Unknown',
        };
    }

    protected $fillable = [
        'name',
        'email',
        'employeerole_id',
        'password',
        'image',
        'categorie',
        'login_access',
        'delete_status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    // In User.php model


    // In your User model
    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
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
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // In User.php model
    public function settings()
    {
        return $this->hasOne(Setting::class);
    }
    // In your User model
    public function canLogin()
    {
        return $this->login_access === 'yes' && $this->delete_status;
    }
}
