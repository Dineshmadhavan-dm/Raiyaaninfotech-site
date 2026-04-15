<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    protected $table = 'relationships';
    protected $primaryKey = 'relationship_id';

    protected $fillable = [
        'relationship_name',
        'delete_status'
    ];
}
