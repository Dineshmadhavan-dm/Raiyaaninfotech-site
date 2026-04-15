<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';
    protected $primaryKey = 'cl_id';

    protected $fillable = [
        'cl_name',
        'cl_email',
        'cl_password',
        'cl_image',
        'delete_status'
    ];

    protected $hidden = ['cl_password'];

    public function setClPasswordAttribute($value)
    {
        $this->attributes['cl_password'] = Hash::make($value);
    }

    public function scopeActive($query)
    {
        return $query->where('delete_status', 1);
    }
}
