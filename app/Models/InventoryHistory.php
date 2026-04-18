<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryHistory extends Model
{protected $table = 'inventory_history';
    protected $fillable = [
        'item_id',
        'employee_id',
        'action_type',
        'old_status',
        'new_status',
        'notes',
        'action_date',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }
}
