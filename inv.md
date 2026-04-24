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
```

<?php

use App\Http\Controllers\Dashboard\HR\ReportController;
use Illuminate\Support\Facades\Route;

// Report Management Routes
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/export-items', [ReportController::class, 'exportItems'])->name('export-items');
    Route::get('/export-assignments', [ReportController::class, 'exportAssignments'])->name('export-assignments');
    Route::get('/export-maintenances', [ReportController::class, 'exportMaintenances'])->name('export-maintenances');
    Route::get('/export-categories', [ReportController::class, 'exportCategories'])->name('export-categories');
});

<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\InventoryAssignment;
use App\Models\InventoryMaintenance;
use App\Models\InventoryCategory;
use App\Models\Department;
use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
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

    public function index()
    {
        // Get data for filters
        $categories = InventoryCategory::where('delete_status', 1)->get();
        $departments = Department::where('delete_status', 1)->get();
        $employees = Employee::where('delete_status', 1)
            ->whereDoesntHave('resignation')
            ->select('emp_id', 'fullname', 'employee_id')
            ->get();
        $items = InventoryItem::where('delete_status', 1)
            ->select('id', 'item_name', 'item_code')
            ->get();

        return view('dashboard.hr.inventory.reports.index', compact('categories', 'departments', 'employees', 'items'));
    }

    /**
     * Export Items Report
     */
    public function exportItems(Request $request)
    {
        $query = InventoryItem::where('delete_status', 1)->with('category');

        // Filter by category
        if ($request->filled('category_ids')) {
            $categoryIds = $request->input('category_ids');
            if (is_array($categoryIds) && !empty($categoryIds)) {
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Filter by item type
        if ($request->filled('item_type')) {
            $query->where('item_type', $request->item_type);
        }

        // Filter by date range
        if ($request->filled('purchase_date_from')) {
            $query->whereDate('purchase_date', '>=', $request->purchase_date_from);
        }
        if ($request->filled('purchase_date_to')) {
            $query->whereDate('purchase_date', '<=', $request->purchase_date_to);
        }

        // Filter by cost range
        if ($request->filled('cost_min')) {
            $query->where('purchase_cost', '>=', $request->cost_min);
        }
        if ($request->filled('cost_max')) {
            $query->where('purchase_cost', '<=', $request->cost_max);
        }

        // Search by item name/code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('item_code', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'item_name');
        $sortOrder = $request->input('sort_order', 'asc');
        $allowedSortFields = ['item_name', 'item_code', 'purchase_date', 'purchase_cost', 'category_id'];

        if (in_array($sortBy, $allowedSortFields)) {
            if ($sortBy === 'category_id') {
                $query->join('inventory_categories', 'inventory_items.category_id', '=', 'inventory_categories.id')
                    ->orderBy('inventory_categories.category_name', $sortOrder)
                    ->select('inventory_items.*');
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->orderBy('item_name', 'asc');
        }

        $items = $query->get();

        // Calculate summaries
        $totalItems = $items->count();
        $totalCost = $items->sum('purchase_cost');
        $totalQuantity = $items->sum('quantity');

        $categoriesSummary = [];
        foreach ($items as $item) {
            $categoryName = $item->category->category_name ?? 'Uncategorized';
            if (!isset($categoriesSummary[$categoryName])) {
                $categoriesSummary[$categoryName] = ['count' => 0, 'total_cost' => 0, 'total_quantity' => 0];
            }
            $categoriesSummary[$categoryName]['count']++;
            $categoriesSummary[$categoryName]['total_cost'] += $item->purchase_cost;
            $categoriesSummary[$categoryName]['total_quantity'] += $item->quantity;
        }

        $itemTypeSummary = [
            'new' => ['count' => 0, 'total_cost' => 0, 'total_quantity' => 0],
            'refurbished' => ['count' => 0, 'total_cost' => 0, 'total_quantity' => 0],
        ];
        foreach ($items as $item) {
            $type = $item->item_type == 1 ? 'refurbished' : 'new';
            $itemTypeSummary[$type]['count']++;
            $itemTypeSummary[$type]['total_cost'] += $item->purchase_cost;
            $itemTypeSummary[$type]['total_quantity'] += $item->quantity;
        }

        $companyLogo = $this->getCompanyLogo();
        $companyName = $this->getCompanyName();
        $filterDescription = $this->buildFilterDescription($request);

        $data = [
            'title' => 'Inventory Items Report',
            'filter_description' => $filterDescription,
            'report_generated_date' => now()->format('d-m-Y H:i:s'),
            'items' => $items,
            'total_items' => $totalItems,
            'total_cost' => $totalCost,
            'total_quantity' => $totalQuantity,
            'categories_summary' => $categoriesSummary,
            'item_type_summary' => $itemTypeSummary,
            'company_logo' => $companyLogo,
            'company_name' => $companyName,
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
            'report_type' => 'items',
        ];

        $pdf = Pdf::loadView('dashboard.hr.inventory.reports.export-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['defaultFont' => 'DejaVu Sans', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download('items_report_' . now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Export Assignments Report
     */
    public function exportAssignments(Request $request)
    {
        $query = InventoryAssignment::with(['item', 'employee', 'department']);

        // Filter by status
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by condition status
        if ($request->filled('condition_status') && $request->condition_status !== '') {
            $query->where('condition_status', $request->condition_status);
        }

        // Filter by employee
        if ($request->filled('employee_ids')) {
            $employeeIds = $request->input('employee_ids');
            if (is_array($employeeIds) && !empty($employeeIds)) {
                $query->whereIn('employee_id', $employeeIds);
            }
        }

        // Filter by department
        if ($request->filled('department_ids')) {
            $departmentIds = $request->input('department_ids');
            if (is_array($departmentIds) && !empty($departmentIds)) {
                $query->whereIn('department_id', $departmentIds);
            }
        }

        // Filter by item
        if ($request->filled('item_ids')) {
            $itemIds = $request->input('item_ids');
            if (is_array($itemIds) && !empty($itemIds)) {
                $query->whereIn('item_id', $itemIds);
            }
        }

        // Filter by date range
        if ($request->filled('assigned_date_from')) {
            $query->whereDate('assigned_date', '>=', $request->assigned_date_from);
        }
        if ($request->filled('assigned_date_to')) {
            $query->whereDate('assigned_date', '<=', $request->assigned_date_to);
        }

        // Filter by return date range
        if ($request->filled('return_date_from')) {
            $query->whereDate('return_date', '>=', $request->return_date_from);
        }
        if ($request->filled('return_date_to')) {
            $query->whereDate('return_date', '<=', $request->return_date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('item', function ($sub) use ($search) {
                    $sub->where('item_name', 'like', "%{$search}%");
                })->orWhereHas('employee', function ($sub) use ($search) {
                    $sub->where('fullname', 'like', "%{$search}%");
                });
            });
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'assigned_date');
        $sortOrder = $request->input('sort_order', 'desc');
        $allowedSortFields = ['assigned_date', 'return_date', 'status'];

        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('assigned_date', 'desc');
        }

        $assignments = $query->get();

        // Calculate summaries
        $totalAssignments = $assignments->count();
        $assignedCount = $assignments->where('status', 0)->count();
        $returnedCount = $assignments->where('status', 1)->count();
        $activeCount = $assignments->where('condition_status', 1)->count();
        $scrapCount = $assignments->where('condition_status', 0)->count();

        $departmentSummary = [];
        foreach ($assignments as $assignment) {
            $deptName = $assignment->department->dep_name ?? 'Unknown';
            if (!isset($departmentSummary[$deptName])) {
                $departmentSummary[$deptName] = ['total' => 0, 'assigned' => 0, 'returned' => 0];
            }
            $departmentSummary[$deptName]['total']++;
            if ($assignment->status == 0) {
                $departmentSummary[$deptName]['assigned']++;
            } else {
                $departmentSummary[$deptName]['returned']++;
            }
        }

        $employeeSummary = [];
        foreach ($assignments as $assignment) {
            $empName = $assignment->employee->fullname ?? 'Unknown';
            if (!isset($employeeSummary[$empName])) {
                $employeeSummary[$empName] = ['total' => 0, 'assigned' => 0, 'returned' => 0];
            }
            $employeeSummary[$empName]['total']++;
            if ($assignment->status == 0) {
                $employeeSummary[$empName]['assigned']++;
            } else {
                $employeeSummary[$empName]['returned']++;
            }
        }

        $data = [
            'title' => 'Inventory Assignments Report',
            'filter_description' => $this->buildAssignmentFilterDescription($request),
            'report_generated_date' => now()->format('d-m-Y H:i:s'),
            'assignments' => $assignments,
            'total_assignments' => $totalAssignments,
            'assigned_count' => $assignedCount,
            'returned_count' => $returnedCount,
            'active_count' => $activeCount,
            'scrap_count' => $scrapCount,
            'department_summary' => $departmentSummary,
            'employee_summary' => $employeeSummary,
            'company_logo' => $this->getCompanyLogo(),
            'company_name' => $this->getCompanyName(),
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
            'report_type' => 'assignments',
        ];

        $pdf = Pdf::loadView('dashboard.hr.inventory.reports.export-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['defaultFont' => 'DejaVu Sans', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download('assignments_report_' . now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Export Maintenance Report
     */
    public function exportMaintenances(Request $request)
    {
        $query = InventoryMaintenance::with(['item', 'employee']);

        // Filter by maintenance type
        if ($request->filled('maintenance_type') && $request->maintenance_type !== '') {
            $query->where('maintenance_type', $request->maintenance_type);
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by item
        if ($request->filled('item_ids')) {
            $itemIds = $request->input('item_ids');
            if (is_array($itemIds) && !empty($itemIds)) {
                $query->whereIn('item_id', $itemIds);
            }
        }

        // Filter by date range
        if ($request->filled('start_date_from')) {
            $query->whereDate('start_date', '>=', $request->start_date_from);
        }
        if ($request->filled('start_date_to')) {
            $query->whereDate('start_date', '<=', $request->start_date_to);
        }

        // Filter by cost range
        if ($request->filled('cost_min')) {
            $query->where('cost', '>=', $request->cost_min);
        }
        if ($request->filled('cost_max')) {
            $query->where('cost', '<=', $request->cost_max);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('issue_description', 'like', "%{$search}%")
                  ->orWhere('vendor_name', 'like', "%{$search}%")
                  ->orWhereHas('item', function ($sub) use ($search) {
                      $sub->where('item_name', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'start_date');
        $sortOrder = $request->input('sort_order', 'desc');
        $allowedSortFields = ['start_date', 'end_date', 'cost', 'status'];

        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('start_date', 'desc');
        }

        $maintenances = $query->get();

        // Calculate summaries
        $totalMaintenances = $maintenances->count();
        $totalCost = $maintenances->sum('cost');

        $typeSummary = [
            'scrap' => ['count' => 0, 'total_cost' => 0],
            'service' => ['count' => 0, 'total_cost' => 0],
            'upgrade' => ['count' => 0, 'total_cost' => 0],
        ];

        $statusSummary = [
            'pending' => 0,
            'complete' => 0,
        ];

        foreach ($maintenances as $maintenance) {
            $type = match($maintenance->maintenance_type) {
                0 => 'scrap',
                1 => 'service',
                2 => 'upgrade',
                default => 'service'
            };
            $typeSummary[$type]['count']++;
            $typeSummary[$type]['total_cost'] += $maintenance->cost;

            if ($maintenance->status == 0) {
                $statusSummary['pending']++;
            } else {
                $statusSummary['complete']++;
            }
        }

        $data = [
            'title' => 'Inventory Maintenance Report',
            'filter_description' => $this->buildMaintenanceFilterDescription($request),
            'report_generated_date' => now()->format('d-m-Y H:i:s'),
            'maintenances' => $maintenances,
            'total_maintenances' => $totalMaintenances,
            'total_cost' => $totalCost,
            'type_summary' => $typeSummary,
            'status_summary' => $statusSummary,
            'company_logo' => $this->getCompanyLogo(),
            'company_name' => $this->getCompanyName(),
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
            'report_type' => 'maintenances',
        ];

        $pdf = Pdf::loadView('dashboard.hr.inventory.reports.export-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['defaultFont' => 'DejaVu Sans', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download('maintenance_report_' . now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Export Categories Report
     */
    public function exportCategories(Request $request)
    {
        $query = InventoryCategory::where('delete_status', 1)->with('items');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('category_name', 'like', "%{$search}%");
        }

        // Filter by has items
        if ($request->filled('has_items')) {
            if ($request->has_items == 'yes') {
                $query->has('items');
            } elseif ($request->has_items == 'no') {
                $query->doesntHave('items');
            }
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'category_name');
        $sortOrder = $request->input('sort_order', 'asc');

        if ($sortBy == 'items_count') {
            $query->withCount('items')->orderBy('items_count', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $categories = $query->get();

        // Calculate summaries
        $totalCategories = $categories->count();
        $totalItems = $categories->sum(function ($cat) {
            return $cat->items->count();
        });
        $totalCost = $categories->sum(function ($cat) {
            return $cat->items->sum('purchase_cost');
        });

        $data = [
            'title' => 'Inventory Categories Report',
            'filter_description' => $this->buildCategoryFilterDescription($request),
            'report_generated_date' => now()->format('d-m-Y H:i:s'),
            'categories' => $categories,
            'total_categories' => $totalCategories,
            'total_items' => $totalItems,
            'total_cost' => $totalCost,
            'company_logo' => $this->getCompanyLogo(),
            'company_name' => $this->getCompanyName(),
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
            'report_type' => 'categories',
        ];

        $pdf = Pdf::loadView('dashboard.hr.inventory.reports.export-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['defaultFont' => 'DejaVu Sans', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download('categories_report_' . now()->format('Ymd_His') . '.pdf');
    }

    // Helper methods
    private function getCompanyLogo()
    {
        $settings = \App\Models\Setting::first();
        if ($settings && $settings->weblogo) {
            $logoPath = public_path('weblogo/' . $settings->weblogo);
            if (file_exists($logoPath)) {
                $logoData = file_get_contents($logoPath);
                $mimeType = mime_content_type($logoPath);
                return 'data:' . $mimeType . ';base64,' . base64_encode($logoData);
            }
        }
        return null;
    }

    private function getCompanyName()
    {
        $settings = \App\Models\Setting::first();
        return $settings && $settings->webname ? $settings->webname : 'RAIYAAN INFOTECH';
    }

    private function buildFilterDescription($request)
    {
        $parts = [];

        if ($request->filled('category_ids')) {
            $categories = InventoryCategory::whereIn('id', $request->category_ids)->pluck('category_name')->toArray();
            $parts[] = 'Categories: ' . implode(', ', $categories);
        }

        if ($request->filled('item_type')) {
            $parts[] = 'Item Type: ' . ($request->item_type == 1 ? 'Refurbished' : 'New');
        }

        if ($request->filled('purchase_date_from') || $request->filled('purchase_date_to')) {
            $from = $request->purchase_date_from ?? 'Start';
            $to = $request->purchase_date_to ?? 'End';
            $parts[] = "Purchase Date: {$from} to {$to}";
        }

        if ($request->filled('search')) {
            $parts[] = "Search: {$request->search}";
        }

        return empty($parts) ? 'All Items' : implode(' | ', $parts);
    }

    private function buildAssignmentFilterDescription($request)
    {
        $parts = [];

        if ($request->filled('status') && $request->status !== '') {
            $parts[] = 'Status: ' . ($request->status == 0 ? 'Assigned' : 'Returned');
        }

        if ($request->filled('condition_status') && $request->condition_status !== '') {
            $parts[] = 'Condition: ' . ($request->condition_status == 1 ? 'Active' : 'Scrap');
        }

        if ($request->filled('employee_ids')) {
            $employees = Employee::whereIn('emp_id', $request->employee_ids)->pluck('fullname')->toArray();
            $parts[] = 'Employees: ' . implode(', ', $employees);
        }

        if ($request->filled('department_ids')) {
            $departments = Department::whereIn('dep_id', $request->department_ids)->pluck('dep_name')->toArray();
            $parts[] = 'Departments: ' . implode(', ', $departments);
        }

        return empty($parts) ? 'All Assignments' : implode(' | ', $parts);
    }

    private function buildMaintenanceFilterDescription($request)
    {
        $parts = [];

        if ($request->filled('maintenance_type') && $request->maintenance_type !== '') {
            $types = ['Scrap', 'Service', 'Upgrade'];
            $parts[] = 'Type: ' . ($types[$request->maintenance_type] ?? 'Unknown');
        }

        if ($request->filled('status') && $request->status !== '') {
            $parts[] = 'Status: ' . ($request->status == 0 ? 'Pending' : 'Complete');
        }

        if ($request->filled('start_date_from') || $request->filled('start_date_to')) {
            $from = $request->start_date_from ?? 'Start';
            $to = $request->start_date_to ?? 'End';
            $parts[] = "Date Range: {$from} to {$to}";
        }

        return empty($parts) ? 'All Maintenance Records' : implode(' | ', $parts);
    }

    private function buildCategoryFilterDescription($request)
    {
        $parts = [];

        if ($request->filled('search')) {
            $parts[] = "Search: {$request->search}";
        }

        if ($request->filled('has_items')) {
            $parts[] = $request->has_items == 'yes' ? 'With Items Only' : 'Empty Categories Only';
        }

        return empty($parts) ? 'All Categories' : implode(' | ', $parts);
    }
}


<x-layout>
    @section('title', 'Report Management')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />

    <style>
        .report-tab {
            transition: all 0.3s ease;
        }
        .report-tab .nav-link {
            color: #6c757d;
            border: none;
            padding: 12px 24px;
            font-weight: 500;
            border-radius: 10px;
            margin: 0 4px;
        }
        .report-tab .nav-link.active {
            background: linear-gradient(135deg, #2c7da0 0%, #1f5e7a 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(44,125,160,0.3);
        }
        .report-tab .nav-link:hover:not(.active) {
            background: #f0f4f8;
            color: #2c7da0;
        }
        .filter-section {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .filter-title {
            font-size: 14px;
            font-weight: 600;
            color: #2c7da0;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .filter-title i {
            font-size: 18px;
        }
        .btn-export {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(40,167,69,0.3);
        }
        .card-header-custom {
            background: linear-gradient(135deg, #2c7da0 0%, #1f5e7a 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 15px 20px;
        }
        .select2-container .select2-selection--multiple {
            min-height: 42px;
            border-radius: 8px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #2c7da0;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 2px 8px;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 10px 12px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #2c7da0;
            box-shadow: 0 0 0 0.2rem rgba(44,125,160,0.25);
        }
        .range-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .range-group .form-control {
            flex: 1;
        }
    </style>

    <div class="container-fluid p-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">📊 Report Management</h3>
                <p class="text-muted mb-0">Generate and export custom reports for inventory management</p>
            </div>
            <div class="text-muted">
                <i class="bi bi-calendar3 me-1"></i> {{ now()->format('d M Y, h:i A') }}
            </div>
        </div>

        <!-- Report Type Tabs -->
        <ul class="nav nav-pills report-tab mb-4" id="reportTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#itemsTab" type="button" role="tab">
                    <i class="bi bi-box-seam me-2"></i>Items Report
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#assignmentsTab" type="button" role="tab">
                    <i class="bi bi-arrow-left-right me-2"></i>Assignments Report
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#maintenanceTab" type="button" role="tab">
                    <i class="bi bi-tools me-2"></i>Maintenance Report
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#categoriesTab" type="button" role="tab">
                    <i class="bi bi-tags me-2"></i>Categories Report
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">

            <!-- ==================== ITEMS REPORT TAB ==================== -->
            <div class="tab-pane fade show active" id="itemsTab" role="tabpanel">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header-custom">
                        <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Inventory Items Report</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="itemsReportForm" method="GET" action="{{ route('reports.export-items') }}" target="_blank">

                            <!-- Filter Section -->
                            <div class="filter-section">
                                <div class="filter-title">
                                    <i class="bi bi-funnel"></i>
                                    <span>Filter Options</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Categories</label>
                                        <select name="category_ids[]" class="form-select items-category-select" multiple>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Select multiple categories (optional)</small>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Item Type</label>
                                        <select name="item_type" class="form-select">
                                            <option value="">All Types</option>
                                            <option value="0">New</option>
                                            <option value="1">Refurbished</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label fw-semibold">Search</label>
                                        <input type="text" name="search" class="form-control" placeholder="Item name, code, or serial number...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Purchase Date Range</label>
                                        <div class="range-group">
                                            <input type="date" name="purchase_date_from" class="form-control" placeholder="From">
                                            <span>to</span>
                                            <input type="date" name="purchase_date_to" class="form-control" placeholder="To">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Cost Range (₹)</label>
                                        <div class="range-group">
                                            <input type="number" name="cost_min" class="form-control" placeholder="Min Cost" step="0.01">
                                            <span>to</span>
                                            <input type="number" name="cost_max" class="form-control" placeholder="Max Cost" step="0.01">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sort Section -->
                            <div class="filter-section">
                                <div class="filter-title">
                                    <i class="bi bi-sort-down"></i>
                                    <span>Sort Options</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Sort By</label>
                                        <select name="sort_by" class="form-select">
                                            <option value="item_name">Item Name</option>
                                            <option value="item_code">Item Code</option>
                                            <option value="purchase_date">Purchase Date</option>
                                            <option value="purchase_cost">Cost</option>
                                            <option value="category_id">Category</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Sort Order</label>
                                        <select name="sort_order" class="form-select">
                                            <option value="asc">Ascending (A-Z / Oldest First)</option>
                                            <option value="desc">Descending (Z-A / Newest First)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Info -->
                            <div class="alert alert-info bg-light border-0 rounded-3 d-flex align-items-center gap-3">
                                <i class="bi bi-info-circle-fill fs-4 text-info"></i>
                                <div>
                                    <strong>Report Preview:</strong> This report will include item details, category breakdown, cost analysis, and quantity summary.
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-export btn-lg">
                                    <i class="bi bi-file-pdf me-2"></i>Generate Items PDF Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ==================== ASSIGNMENTS REPORT TAB ==================== -->
            <div class="tab-pane fade" id="assignmentsTab" role="tabpanel">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header-custom">
                        <h5 class="mb-0"><i class="bi bi-arrow-left-right me-2"></i>Inventory Assignments Report</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="assignmentsReportForm" method="GET" action="{{ route('reports.export-assignments') }}" target="_blank">

                            <!-- Filter Section -->
                            <div class="filter-section">
                                <div class="filter-title">
                                    <i class="bi bi-funnel"></i>
                                    <span>Filter Options</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Assignment Status</label>
                                        <select name="status" class="form-select">
                                            <option value="">All</option>
                                            <option value="0">Assigned</option>
                                            <option value="1">Returned</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Condition Status</label>
                                        <select name="condition_status" class="form-select">
                                            <option value="">All</option>
                                            <option value="1">Active</option>
                                            <option value="0">Scrap</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Search</label>
                                        <input type="text" name="search" class="form-control" placeholder="Item name or employee name...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Employees</label>
                                        <select name="employee_ids[]" class="form-select assignments-employee-select" multiple>
                                            @foreach($employees as $emp)
                                                <option value="{{ $emp->emp_id }}">{{ $emp->fullname }} ({{ $emp->employee_id }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Departments</label>
                                        <select name="department_ids[]" class="form-select assignments-dept-select" multiple>
                                            @foreach($departments as $dept)
                                                <option value="{{ $dept->dep_id }}">{{ $dept->dep_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Items</label>
                                        <select name="item_ids[]" class="form-select assignments-item-select" multiple>
                                            @foreach($items as $item)
                                                <option value="{{ $item->id }}">{{ $item->item_name }} ({{ $item->item_code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Assigned Date Range</label>
                                        <div class="range-group">
                                            <input type="date" name="assigned_date_from" class="form-control" placeholder="From">
                                            <span>to</span>
                                            <input type="date" name="assigned_date_to" class="form-control" placeholder="To">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Return Date Range</label>
                                        <div class="range-group">
                                            <input type="date" name="return_date_from" class="form-control" placeholder="From">
                                            <span>to</span>
                                            <input type="date" name="return_date_to" class="form-control" placeholder="To">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sort Section -->
                            <div class="filter-section">
                                <div class="filter-title">
                                    <i class="bi bi-sort-down"></i>
                                    <span>Sort Options</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Sort By</label>
                                        <select name="sort_by" class="form-select">
                                            <option value="assigned_date">Assigned Date</option>
                                            <option value="return_date">Return Date</option>
                                            <option value="status">Status</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Sort Order</label>
                                        <select name="sort_order" class="form-select">
                                            <option value="desc">Newest First</option>
                                            <option value="asc">Oldest First</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info bg-light border-0 rounded-3 d-flex align-items-center gap-3">
                                <i class="bi bi-info-circle-fill fs-4 text-info"></i>
                                <div>
                                    <strong>Report Preview:</strong> This report will include assignment details, employee information, department breakdown, and status summary.
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-export btn-lg">
                                    <i class="bi bi-file-pdf me-2"></i>Generate Assignments PDF Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ==================== MAINTENANCE REPORT TAB ==================== -->
            <div class="tab-pane fade" id="maintenanceTab" role="tabpanel">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header-custom">
                        <h5 class="mb-0"><i class="bi bi-tools me-2"></i>Maintenance Report</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="maintenanceReportForm" method="GET" action="{{ route('reports.export-maintenances') }}" target="_blank">

                            <!-- Filter Section -->
                            <div class="filter-section">
                                <div class="filter-title">
                                    <i class="bi bi-funnel"></i>
                                    <span>Filter Options</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Maintenance Type</label>
                                        <select name="maintenance_type" class="form-select">
                                            <option value="">All Types</option>
                                            <option value="1">Service</option>
                                            <option value="2">Upgrade</option>
                                            <option value="0">Scrap</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="">All</option>
                                            <option value="0">Pending</option>
                                            <option value="1">Completed</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Search</label>
                                        <input type="text" name="search" class="form-control" placeholder="Item name, issue description, vendor...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Items</label>
                                        <select name="item_ids[]" class="form-select maintenance-item-select" multiple>
                                            @foreach($items as $item)
                                                <option value="{{ $item->id }}">{{ $item->item_name }} ({{ $item->item_code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Start Date Range</label>
                                        <div class="range-group">
                                            <input type="date" name="start_date_from" class="form-control" placeholder="From">
                                            <span>to</span>
                                            <input type="date" name="start_date_to" class="form-control" placeholder="To">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Cost Range (₹)</label>
                                        <div class="range-group">
                                            <input type="number" name="cost_min" class="form-control" placeholder="Min Cost" step="0.01">
                                            <span>to</span>
                                            <input type="number" name="cost_max" class="form-control" placeholder="Max Cost" step="0.01">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sort Section -->
                            <div class="filter-section">
                                <div class="filter-title">
                                    <i class="bi bi-sort-down"></i>
                                    <span>Sort Options</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Sort By</label>
                                        <select name="sort_by" class="form-select">
                                            <option value="start_date">Start Date</option>
                                            <option value="end_date">End Date</option>
                                            <option value="cost">Cost</option>
                                            <option value="status">Status</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Sort Order</label>
                                        <select name="sort_order" class="form-select">
                                            <option value="desc">Newest First</option>
                                            <option value="asc">Oldest First</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info bg-light border-0 rounded-3 d-flex align-items-center gap-3">
                                <i class="bi bi-info-circle-fill fs-4 text-info"></i>
                                <div>
                                    <strong>Report Preview:</strong> This report will include maintenance records, cost analysis, type breakdown, and status summary.
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-export btn-lg">
                                    <i class="bi bi-file-pdf me-2"></i>Generate Maintenance PDF Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ==================== CATEGORIES REPORT TAB ==================== -->
            <div class="tab-pane fade" id="categoriesTab" role="tabpanel">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header-custom">
                        <h5 class="mb-0"><i class="bi bi-tags me-2"></i>Categories Report</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="categoriesReportForm" method="GET" action="{{ route('reports.export-categories') }}" target="_blank">

                            <!-- Filter Section -->
                            <div class="filter-section">
                                <div class="filter-title">
                                    <i class="bi bi-funnel"></i>
                                    <span>Filter Options</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Search Category</label>
                                        <input type="text" name="search" class="form-control" placeholder="Category name...">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Items Filter</label>
                                        <select name="has_items" class="form-select">
                                            <option value="">All Categories</option>
                                            <option value="yes">With Items Only</option>
                                            <option value="no">Empty Categories Only</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Sort Section -->
                            <div class="filter-section">
                                <div class="filter-title">
                                    <i class="bi bi-sort-down"></i>
                                    <span>Sort Options</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Sort By</label>
                                        <select name="sort_by" class="form-select">
                                            <option value="category_name">Category Name</option>
                                            <option value="items_count">Number of Items</option>
                                            <option value="created_at">Date Created</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Sort Order</label>
                                        <select name="sort_order" class="form-select">
                                            <option value="asc">Ascending (A-Z)</option>
                                            <option value="desc">Descending (Z-A)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info bg-light border-0 rounded-3 d-flex align-items-center gap-3">
                                <i class="bi bi-info-circle-fill fs-4 text-info"></i>
                                <div>
                                    <strong>Report Preview:</strong> This report will include category details, item count per category, and total value summary.
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-export btn-lg">
                                    <i class="bi bi-file-pdf me-2"></i>Generate Categories PDF Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for multiple selects
            $('.items-category-select').select2({
                placeholder: 'Select categories',
                allowClear: true,
                width: '100%'
            });

            $('.assignments-employee-select').select2({
                placeholder: 'Select employees',
                allowClear: true,
                width: '100%'
            });

            $('.assignments-dept-select').select2({
                placeholder: 'Select departments',
                allowClear: true,
                width: '100%'
            });

            $('.assignments-item-select').select2({
                placeholder: 'Select items',
                allowClear: true,
                width: '100%'
            });

            $('.maintenance-item-select').select2({
                placeholder: 'Select items',
                allowClear: true,
                width: '100%'
            });
        });
    </script>

</x-layout>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Segoe UI', Arial, sans-serif;
            font-size: 9px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2c7da0;
        }

        .company-logo {
            max-height: 50px;
            margin-bottom: 8px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #2c7da0;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 14px;
            font-weight: bold;
            margin: 8px 0 5px;
        }

        .report-info {
            font-size: 8px;
            color: #666;
            margin-bottom: 3px;
        }

        .summary-container {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .summary-card {
            flex: 1;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 8px;
            text-align: center;
            border: 1px solid #e0e0e0;
        }

        .summary-card h4 {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }

        .summary-card .value {
            font-size: 16px;
            font-weight: bold;
            color: #2c7da0;
        }

        .sub-summary {
            margin-bottom: 15px;
        }

        .sub-summary h4 {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #2c7da0;
            border-left: 3px solid #2c7da0;
            padding-left: 8px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #ddd;
            padding: 5px 8px;
            text-align: left;
        }

        .summary-table th {
            background-color: #e9ecef;
            font-weight: bold;
            font-size: 8px;
        }

        .summary-table td {
            font-size: 8px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th {
            background-color: #2c7da0;
            color: white;
            padding: 6px 5px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
        }

        .data-table td {
            border: 1px solid #ddd;
            padding: 5px;
            font-size: 7px;
        }

        .data-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 7px;
            font-weight: bold;
        }

        .badge-assigned { background-color: #ffc107; color: #333; }
        .badge-returned { background-color: #28a745; color: white; }
        .badge-active { background-color: #17a2b8; color: white; }
        .badge-scrap { background-color: #dc3545; color: white; }
        .badge-pending { background-color: #fd7e14; color: white; }
        .badge-complete { background-color: #28a745; color: white; }
        .badge-new { background-color: #28a745; color: white; }
        .badge-refurbished { background-color: #17a2b8; color: white; }
        .badge-service { background-color: #3498db; color: white; }
        .badge-upgrade { background-color: #9b59b6; color: white; }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            text-align: center;
            font-size: 7px;
            color: #999;
            border-top: 1px solid #eee;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        @if($company_logo)
            <img src="{{ $company_logo }}" class="company-logo" alt="Logo">
        @endif
        <div class="company-name">{{ $company_name }}</div>
        <div class="report-title">{{ $title }}</div>
        <div class="report-info">Filter: {{ $filter_description }}</div>
        <div class="report-info">Sort By: {{ ucfirst(str_replace('_', ' ', $sort_by)) }} ({{ $sort_order == 'asc' ? 'Ascending' : 'Descending' }})</div>
        <div class="report-info">Generated On: {{ $report_generated_date }}</div>
    </div>

    @if($report_type == 'items')
        <!-- ==================== ITEMS REPORT ==================== -->

        <!-- Summary Cards -->
        <div class="summary-container">
            <div class="summary-card">
                <h4>Total Items</h4>
                <div class="value">{{ $total_items }}</div>
            </div>
            <div class="summary-card">
                <h4>Total Quantity</h4>
                <div class="value">{{ number_format($total_quantity) }}</div>
            </div>
            <div class="summary-card">
                <h4>Total Cost</h4>
                <div class="value">₹ {{ number_format($total_cost, 2) }}</div>
            </div>
            <div class="summary-card">
                <h4>Avg Cost</h4>
                <div class="value">₹ {{ $total_items > 0 ? number_format($total_cost / $total_items, 2) : '0' }}</div>
            </div>
        </div>

        <!-- Category Summary -->
        @if(count($categories_summary) > 0)
        <div class="sub-summary">
            <h4>Summary by Category</h4>
            <table class="summary-table">
                <thead>
                    <tr><th>Category</th><th class="text-right">Items</th><th class="text-right">Quantity</th><th class="text-right">Total Cost (₹)</th></tr>
                </thead>
                <tbody>
                    @foreach($categories_summary as $catName => $sum)
                    <tr><td>{{ $catName }}</td><td class="text-right">{{ $sum['count'] }}</td><td class="text-right">{{ number_format($sum['total_quantity']) }}</td><td class="text-right">₹ {{ number_format($sum['total_cost'], 2) }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Item Type Summary -->
        <div class="sub-summary">
            <h4>Summary by Item Type</h4>
            <table class="summary-table">
                <thead><tr><th>Type</th><th class="text-right">Count</th><th class="text-right">Quantity</th><th class="text-right">Total Cost (₹)</th></tr></thead>
                <tbody>
                    @foreach($item_type_summary as $type => $sum)
                    @if($sum['count'] > 0)
                    <tr><td>{{ ucfirst($type) }}</td><td class="text-right">{{ $sum['count'] }}</td><td class="text-right">{{ number_format($sum['total_quantity']) }}</td><td class="text-right">₹ {{ number_format($sum['total_cost'], 2) }}</td></tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Items Details Table -->
        <div class="sub-summary">
            <h4>Item Details</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th><th>Item Name</th><th>Item Code</th><th>Category</th><th>Type</th>
                        <th>Brand</th><th>Model</th><th>Serial</th><th>Qty</th><th>Cost (₹)</th>
                        <th>Purchase Date</th><th>Vendor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->item_code }}</td>
                        <td>{{ $item->category->category_name ?? '-' }}</td>
                        <td class="text-center"><span class="badge {{ $item->item_type == 1 ? 'badge-refurbished' : 'badge-new' }}">{{ $item->item_type == 1 ? 'Refurbished' : 'New' }}</span></td>
                        <td>{{ $item->brand ?? '-' }}</td>
                        <td>{{ $item->model_number ?? '-' }}</td>
                        <td>{{ $item->serial_number ?? '-' }}</td>
                        <td class="text-right">{{ number_format($item->quantity) }}</td>
                        <td class="text-right">₹ {{ number_format($item->purchase_cost, 2) }}</td>
                        <td>{{ $item->purchase_date ? \Carbon\Carbon::parse($item->purchase_date)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $item->vendor_name ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @elseif($report_type == 'assignments')
        <!-- ==================== ASSIGNMENTS REPORT ==================== -->

        <div class="summary-container">
            <div class="summary-card"><h4>Total Assignments</h4><div class="value">{{ $total_assignments }}</div></div>
            <div class="summary-card"><h4>Assigned</h4><div class="value">{{ $assigned_count }}</div></div>
            <div class="summary-card"><h4>Returned</h4><div class="value">{{ $returned_count }}</div></div>
            <div class="summary-card"><h4>Active Items</h4><div class="value">{{ $active_count }}</div></div>
            <div class="summary-card"><h4>Scrap Items</h4><div class="value">{{ $scrap_count }}</div></div>
        </div>

        <!-- Department Summary -->
        @if(count($department_summary) > 0)
        <div class="sub-summary">
            <h4>Summary by Department</h4>
            <table class="summary-table">
                <thead><tr><th>Department</th><th class="text-right">Total</th><th class="text-right">Assigned</th><th class="text-right">Returned</th></tr></thead>
                <tbody>
                    @foreach($department_summary as $deptName => $sum)
                    <tr><td>{{ $deptName }}</td><td class="text-right">{{ $sum['total'] }}</td><td class="text-right">{{ $sum['assigned'] }}</td><td class="text-right">{{ $sum['returned'] }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Assignments Details Table -->
        <div class="sub-summary">
            <h4>Assignment Details</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th><th>Item</th><th>Employee</th><th>Department</th><th>Condition</th><th>Status</th><th>Assigned Date</th><th>Return Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignments as $index => $a)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $a->item->item_name ?? '-' }}</td>
                        <td>{{ $a->employee->fullname ?? '-' }}</td>
                        <td>{{ $a->department->dep_name ?? '-' }}</td>
                        <td class="text-center"><span class="badge {{ $a->condition_status == 1 ? 'badge-active' : 'badge-scrap' }}">{{ $a->condition_status == 1 ? 'Active' : 'Scrap' }}</span></td>
                        <td class="text-center"><span class="badge {{ $a->status == 0 ? 'badge-assigned' : 'badge-returned' }}">{{ $a->status == 0 ? 'Assigned' : 'Returned' }}</span></td>
                        <td>{{ $a->assigned_date ? \Carbon\Carbon::parse($a->assigned_date)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $a->return_date ? \Carbon\Carbon::parse($a->return_date)->format('d-m-Y') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @elseif($report_type == 'maintenances')
        <!-- ==================== MAINTENANCE REPORT ==================== -->

        <div class="summary-container">
            <div class="summary-card"><h4>Total Records</h4><div class="value">{{ $total_maintenances }}</div></div>
            <div class="summary-card"><h4>Total Cost</h4><div class="value">₹ {{ number_format($total_cost, 2) }}</div></div>
            <div class="summary-card"><h4>Pending</h4><div class="value">{{ $status_summary['pending'] }}</div></div>
            <div class="summary-card"><h4>Completed</h4><div class="value">{{ $status_summary['complete'] }}</div></div>
        </div>

        <!-- Maintenance Type Summary -->
        <div class="sub-summary">
            <h4>Summary by Type</h4>
            <table class="summary-table">
                <thead><tr><th>Type</th><th class="text-right">Count</th><th class="text-right">Total Cost (₹)</th></tr></thead>
                <tbody>
                    @foreach($type_summary as $type => $sum)
                    @if($sum['count'] > 0)
                    <tr><td>{{ ucfirst($type) }}</td><td class="text-right">{{ $sum['count'] }}</td><td class="text-right">₹ {{ number_format($sum['total_cost'], 2) }}</td></tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Maintenance Details Table -->
        <div class="sub-summary">
            <h4>Maintenance Details</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th><th>Item</th><th>Issue</th><th>Type</th><th>Cost (₹)</th><th>Vendor</th><th>Start Date</th><th>End Date</th><th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($maintenances as $index => $m)
                    @php
                        $typeLabels = ['Scrap', 'Service', 'Upgrade'];
                        $typeClass = match($m->maintenance_type) { 0 => 'badge-scrap', 1 => 'badge-service', 2 => 'badge-upgrade', default => '' };
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $m->item->item_name ?? '-' }}</td>
                        <td>{{ Str::limit($m->issue_description, 40) }}</td>
                        <td><span class="badge {{ $typeClass }}">{{ $typeLabels[$m->maintenance_type] ?? '-' }}</span></td>
                        <td class="text-right">₹ {{ number_format($m->cost, 2) }}</td>
                        <td>{{ $m->vendor_name ?? '-' }}</td>
                        <td>{{ $m->start_date ? \Carbon\Carbon::parse($m->start_date)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $m->end_date ? \Carbon\Carbon::parse($m->end_date)->format('d-m-Y') : '-' }}</td>
                        <td><span class="badge {{ $m->status == 0 ? 'badge-pending' : 'badge-complete' }}">{{ $m->status == 0 ? 'Pending' : 'Complete' }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @elseif($report_type == 'categories')
        <!-- ==================== CATEGORIES REPORT ==================== -->

        <div class="summary-container">
            <div class="summary-card"><h4>Total Categories</h4><div class="value">{{ $total_categories }}</div></div>
            <div class="summary-card"><h4>Total Items</h4><div class="value">{{ number_format($total_items) }}</div></div>
            <div class="summary-card"><h4>Total Value</h4><div class="value">₹ {{ number_format($total_cost, 2) }}</div></div>
            <div class="summary-card"><h4>Avg Items/Cat</h4><div class="value">{{ $total_categories > 0 ? round($total_items / $total_categories, 1) : 0 }}</div></div>
        </div>

        <!-- Categories Details Table -->
        <div class="sub-summary">
            <h4>Category Details</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th><th>Category Name</th><th>Description</th><th class="text-right">Items Count</th><th class="text-right">Total Quantity</th><th class="text-right">Total Value (₹)</th><th>Created Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $index => $cat)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $cat->category_name }}</td>
                        <td>{{ Str::limit($cat->description, 50) ?? '-' }}</td>
                        <td class="text-right">{{ $cat->items->count() }}</td>
                        <td class="text-right">{{ number_format($cat->items->sum('quantity')) }}</td>
                        <td class="text-right">₹ {{ number_format($cat->items->sum('purchase_cost'), 2) }}</td>
                        <td>{{ $cat->created_at ? $cat->created_at->format('d-m-Y') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Categories with Most Items -->
        @if($categories->count() > 0)
        <div class="sub-summary">
            <h4>Top 5 Categories by Item Count</h4>
            <table class="summary-table">
                <thead><tr><th>Category</th><th class="text-right">Items Count</th><th class="text-right">Total Value (₹)</th></tr></thead>
                <tbody>
                    @foreach($categories->sortByDesc(function($cat) { return $cat->items->count(); })->take(5) as $cat)
                    <tr><td>{{ $cat->category_name }}</td><td class="text-right">{{ $cat->items->count() }}</td><td class="text-right">₹ {{ number_format($cat->items->sum('purchase_cost'), 2) }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

    @endif

    <div class="footer">
        <p>This is a system-generated report. For any discrepancies, please contact the inventory department.</p>
        <p>{{ $company_name }} - Inventory Management System</p>
    </div>

</body>
</html>
