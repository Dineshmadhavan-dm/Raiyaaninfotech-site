<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emprole extends Model
{
    protected $table = 'emproles';
    protected $primaryKey = 'role_id';

    protected $fillable = [
        'role_name',
        'delete_status'
    ];
}
