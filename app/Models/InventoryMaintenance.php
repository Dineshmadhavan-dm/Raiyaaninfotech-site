<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMaintenance extends Model
{
    protected $table = 'inventory_maintenance';

    protected $fillable = [
        'item_id',
        'employee_id',        // ✅ ADD THIS - to track who had it
        'issue_description',
        'maintenance_type',
        'cost',
        'vendor_name',
        'start_date',
        'end_date',
        'status',
        'remarks',
    ];

    // ✅ Relationship to InventoryItem
    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    // ✅ Relationship to Employee (who had the item)
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_id');
    }


}
