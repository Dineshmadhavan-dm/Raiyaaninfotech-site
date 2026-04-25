<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryAssignment extends Model
{
    protected $fillable = [
        'item_id',
        'employee_id',
        'department_id',
        'assigned_date',
        'return_date',
        'status',
        'condition_status',
        'remarks',
    ];

    protected $casts = [
    'status' => 'integer',
    'condition_status' => 'integer',
];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

  public function employee()
{
    return $this->belongsTo(Employee::class, 'employee_id', 'emp_id');
}
public function department()
{
    return $this->belongsTo(Department::class, 'department_id', 'dep_id');
}
}
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryCategory extends Model
{
    protected $fillable = [
        'category_name',
        'description',
        'delete_status'
    ];

    public function items()
    {
        return $this->hasMany(InventoryItem::class, 'category_id');
    }
}
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $fillable = [
        'item_name',
        'item_code',
        'category_id',
        'brand',
        'model_number',
        'serial_number',
        'purchase_date',
        'purchase_cost',
        'vendor_name',
        'invoice_number',
        'warranty_expiry',
        'quantity',
        'item_type',
         'item_image',
         'document_file',
         'delete_status',
        'description',
        'remarks',
    ];
protected $casts = [
    'item_type' => 'integer',
];

    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'category_id');
    }

    public function assignments()
    {
        return $this->hasMany(InventoryAssignment::class, 'item_id');
    }

    public function histories()
    {
        return $this->hasMany(InventoryHistory::class, 'item_id');
    }

    public function maintenances()
    {
        return $this->hasMany(InventoryMaintenance::class, 'item_id');
    }
}
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
        'document'
    ];
