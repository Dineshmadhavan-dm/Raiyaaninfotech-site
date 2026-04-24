<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryHistory extends Model
{
    protected $table = 'inventory_history';

    protected $fillable = [
        'item_id',
        'employee_id',
        'assignment_id',
        'maintenance_id',
        'category_id',
        'module',
        'action',
        'sub_action',
        'old_data',
        'new_data',
        'action_date',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'action_date' => 'datetime',
    ];

    // MODULES
    const MODULE_ITEM = 'item';
    const MODULE_ASSIGNMENT = 'assignment';
    const MODULE_MAINTENANCE = 'maintenance';
    const MODULE_CATEGORY = 'category';

    // ACTIONS
    const ACTION_CREATED = 'created';
    const ACTION_UPDATED = 'updated';
    const ACTION_ASSIGNED = 'assigned';
    const ACTION_RETURNED = 'returned';
    const ACTION_DELETED = 'deleted';

    // SUB ACTIONS
    const SUB_SERVICE = 'service';
    const SUB_UPGRADE = 'upgrade';
    const SUB_SCRAP = 'scrap';

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    public function assignment()
    {
        return $this->belongsTo(InventoryAssignment::class, 'assignment_id');
    }

    public function maintenance()
    {
        return $this->belongsTo(InventoryMaintenance::class, 'maintenance_id');
    }

    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'category_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_id');
    }

    // REMOVE THIS - it doesn't work because there's no department_id column
    // public function department()
    // {
    //     return $this->belongsTo(Department::class, 'department_id', 'dep_id');
    // }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS FOR DEPARTMENT NAME FROM JSON
    |--------------------------------------------------------------------------
    */

    /**
     * Get department name from new_data JSON
     */
    public function getNewDepartmentName()
    {
        if ($this->new_data && isset($this->new_data['department_id'])) {
            $deptId = $this->new_data['department_id'];
            $department = Department::where('dep_id', $deptId)->first();
            return $department ? $department->dep_name : $deptId;
        }
        return null;
    }

    /**
     * Get department name from old_data JSON
     */
    public function getOldDepartmentName()
    {
        if ($this->old_data && isset($this->old_data['department_id'])) {
            $deptId = $this->old_data['department_id'];
            $department = Department::where('dep_id', $deptId)->first();
            return $department ? $department->dep_name : $deptId;
        }
        return null;
    }
    /*
|--------------------------------------------------------------------------
| HELPER METHODS FOR CATEGORY NAME FROM JSON
|--------------------------------------------------------------------------
*/

/**
 * Get category name from new_data JSON
 */
public function getNewCategoryName()
{
    if ($this->new_data && isset($this->new_data['category_id'])) {
        $catId = $this->new_data['category_id'];
        $category = InventoryCategory::find($catId);
        return $category ? $category->category_name : $catId;
    }
    return null;
}

/**
 * Get category name from old_data JSON
 */
public function getOldCategoryName()
{
    if ($this->old_data && isset($this->old_data['category_id'])) {
        $catId = $this->old_data['category_id'];
        $category = InventoryCategory::find($catId);
        return $category ? $category->category_name : $catId;
    }
    return null;
}

    /*
    |--------------------------------------------------------------------------
    | HELPER FUNCTION
    |--------------------------------------------------------------------------
    */

    public static function log($data)
    {
        return self::create(array_merge([
            'action_date' => now()
        ], $data));
    }
}
