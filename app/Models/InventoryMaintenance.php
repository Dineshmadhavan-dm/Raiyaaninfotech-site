<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMaintenance extends Model
{protected $table = 'inventory_maintenance';
    protected $fillable = [
        'item_id',
        'issue_description',
        'maintenance_type',
        'cost',
        'vendor_name',
        'start_date',
        'end_date',
        'status',
        'remarks',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }


    public function maintenances()
{
    return $this->hasMany(InventoryMaintenance::class, 'item_id', 'id');
}
}