protected $casts = [
    'status' => 'integer',
    'maintenance_type' => 'integer',
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
--------------------------------
<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\InventoryAssignment;
use App\Models\InventoryItem;
use App\Models\Employee;
use App\Models\Department;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\InventoryHistory;

class InventoryAssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->categorie == 2) {
                return redirect()->route('emphome')->with('error', 'Access denied');
            }
            return $next($request);
        });
    }

    public function checkItem($id)
    {
        $exists = InventoryAssignment::where('item_id', $id)
            ->where('status', 0)
            ->where('condition_status', 1)
            ->exists();

        return response()->json([
            'assigned' => $exists
        ]);
    }

   

    public function getEmployees($dep_id)
    {
        return response()->json(
            Employee::where('cur_department', $dep_id)
                ->where('delete_status', 1)
                ->whereDoesntHave('resignation')
                ->select('emp_id', 'fullname')
                ->get()
        );
    }

    public function index(Request $request)
    {
        $query = InventoryAssignment::with(['item', 'employee', 'department']);

        if ($request->filled('item')) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('item_name', 'like', '%' . $request->item . '%');
            });
        }

        if ($request->filled('employee')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('fullname', 'like', '%' . $request->employee . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->get('per_page', 5);
        $assignments = $query->latest()->paginate($perPage)->withQueryString();

        $departments = Department::where('delete_status', 1)->get();

        $employees = Employee::where('delete_status', 1)
            ->whereDoesntHave('resignation')
            ->select('emp_id', 'fullname', 'employee_id')
            ->get();

        return view('dashboard.hr.inventory.assignment.index', compact('assignments', 'departments', 'employees'));
    }

    public function create()
    {
        $items = InventoryItem::where('delete_status', 1)
            ->whereDoesntHave('assignments', function ($q) {
                $q->where(function ($sub) {
                    $sub->where('condition_status', 0)
                        ->orWhere('status', 0);
                });
            })
            ->get();

        $employees = Employee::where('delete_status', 1)->get();
        $departments = Department::where('delete_status', 1)->get();

        return view('dashboard.hr.inventory.assignment.create', compact('items', 'employees', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'employee_id' => 'required',
            'department_id' => 'required',
            'assigned_date' => 'required|date',
        ]);

        $assignment = InventoryAssignment::create([
            'item_id' => $request->item_id,
            'employee_id' => $request->employee_id,
            'department_id' => $request->department_id,
            'assigned_date' => $request->assigned_date,
            'status' => 0,
            'condition_status' => 1,
            'remarks' => $request->remarks,
        ]);

        InventoryHistory::log([
            'module' => InventoryHistory::MODULE_ASSIGNMENT,
            'action' => InventoryHistory::ACTION_ASSIGNED,
            'item_id' => $assignment->item_id,
            'employee_id' => $assignment->employee_id,
            'assignment_id' => $assignment->id,
            'new_data' => $assignment->toArray(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Item assigned successfully'
        ]);
    }

    public function returnItem(Request $request, $id)
    {
        try {
            $assignment = InventoryAssignment::findOrFail($id);

            if ($assignment->status == 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Item already returned'
                ]);
            }

            $request->validate([
                'return_date' => 'required|date',
            ]);

            $oldData = $assignment->toArray();

            $assignment->status = 1;
            $assignment->return_date = $request->return_date;

            if ($request->filled('return_remarks')) {
                $assignment->remarks = $request->return_remarks;
            }

            $assignment->save();

            InventoryHistory::log([
                'module' => InventoryHistory::MODULE_ASSIGNMENT,
                'action' => InventoryHistory::ACTION_RETURNED,
                'item_id' => $assignment->item_id,
                'employee_id' => $assignment->employee_id,
                'assignment_id' => $assignment->id,
                'old_data' => $oldData,
                'new_data' => $assignment->toArray(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Item returned successfully on ' . date('d-m-Y', strtotime($request->return_date))
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Assignment record not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $assignment = InventoryAssignment::with(['item', 'employee'])->findOrFail($id);

        return response()->json([
            'status' => true,
            'assignment' => $assignment
        ]);
    }
}
<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\InventoryCategory;
use App\Models\InventoryHistory;
use Illuminate\Http\Request;

class InventoryCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->categorie == 2) {
                return redirect()->route('emphome')->with('error', 'Access denied');
            }
            return $next($request);
        });
    }

    public function checkCategory(Request $request)
    {
        $query = InventoryCategory::whereRaw(
            'LOWER(category_name) = ?',
            [strtolower($request->category_name)]
        )->where('delete_status', 1);

        if ($request->id) {
            $query->where('id', '!=', $request->id);
        }

        $exists = $query->exists();

        return response()->json(['exists' => $exists]);
    }

    public function index(Request $request)
    {
        $query = InventoryCategory::where('delete_status', 1);

        if ($request->filled('category_name')) {
            $query->where('category_name', 'like', '%' . $request->category_name . '%');
        }

        $perPage = $request->get('per_page', 5);

        $categories = $query->latest()->paginate($perPage)->withQueryString();

        return view('dashboard.hr.inventory.category.index', compact('categories'));
    }

    public function create()
    {
        return view('dashboard.hr.inventory.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => [
                'required',
                function ($attr, $value, $fail) {
                    $exists = InventoryCategory::whereRaw(
                        'LOWER(category_name) = ?',
                        [strtolower($value)]
                    )
                    ->where('delete_status', 1)
                    ->exists();

                    if ($exists) {
                        $fail('Category already exists');
                    }
                }
            ],
        ]);

        $category = InventoryCategory::create([
            'category_name' => $request->category_name,
            'description' => $request->description,
            'delete_status' => 1
        ]);

        InventoryHistory::log([
            'module' => InventoryHistory::MODULE_CATEGORY,
            'action' => InventoryHistory::ACTION_CREATED,
            'category_id' => $category->id,
            'new_data' => $category->toArray(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Category created successfully'
        ]);
    }

    public function edit($id)
    {
        $category = InventoryCategory::findOrFail($id);
        return view('dashboard.hr.inventory.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = InventoryCategory::findOrFail($id);

        $request->validate([
            'category_name' => [
                'required',
                function ($attr, $value, $fail) use ($id) {
                    $exists = InventoryCategory::whereRaw(
                        'LOWER(category_name) = ?',
                        [strtolower($value)]
                    )
                    ->where('delete_status', 1)
                    ->where('id', '!=', $id)
                    ->exists();

                    if ($exists) {
                        $fail('Category already exists');
                    }
                }
            ],
        ]);

        $oldData = $category->getOriginal();

        $category->update([
            'category_name' => $request->category_name,
            'description' => $request->description,
        ]);

        InventoryHistory::log([
            'module' => InventoryHistory::MODULE_CATEGORY,
            'action' => InventoryHistory::ACTION_UPDATED,
            'category_id' => $category->id,
            'old_data' => $oldData,
            'new_data' => $category->fresh()->toArray(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Category updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $category = InventoryCategory::findOrFail($id);

        $oldData = $category->toArray();

        $category->update(['delete_status' => 0]);

        InventoryHistory::log([
            'module' => InventoryHistory::MODULE_CATEGORY,
            'action' => InventoryHistory::ACTION_DELETED,
            'category_id' => $category->id,
            'old_data' => $oldData,
            'new_data' => ['delete_status' => 0],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
}
<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\InventoryHistory;
use Illuminate\Http\Request;

class InventoryHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->categorie == 2) {
                return redirect()->route('emphome')->with('error', 'Access denied');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        // ✅ Add 'department' to the with() array
        $query = InventoryHistory::with(['item', 'employee', 'assignment', 'maintenance', 'category']);

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('sub_action')) {
            $query->where('sub_action', $request->sub_action);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('action_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('action_date', '<=', $request->date_to);
        }

        $perPage = $request->get('per_page', 1);

        $histories = $query->latest('action_date')
            ->paginate($perPage)
            ->withQueryString();

        $employees = Employee::where('delete_status', 1)
            ->whereDoesntHave('resignation')
            ->select('emp_id', 'fullname', 'employee_id')
            ->get();

        return view('dashboard.hr.inventory.history.index', compact('histories', 'employees'));
    }

    public function show($id)
    {
        // ✅ Add 'department' to the with() array
        $history = InventoryHistory::with([
            'item',
            'employee',
            'assignment',
            'maintenance',
            'category'
        ])->findOrFail($id);

        return response()->json([
            'status' => true,
            'history' => $history
        ]);
    }
}

<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\InventoryCategory;
use App\Models\InventoryHistory;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InventoryItemController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->categorie == 2) {
                return redirect()->route('emphome')->with('error', 'Access denied');
            }
            return $next($request);
        });
    }

    
      

    public function index(Request $request)
    {
        $query = InventoryItem::where('delete_status', 1);

        if ($request->filled('item_name')) {
            $query->where('item_name', 'like', '%' . $request->item_name . '%');
        }

        if ($request->filled('item_code')) {
            $query->where('item_code', 'like', '%' . $request->item_code . '%');
        }

        if ($request->filled('item_type')) {
            $query->where('item_type', $request->item_type);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $perPage = $request->get('per_page', 5);
        $items = $query->latest()->paginate($perPage)->withQueryString();

        $categories = InventoryCategory::where('delete_status', 1)->get();

        return view('dashboard.hr.inventory.item.index', compact('items', 'categories'));
    }

  public function create()
{
    $categories = InventoryCategory::where('delete_status', 1)->get();

    $lastItem = InventoryItem::orderBy('id', 'desc')->first();

    $lastNumber = 0;

    if ($lastItem && $lastItem->item_code) {
        $parts = explode('-', $lastItem->item_code);
        $lastNumber = isset($parts[2]) ? (int)$parts[2] : 0;
    }

    return view('dashboard.hr.inventory.item.create', compact('categories', 'lastNumber'));
}

   public function store(Request $request)
{
    $request->validate([
        'item_name' => 'required',
        'category_id' => 'required',
    ]);

    $item = new InventoryItem();

    $data = $request->except('item_image');
    $data['item_type'] = $request->item_type ?? 0;
    $data['delete_status'] = 1;

    $item->fill($data);

    if ($request->filled('item_image') && is_string($request->item_image)) {
        $this->processBase64Image($request->item_image, $item);
    }

    if ($request->hasFile('document_file')) {
        $file = $request->file('document_file');
        $filename = 'doc_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('inventory_docs'), $filename);
        $item->document_file = $filename;
    }

    $lastItem = InventoryItem::where('item_code', 'like', 'ITM-' . date('Y') . '-%')
        ->orderBy('id', 'desc')
        ->first();

    $lastNumber = 0;

    if ($lastItem && $lastItem->item_code) {
        $parts = explode('-', $lastItem->item_code);
        $lastNumber = isset($parts[2]) ? (int) $parts[2] : 0;
    }

    $nextNumber = $lastNumber + 1;

    $year = date('Y');

    $item->item_code = 'ITM-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

    $item->save();

    InventoryHistory::log([
        'module' => InventoryHistory::MODULE_ITEM,
        'action' => InventoryHistory::ACTION_CREATED,
        'item_id' => $item->id,
        'new_data' => $item->toArray(),
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Inventory item created successfully'
    ]);
}
    public function show($id)
    {
        $item = InventoryItem::findOrFail($id);

        return response()->json([
            'status' => true,
            'item' => $item
        ]);
    }

    public function edit($id)
    {
        $item = InventoryItem::findOrFail($id);
        $categories = InventoryCategory::where('delete_status', 1)->get();

        return view('dashboard.hr.inventory.item.edit', compact('item', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $item = InventoryItem::findOrFail($id);

        $request->validate([
            'item_name' => 'required',
            'item_code' => 'required|unique:inventory_items,item_code,' . $id,
        ]);

        $oldData = $item->getOriginal();

        $data = $request->except('item_image');
        $data['item_type'] = $request->item_type ?? 0;

        $item->fill($data);

        if ($request->filled('item_image') && is_string($request->item_image)) {
            $this->processBase64Image($request->item_image, $item);
        }

        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $filename = 'doc_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('inventory_docs'), $filename);
            $item->document_file = $filename;
        }

        $item->save();

        InventoryHistory::log([
            'module' => InventoryHistory::MODULE_ITEM,
            'action' => InventoryHistory::ACTION_UPDATED,
            'item_id' => $item->id,
            'old_data' => $oldData,
            'new_data' => $item->fresh()->toArray(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Inventory item updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $item = InventoryItem::findOrFail($id);

        $oldData = $item->toArray();

        $item->update(['delete_status' => 0]);

        InventoryHistory::log([
            'module' => InventoryHistory::MODULE_ITEM,
            'action' => InventoryHistory::ACTION_DELETED,
            'item_id' => $item->id,
            'old_data' => $oldData,
            'new_data' => ['delete_status' => 0],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Item deleted successfully'
        ]);
    }

    private function processBase64Image($imageData, $item)
    {
        if (strpos($imageData, 'data:image') === 0) {
            $parts = explode(',', $imageData);
            $mime = explode(';', explode(':', $parts[0])[1])[0];

            $extension = match ($mime) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                default => 'jpg'
            };

            $image = base64_decode($parts[1]);
            $imageName = 'item_' . time() . '_' . uniqid() . '.' . $extension;

            $folder = public_path('inventory_images');

            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            file_put_contents($folder . '/' . $imageName, $image);

            if ($item->item_image && file_exists($folder . '/' . $item->item_image)) {
                unlink($folder . '/' . $item->item_image);
            }

            $item->item_image = $imageName;
        }
    }
}
<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\InventoryAssignment;
use App\Models\InventoryMaintenance;
use App\Models\InventoryItem;
use App\Models\InventoryHistory;
use Illuminate\Http\Request;

class InventoryMaintenanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->categorie == 2) {
                return redirect()->route('emphome')->with('error', 'Access denied');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = InventoryMaintenance::with('item');

        if ($request->filled('item')) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('item_name', 'like', '%' . $request->item . '%');
            });
        }

        if ($request->filled('type')) {
            $query->where('maintenance_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->get('per_page', 5);

        $maintenances = $query->latest()->paginate($perPage)->withQueryString();

        return view('dashboard.hr.inventory.maintenance.index', compact('maintenances'));
    }

    public function create()
    {
        $items = InventoryItem::where('delete_status', 1)
            ->whereDoesntHave('maintenances', function ($q) {
                $q->where('status', 'pending');
            })
            ->get();

        return view('dashboard.hr.inventory.maintenance.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'issue_description' => 'required|min:5|max:500',
            'maintenance_type' => 'required|in:0,1,2',
            'cost' => 'required|numeric|min:0',
            'vendor_name' => 'required|min:2',
            'start_date' => 'required|date',
            'remarks' => 'nullable|max:500',
        ]);

        $data = [
            'item_id' => $request->item_id,
            'employee_id' => $request->employee_id,
            'issue_description' => $request->issue_description,
            'maintenance_type' => $request->maintenance_type,
            'cost' => $request->cost,
            'vendor_name' => $request->vendor_name,
            'start_date' => $request->start_date,
            'status' => 0,
            'remarks' => $request->remarks,
        ];

        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $filename = 'mnt_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('maintenance_docs'), $filename);
            $data['document'] = $filename;
        }

        $maintenance = InventoryMaintenance::create($data);

        if ($request->maintenance_type == 0) {
            $assignments = InventoryAssignment::where('item_id', $request->item_id)
                ->where('status', 0)
                ->get();

            foreach ($assignments as $assignment) {
                $oldAssignment = $assignment->toArray();

                $assignment->update([
                    'status' => 1,
                    'condition_status' => 0,
                    'return_date' => now()
                ]);

                InventoryHistory::log([
                    'module' => InventoryHistory::MODULE_ASSIGNMENT,
                    'action' => InventoryHistory::ACTION_RETURNED,
                    'item_id' => $assignment->item_id,
                    'employee_id' => $assignment->employee_id,
                    'assignment_id' => $assignment->id,
                    'old_data' => $oldAssignment,
                    'new_data' => $assignment->toArray(),
                ]);
            }
        }

        InventoryHistory::log([
            'module' => InventoryHistory::MODULE_MAINTENANCE,
            'action' => InventoryHistory::ACTION_CREATED,
            'sub_action' => match($maintenance->maintenance_type) {
                0 => InventoryHistory::SUB_SCRAP,
                1 => InventoryHistory::SUB_SERVICE,
                2 => InventoryHistory::SUB_UPGRADE,
            },
            'item_id' => $maintenance->item_id,
            'employee_id' => $maintenance->employee_id,
            'maintenance_id' => $maintenance->id,
            'new_data' => $maintenance->toArray(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Maintenance added successfully'
        ]);
    }

    public function edit($id)
    {
        $maintenance = InventoryMaintenance::with('item')->findOrFail($id);
        $items = InventoryItem::where('delete_status', 1)->get();

        return view('dashboard.hr.inventory.maintenance.edit', compact('maintenance', 'items'));
    }

    public function update(Request $request, $id)
    {
        $maintenance = InventoryMaintenance::findOrFail($id);

        $request->validate([
            'maintenance_type' => 'required|in:0,1,2',
            'issue_description' => 'required|min:5|max:500',
            'cost' => 'required|numeric|min:0',
            'vendor_name' => 'required|min:2',
            'start_date' => 'required|date',
            'status' => 'required|in:0,1',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'remarks' => 'nullable|max:500',
        ]);

        $oldData = $maintenance->toArray();

        $data = $request->except('_token', '_method');

        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $filename = 'mnt_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('maintenance_docs'), $filename);

            if ($maintenance->document && file_exists(public_path('maintenance_docs/' . $maintenance->document))) {
                unlink(public_path('maintenance_docs/' . $maintenance->document));
            }

            $data['document'] = $filename;
        }

        if ($request->status == 0) {
            $data['end_date'] = null;
        }

        $maintenance->update($data);

        if ($request->maintenance_type == 0) {
            $assignments = InventoryAssignment::where('item_id', $maintenance->item_id)
                ->where('status', 0)
                ->get();

            foreach ($assignments as $assignment) {
                $oldAssignment = $assignment->toArray();

                $assignment->update([
                    'status' => 1,
                    'condition_status' => 0,
                    'return_date' => now()
                ]);

                InventoryHistory::log([
                    'module' => InventoryHistory::MODULE_ASSIGNMENT,
                    'action' => InventoryHistory::ACTION_RETURNED,
                    'item_id' => $assignment->item_id,
                    'employee_id' => $assignment->employee_id,
                    'assignment_id' => $assignment->id,
                    'old_data' => $oldAssignment,
                    'new_data' => $assignment->toArray(),
                ]);
            }
        }

        InventoryHistory::log([
            'module' => InventoryHistory::MODULE_MAINTENANCE,
            'action' => InventoryHistory::ACTION_UPDATED,
            'sub_action' => match($maintenance->maintenance_type) {
                0 => InventoryHistory::SUB_SCRAP,
                1 => InventoryHistory::SUB_SERVICE,
                2 => InventoryHistory::SUB_UPGRADE,
            },
            'item_id' => $maintenance->item_id,
            'employee_id' => $maintenance->employee_id,
            'maintenance_id' => $maintenance->id,
            'old_data' => $oldData,
            'new_data' => $maintenance->fresh()->toArray(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Maintenance updated successfully'
        ]);
    }

    public function show($id)
    {
        $maintenance = InventoryMaintenance::with(['item', 'employee'])->findOrFail($id);

        return response()->json([
            'status' => true,
            'maintenance' => $maintenance
        ]);
    }

    public function checkItemAssignment($itemId)
    {
        $assignment = InventoryAssignment::where('item_id', $itemId)
            ->where('status', 0)
            ->with(['employee', 'department'])
            ->first();

        if ($assignment) {
            return response()->json([
                'assigned' => true,
                'assignment' => $assignment
            ]);
        }

        return response()->json([
            'assigned' => false
        ]);
    }
}
---------------------------
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name');
            $table->text('description')->nullable();
            $table->boolean('delete_status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_categories');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->string('item_code')->unique();
            $table->foreignId('category_id')->constrained('inventory_categories')->cascadeOnDelete();
            $table->string('brand')->nullable();
            $table->string('model_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_cost', 10, 2)->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('invoice_number')->nullable();
            $table->date('warranty_expiry')->nullable();

            $table->integer('quantity')->default(1);
       $table->tinyInteger('item_type') ->default(0) ->comment('0=new,1=refurbished');
            $table->text('description')->nullable();
            $table->text('remarks')->nullable();
            $table->string('item_image')->nullable();
             $table->string('document_file')->nullable();
             $table->boolean('delete_status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
       Schema::create('inventory_assignments', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('item_id');
    $table->unsignedBigInteger('employee_id');
    $table->unsignedBigInteger('department_id');
    $table->date('assigned_date');
    $table->date('return_date')->nullable();

    $table->tinyInteger('status')
          ->default(0)
          ->comment('0=assigned,1=returned');



    $table->text('remarks')->nullable();
    $table->timestamps();

    $table->tinyInteger('condition_status')
          ->default(1)
          ->comment('0=scrap,1=active');
    $table->foreign('item_id')->references('id')->on('inventory_items')->cascadeOnDelete();
    $table->foreign('employee_id')->references('emp_id')->on('employees')->cascadeOnDelete();
    $table->foreign('department_id')->references('dep_id')->on('departments')->cascadeOnDelete();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_assignments');
    }
};
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_history', function (Blueprint $table) {
            $table->id();

            // 🔗 REFERENCES
            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('assignment_id')->nullable();
            $table->unsignedBigInteger('maintenance_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();

            // 🧠 MODULE + ACTION
            $table->string('module');
            // item / assignment / maintenance / category

            $table->string('action');
            // created, updated, assigned, returned

            $table->string('sub_action')->nullable();
            // service, upgrade, scrap (for maintenance)

            // 📦 FULL SNAPSHOT DATA
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();

            // 📅 TIMESTAMP
            $table->timestamp('action_date')->useCurrent();

            $table->timestamps();

            // 🔗 FOREIGN KEYS (optional but recommended)
            $table->foreign('item_id')->references('id')->on('inventory_items')->nullOnDelete();
            $table->foreign('employee_id')->references('emp_id')->on('employees')->nullOnDelete();
            $table->foreign('assignment_id')->references('id')->on('inventory_assignments')->nullOnDelete();
            $table->foreign('maintenance_id')->references('id')->on('inventory_maintenance')->nullOnDelete();
            $table->foreign('category_id')->references('id')->on('inventory_categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_history');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_maintenance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->unsignedBigInteger('employee_id')->nullable(); // ✅ ADD THIS
            $table->text('issue_description');
       $table->tinyInteger('maintenance_type')
      ->nullable()
      ->comment('0=scrap,1=service,2=upgrade');

$table->decimal('cost', 10, 2)->nullable();

$table->string('vendor_name')->nullable();

$table->date('start_date')->nullable();
$table->date('end_date')->nullable();

$table->tinyInteger('status')
      ->default(0)
      ->comment('0=pending,1=complete');
            $table->text('remarks')->nullable();
             $table->string('document')->nullable();
            $table->timestamps();

            // ✅ ADD FOREIGN KEY
            $table->foreign('employee_id')
                  ->references('emp_id')
                  ->on('employees')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_maintenance');
    }
};
----------------------
```
└── 📁inventory
    └── 📁assignment
        ├── create.blade.php
        ├── index.blade.php
    └── 📁category
        ├── create.blade.php
        ├── edit.blade.php
        ├── index.blade.php
    └── 📁history
        ├── index.blade.php
    └── 📁item
        ├── create.blade.php
        ├── edit.blade.php
        ├── index.blade.php
    └── 📁maintenance
        ├── create.blade.php
        ├── edit.blade.php
        ├── index.blade.php
    └── 📁reports
        ├── export-pdf.blade.php
        └── index.blade.php
`
