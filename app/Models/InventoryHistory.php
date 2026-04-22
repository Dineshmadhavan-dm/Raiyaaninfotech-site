<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryHistory extends Model
{protected $table = 'inventory_history';
    protected $fillable = [
        'item_id',
        'employee_id',
        'action_type',
        'action_date',
    ];

    protected $casts = [
    'action_type' => 'integer',
];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }
}
