<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\InventoryAssignment;
use App\Models\InventoryItem;
use App\Models\Employee;
use App\Models\Department;
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
            ->where('status', 'assigned')
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
            $query->whereHas('item', function($q) use ($request){
                $q->where('item_name', 'like', '%' . $request->item . '%');
            });
        }

        if ($request->filled('employee')) {
            $query->whereHas('employee', function($q) use ($request){
                $q->where('fullname', 'like', '%' . $request->employee . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->get('per_page', 5);

        $assignments = $query->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('dashboard.hr.inventory.assignment.index', compact('assignments'));
    }

    public function create()
    {
        $items = InventoryItem::where('delete_status', 1)->get();
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
            'status' => 'assigned',
            'remarks' => $request->remarks,
        ]);

        InventoryHistory::create([
            'item_id' => $request->item_id,
            'employee_id' => $request->employee_id,
            'action_type' => 'assigned',
            'old_status' => 'available',
            'new_status' => 'assigned',
            'notes' => $request->remarks ?? 'Item assigned to employee',
            'action_date' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Item assigned successfully'
        ]);
    }

    public function returnItem($id)
    {
        $assignment = InventoryAssignment::findOrFail($id);

        if ($assignment->status == 'returned') {
            return response()->json([
                'status' => false,
                'message' => 'Already returned'
            ]);
        }

        $assignment->status = 'returned';
        $assignment->return_date = now();
        $assignment->save();

        InventoryHistory::create([
            'item_id' => $assignment->item_id,
            'employee_id' => $assignment->employee_id,
            'action_type' => 'returned',
            'old_status' => 'assigned',
            'new_status' => 'available',
            'notes' => 'Item returned by employee',
            'action_date' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Item returned successfully'
        ]);
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

    // ✅ INDEX
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

    // ✅ CREATE
    public function create()
    {
        return view('dashboard.hr.inventory.category.create');
    }

    // ✅ STORE (🔥 fixed)
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

        InventoryCategory::create([
            'category_name' => $request->category_name,
            'description' => $request->description,
            'delete_status' => 1
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Category created successfully'
        ]);
    }

    // ✅ EDIT
    public function edit($id)
    {
        $category = InventoryCategory::findOrFail($id);
        return view('dashboard.hr.inventory.category.edit', compact('category'));
    }

    // ✅ UPDATE (🔥 IMPORTANT FIX)
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
                    ->where('id', '!=', $id) // 🔥 ignore current record
                    ->exists();

                    if ($exists) {
                        $fail('Category already exists');
                    }
                }
            ],
        ]);

        $category->update([
            'category_name' => $request->category_name,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Category updated successfully'
        ]);
    }

    // ✅ DELETE
    public function destroy($id)
    {
        $category = InventoryCategory::findOrFail($id);

        $category->update(['delete_status' => 0]);

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
}
<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
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

    // ✅ LIST (VIEW ONLY)

    public function index(Request $request)
{
    $query = InventoryHistory::with('item');

    // 🔍 FILTER ITEM
    if ($request->filled('item')) {
        $query->whereHas('item', function($q) use ($request){
            $q->where('item_name', 'like', '%' . $request->item . '%');
        });
    }

    // 🔍 FILTER ACTION
    if ($request->filled('action')) {
        $query->where('action_type', $request->action);
    }

    // 🔍 FILTER STATUS
    if ($request->filled('status')) {
        $query->where('new_status', $request->status);
    }

    $perPage = $request->get('per_page', 5);

    $histories = $query->latest()
        ->paginate($perPage)
        ->withQueryString();

    return view('dashboard.hr.inventory.history.index', compact('histories'));
}

    // ✅ SHOW DETAILS
    public function show($id)
    {
        $history = InventoryHistory::with(['item'])->findOrFail($id);

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
        $lastItem = InventoryItem::latest()->first();
        $lastId = $lastItem ? $lastItem->id : 0;

        return view('dashboard.hr.inventory.item.create', compact('categories', 'lastId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required',
            'item_code' => 'required|unique:inventory_items,item_code',
            'category_id' => 'required',
        ]);

        $item = new InventoryItem();
        $item->fill($request->all());

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

        InventoryHistory::create([
            'item_id' => $item->id,
            'employee_id' => null,
            'action_type' => 'created',
            'old_status' => null,
            'new_status' => 'available',
            'notes' => 'New inventory item created: ' . $request->item_name,
            'action_date' => now(),
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

        $data = $request->except('item_image');
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

        InventoryHistory::create([
            'item_id' => $item->id,
            'employee_id' => null,
            'action_type' => 'updated',
            'old_status' => $item->getOriginal('delete_status') == 1 ? 'available' : 'deleted',
            'new_status' => 'available',
            'notes' => 'Item details updated: ' . $request->item_name,
            'action_date' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Inventory item updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $item = InventoryItem::findOrFail($id);

        $oldStatus = $item->delete_status == 1 ? 'available' : 'deleted';

        $item->update(['delete_status' => 0]);

        InventoryHistory::create([
            'item_id' => $item->id,
            'employee_id' => null,
            'action_type' => 'deleted',
            'old_status' => $oldStatus,
            'new_status' => 'deleted',
            'notes' => 'Item deleted: ' . $item->item_name,
            'action_date' => now(),
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
            $query->whereHas('item', function($q) use ($request){
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

        $maintenances = $query->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('dashboard.hr.inventory.maintenance.index', compact('maintenances'));
    }

    public function create()
    {
        $items = InventoryItem::where('delete_status', 1)
            ->whereDoesntHave('maintenances', function($q){
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
            'maintenance_type' => 'required|in:scrap,service,upgrade',
            'cost' => 'required|numeric|min:0',
            'vendor_name' => 'required|min:2',
            'start_date' => 'required|date',
            'remarks' => 'nullable|max:500',
        ]);

        $maintenance = InventoryMaintenance::create([
            'item_id' => $request->item_id,
            'employee_id' => $request->employee_id,
            'issue_description' => $request->issue_description,
            'maintenance_type' => $request->maintenance_type,
            'cost' => $request->cost,
            'vendor_name' => $request->vendor_name,
            'start_date' => $request->start_date,
            'status' => 'pending',
            'remarks' => $request->remarks,
        ]);

        InventoryHistory::create([
            'item_id' => $request->item_id,
            'employee_id' => $request->employee_id,
            'action_type' => 'maintenance',
            'old_status' => 'assigned',
            'new_status' => 'maintenance',
            'notes' => $request->issue_description,
            'action_date' => now(),
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
            'maintenance_type' => 'required|in:scrap,service,upgrade',
            'issue_description' => 'required|min:5|max:500',
            'cost' => 'required|numeric|min:0',
            'vendor_name' => 'required|min:2',
            'start_date' => 'required|date',
            'status' => 'required|in:pending,completed',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'remarks' => 'nullable|max:500',
        ]);

        $data = $request->except('_token', '_method');

        if ($request->status == 'pending') {
            $data['end_date'] = null;
        }

        $maintenance->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Maintenance updated successfully'
        ]);
    }

    public function complete($id)
    {
        $maintenance = InventoryMaintenance::findOrFail($id);

        if ($maintenance->status == 'completed') {
            return response()->json([
                'status' => false,
                'message' => 'Already completed'
            ]);
        }

        $maintenance->status = 'completed';

        if (!$maintenance->end_date) {
            $maintenance->end_date = now();
        }

        $maintenance->save();

        InventoryHistory::create([
            'item_id' => $maintenance->item_id,
            'employee_id' => $maintenance->employee_id,
            'action_type' => 'maintenance',
            'old_status' => 'maintenance',
            'new_status' => 'available',
            'notes' => 'Maintenance completed: ' . $maintenance->issue_description,
            'action_date' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Maintenance completed'
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
            ->where('status', 'assigned')
            ->with(['employee', 'department'])
            ->first();

        if($assignment) {
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

------------------------
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
        'remarks',
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
--------------------
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
          $table->enum('item_type', ['new', 'refurbished'])->default('new');
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
    $table->enum('status', ['assigned','returned'])->default('assigned');
    $table->text('remarks')->nullable();
    $table->timestamps();
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
            $table->foreignId('item_id')->constrained('inventory_items')->cascadeOnDelete();
           $table->unsignedBigInteger('employee_id')->nullable();
            $table->enum('action_type', ['created','assigned','returned','damaged','repaired']);
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('action_date')->useCurrent();
            $table->foreign('employee_id')
      ->references('emp_id')
      ->on('employees')
      ->nullOnDelete();
            $table->timestamps();
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
            $table->enum('maintenance_type', ['scrap', 'service', 'upgrade'])->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('vendor_name')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['pending','completed'])->default('pending');
            $table->text('remarks')->nullable();
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

-------------
blade pages

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
        └── index.blade.php
```


assignment 
<x-layout>
@section('title','Assign Inventory')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>



<style>

.select2-selection.is-invalid {
    border: 1px solid #dc3545 !important;
}

/* 🔥 Fix Select2 height to match inputs */
.select2-container .select2-selection--single {
    height: 45px !important;
    border: 1px solid #ced4da;
    border-radius: 6px;
    padding: 6px 10px;
    display: flex;
    align-items: center;
}

/* Text alignment */
.select2-container .select2-selection__rendered {
    line-height: normal !important;
    padding-left: 0 !important;
}

/* Fix arrow container height */
.select2-container .select2-selection__arrow {
    height: 45px !important;
    right: 10px;
}

/* 🔥 Increase arrow size */
.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-width: 6px 5px 0 5px; /* bigger arrow */
}

/* Center arrow properly */
.select2-container--default .select2-selection--single .select2-selection__arrow {
    display: flex;
    align-items: center;
    justify-content: center;
}

</style>
<div class="container-fluid p-4">

 <div class="d-flex justify-content-between align-items-center mb-4">
            <div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 mt-2">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dhome') }}" class="text-decoration-none text-dark">
                               Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('inventory.assignments.index') }}" class="text-decoration-none text-dark">
                                Inventory Assignment
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary">Create</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('inventory.assignments.index') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-arrow-left-circle me-2"></i>Back
            </a>
        </div>


<form id="assignForm">
@csrf

<div class="row">

<!-- 🔥 LEFT FORM -->
<div class="col-lg-9">

<div class="card shadow-sm border-0 p-4">

<div class="col-12 mt-3 ">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Assignment Create</h6>
    </div>
</div>
<div class="row g-3 mt-3">



<!-- DEPARTMENT -->
<div class="col-md-6">
<label>Department <span class=" text-danger">*</span></label>
<select name="department_id" id="department" class="form-select select2">
<option value="">Select Department</option>
@foreach($departments as $dep)
<option value="{{ $dep->dep_id }}">{{ $dep->dep_name }}</option>
@endforeach
</select>
</div>

<!-- EMPLOYEE -->
<div class="col-md-6">
<label>Employee <span class=" text-danger">*</span></label>
<select name="employee_id" id="employee" class="form-select select2">
<option value="">Select Employee</option>
</select>
</div>


<!-- ITEM -->
<div class="col-md-6">
<label>Item  <span class=" text-danger">*</span></label>
<select name="item_id" class="form-select select2">
<option value="">Select Item</option>
@foreach($items as $item)
<option value="{{ $item->id }}">{{ $item->item_name }}</option>
@endforeach
</select>
</div>

<!-- DATE -->
<div class="col-md-6">
<label>Assigned Date <span class=" text-danger">*</span></label>
<input type="date" name="assigned_date" class="form-control">
</div>

<!-- REMARK -->
<div class="col-12">
<label>Remarks</label>
<textarea name="remarks" class="form-control"></textarea>
</div>

</div>

</div>
</div>

<!-- 🔥 RIGHT ACTION CARD -->
<div class="col-lg-3">

<div class="card shadow-sm border-0 p-4 position-sticky" style="top:100px;">

<h6 class="fw-bold text-primary mb-3">Actions</h6>

<div class="d-flex gap-2">
<button type="submit" class="btn btn-primary w-100">Assign</button>

<a href="{{ route('inventory.assignments.index') }}" class="btn btn-outline-secondary w-100">
Cancel
</a>
</div>

</div>

</div>

</div>
</form>
</div>




<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function(){

    // ✅ Select2 init
    $('.select2').select2({
        placeholder: "Select option",
        allowClear: true,
        width: '100%'
    });

    // 🔥 ===============================
    // ✅ DEPARTMENT → EMPLOYEE
    // 🔥 ===============================
    $('#department').on('change', function(){

        let depId = $(this).val();

        $('#employee').val(null).trigger('change');

        if(!depId){
            $('#employee').html('<option value="">Select Employee</option>').trigger('change');
            return;
        }

        $('#employee').html('<option value="">Loading...</option>');

        let url = "/dashboard/employees/inventory-assignments/get-employees/" + depId;

        $.get(url, function(res){

            let options = '<option value="">Select Employee</option>';

            if(res.length === 0){
                options += '<option>No employees found</option>';
            }

            res.forEach(emp=>{
                options += `<option value="${emp.emp_id}">${emp.fullname}</option>`;
            });

            $('#employee').html(options).trigger('change.select2');
        });

    });

    // 🔥 ===============================
    // ✅ ITEM ALREADY ASSIGNED CHECK
    // 🔥 ===============================
    $('select[name="item_id"]').on('change', function(){

        let itemId = $(this).val();
        let input = $(this);

        clearError(input);

        if(!itemId) return;

        let url = "/dashboard/employees/inventory-assignments/check-item/" + itemId;

        $.get(url, function(res){

            if(res.assigned){
                showError(input,'This item is already assigned ❌');
            }

        });

    });

});
</script>

<script>

function showError(input,msg){

    input.addClass('is-invalid');

    if(input.next('.select2-container').length){
        input.next('.select2-container').find('.select2-selection')
            .addClass('is-invalid');
    }

    if(input.closest('div').find('.error-msg').length === 0){
        input.closest('div').append('<div class="text-danger small error-msg">'+msg+'</div>');
    }
}

function clearError(input){
    input.removeClass('is-invalid');
    input.closest('div').find('.error-msg').remove();

    if(input.next('.select2-container').length){
        input.next('.select2-container').find('.select2-selection')
            .removeClass('is-invalid');
    }
}

function validate(input){

    let val = input.val();
    let name = input.attr('name');

    clearError(input);

    // ✅ REQUIRED FIELDS
    if(name=='item_id' && !val){
        showError(input,'Item required'); return false;
    }

    if(name=='department_id' && !val){
        showError(input,'Department required'); return false;
    }

    if(name=='employee_id' && !val){
        showError(input,'Employee required'); return false;
    }

    if(name=='assigned_date' && !val){
        showError(input,'Date required'); return false;
    }

    // 🔥 REMARKS VALIDATION
    if(name=='remarks'){
        if(val && (val.length < 5 || val.length > 300)){
            showError(input,'Remarks must be 5 to 300 characters');
            return false;
        }
    }

    return true;
}

// 🔥 LIVE VALIDATION
$('select,input,textarea').on('change keyup',function(){
    validate($(this));
});

// 🔥 SUBMIT
$('#assignForm').submit(function(e){
    e.preventDefault();

    let valid = true;

    $('select,input,textarea').each(function(){
        if(!validate($(this))) valid=false;
    });

    // 🔥 BLOCK IF ITEM INVALID
    let itemInvalid = $('select[name="item_id"]').hasClass('is-invalid');

    if(!valid || itemInvalid) return;

    $.post('{{ route("inventory.assignments.store") }}',
        $(this).serialize(),
        function(res){

            if(res.status){
                Swal.fire('Success','Item Assigned','success').then(()=>{
                    window.location.href='{{ route("inventory.assignments.index") }}';
                });
            }
        }
    );
});
</script>
</x-layout>

maintance 
<x-layout>
@section('title','Add Maintenance')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

<style>
.select2-selection.is-invalid {
    border: 1px solid #dc3545 !important;
}

.select2-container .select2-selection--single {
    height: 45px !important;
    border: 1px solid #ced4da;
    border-radius: 6px;
    padding: 6px 10px;
    display: flex;
    align-items: center;
}

.select2-container .select2-selection__rendered {
    line-height: normal !important;
    padding-left: 0 !important;
}

.select2-container .select2-selection__arrow {
    height: 45px !important;
    right: 10px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-width: 6px 5px 0 5px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    display: flex;
    align-items: center;
    justify-content: center;
}

.employee-details-card {
    background: #f8f9fa;
    border-left: 4px solid #0d6efd;
    transition: all 0.3s ease;
}

.employee-details-card:hover {
    background: #e9ecef;
}
</style>

<div class="container-fluid p-4">

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 mt-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('dhome') }}" class="text-decoration-none text-dark">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('inventory.maintenance.index') }}" class="text-decoration-none text-dark">Inventory Maintenance</a>
                </li>
                <li class="breadcrumb-item active text-primary">Create</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('inventory.maintenance.index') }}" class="btn btn-outline-primary d-flex align-items-center">
        <i class="bi bi-arrow-left-circle me-2"></i>Back
    </a>
</div>

<form id="maintenanceForm">
@csrf

<div class="row">

<!-- LEFT FORM -->
<div class="col-lg-9">

<div class="card shadow-sm border-0 p-4">

<div class="bg-light rounded-3 p-3 mb-3">
    <h6 class="fw-bold text-primary mb-0">Maintenance Details</h6>
</div>

<div class="row g-3">

<!-- ITEM -->
<div class="col-md-6">
    <label>Item <span class="text-danger">*</span></label>
    <select name="item_id" id="item_id" class="form-select select2">
        <option value="">Select Item</option>
        @foreach($items as $item)
        <option value="{{ $item->id }}">{{ $item->item_name }} ({{ $item->item_code }})</option>
        @endforeach
    </select>
</div>

<!-- ASSIGNED EMPLOYEE DETAILS (Dynamic) -->
<div class="col-12" id="employeeDetailsContainer" style="display:none;">
    <div class="card employee-details-card mt-2 mb-2">
        <div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-person-badge fs-2 text-primary"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1 text-primary">Currently Assigned To:</h6>
                    <div id="employeeDetails">
                        <p class="mb-1"><strong>Name:</strong> <span id="emp_name">-</span></p>
                        <p class="mb-1"><strong>Department:</strong> <span id="emp_dept">-</span></p>
                        <p class="mb-0"><strong>Assigned Date:</strong> <span id="assigned_date">-</span></p>
                    </div>
                </div>
                <div>
                    <i class="bi bi-info-circle-fill text-info"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TYPE -->
<div class="col-md-6">
    <label>Type <span class="text-danger">*</span></label>
    <select name="maintenance_type" class="form-select select2">
        <option value="">Select Type</option>
        <option value="scrap">Scrap</option>
        <option value="service">Service</option>
        <option value="upgrade">Upgrade</option>
    </select>
</div>

<!-- ISSUE -->
<div class="col-md-6">
    <label>Issue <span class="text-danger">*</span></label>
    <textarea name="issue_description" class="form-control" placeholder="Enter issue description" rows="3"></textarea>
</div>

<!-- COST -->
<div class="col-md-6">
    <label>Cost <span class="text-danger">*</span></label>
    <input type="number" step="0.01" name="cost" class="form-control" placeholder="Enter cost">
</div>

<!-- VENDOR -->
<div class="col-md-6">
    <label>Vendor Name <span class="text-danger">*</span></label>
    <input type="text" name="vendor_name" class="form-control" placeholder="Enter vendor name">
</div>

<!-- DATE -->
<div class="col-md-6">
    <label>Start Date <span class="text-danger">*</span></label>
    <input type="date" name="start_date" class="form-control">
</div>

<!-- REMARK -->
<div class="col-12">
    <label>Remarks</label>
    <textarea name="remarks" class="form-control" placeholder="Optional remarks" rows="2"></textarea>
</div>

<!-- Hidden field for employee_id -->
<input type="hidden" name="employee_id" id="employee_id" value="">

</div>

</div>
</div>

<!-- RIGHT ACTION CARD -->
<div class="col-lg-3">
    <div class="card shadow-sm border-0 p-4 position-sticky" style="top:100px;">
        <h6 class="fw-bold text-primary mb-3">Actions</h6>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Save</button>
            <a href="{{ route('inventory.maintenance.index') }}" class="btn btn-outline-secondary w-100">Cancel</a>
        </div>
    </div>
</div>

</div>

</form>
</div>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function(){
    $('.select2').select2({
        placeholder: "Select option",
        allowClear: true,
        width: '100%'
    });

    // Get employee details when item is selected
    $('#item_id').on('change', function(){
        let itemId = $(this).val();

        if(!itemId) {
            $('#employeeDetailsContainer').hide();
            $('#employee_id').val('');
            return;
        }

        // ✅ FIXED: Use direct URL path instead of route helper
        let url = '/dashboard/employees/inventory-maintenance/check-assignment/' + itemId;

        $.get(url, function(res){
            if(res.assigned && res.assignment) {
                // Item is assigned, show employee details
                $('#emp_name').text(res.assignment.employee?.fullname || 'N/A');
                $('#emp_dept').text(res.assignment.department?.dep_name || 'N/A');
                $('#assigned_date').text(res.assignment.assigned_date || 'N/A');
                $('#employee_id').val(res.assignment.employee_id);
                $('#employeeDetailsContainer').fadeIn();
            } else {
                // Item not assigned
                $('#employeeDetailsContainer').hide();
                $('#employee_id').val('');
                Swal.fire({
                    icon: 'warning',
                    title: 'Item Not Assigned',
                    text: 'This item is not currently assigned to any employee',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        }).fail(function(){
            $('#employeeDetailsContainer').hide();
        });
    });
});

// Validation Functions
function showError(input,msg){
    input.addClass('is-invalid');
    if(input.next('.select2-container').length){
        input.next('.select2-container').find('.select2-selection').addClass('is-invalid');
    }
    if(input.closest('div').find('.error-msg').length === 0){
        input.closest('div').append('<div class="text-danger small error-msg">'+msg+'</div>');
    }
}

function clearError(input){
    input.removeClass('is-invalid');
    input.closest('div').find('.error-msg').remove();
    if(input.next('.select2-container').length){
        input.next('.select2-container').find('.select2-selection').removeClass('is-invalid');
    }
}

function validate(input){
    let val = input.val()?.trim();
    let name = input.attr('name');
    clearError(input);

    if(name == 'item_id' && !val){
        showError(input,'Item required');
        return false;
    }

    if(name == 'maintenance_type' && !val){
        showError(input,'Type required');
        return false;
    }

    if(name == 'issue_description'){
        if(!val){
            showError(input,'Issue required');
            return false;
        }
        if(val.length < 5 || val.length > 500){
            showError(input,'Issue must be 5–500 characters');
            return false;
        }
    }

    if(name == 'cost' && !val){
        showError(input,'Cost required');
        return false;
    }

    if(name == 'vendor_name'){
        if(!val){
            showError(input,'Vendor required');
            return false;
        }
        if(val.length < 2){
            showError(input,'Vendor name must be at least 2 characters');
            return false;
        }
    }

    if(name == 'start_date' && !val){
        showError(input,'Date required');
        return false;
    }

    return true;
}

$('input, textarea, select').on('keyup change', function(){
    validate($(this));
});

$('#maintenanceForm').submit(function(e){
    e.preventDefault();

    let valid = true;
    $('input, textarea, select').each(function(){
        if(!validate($(this))) valid = false;
    });

    if(!valid) return;

    $.ajax({
        url: '{{ route("inventory.maintenance.store") }}',
        type: 'POST',
        data: $(this).serialize(),
        success: function(res){
            if(res.status){
                Swal.fire('Success','Maintenance added successfully','success').then(()=>{
                    window.location.href = '{{ route("inventory.maintenance.index") }}';
                });
            }
        },
        error: function(xhr){
            let errors = xhr.responseJSON?.errors;
            if(errors){
                let errorMsg = Object.values(errors).flat().join('\n');
                Swal.fire('Error', errorMsg, 'error');
            } else {
                Swal.fire('Error', 'Something went wrong', 'error');
            }
        }
    });
});
</script>
</x-layout>


history  
<x-layout>
@section('title', 'Inventory History')

<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-2">Inventory History</h3>
            <nav>
                <ol class="breadcrumb mb-0 mt-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dhome') }}" class="text-muted text-decoration-none">
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-primary">History</li>
                </ol>
            </nav>
        </div>
    </div>

<form method="GET" class="row mb-3">

    <div class="col-md-3">
        <input type="text" name="item" value="{{ request('item') }}" class="form-control" placeholder="Item Name">
    </div>

    <div class="col-md-3">
        <select name="action" class="form-control">
            <option value="">All Action</option>
            <option value="assigned" {{ request('action')=='assigned'?'selected':'' }}>Assigned</option>
            <option value="returned" {{ request('action')=='returned'?'selected':'' }}>Returned</option>
            <option value="maintenance" {{ request('action')=='maintenance'?'selected':'' }}>Maintenance</option>
        </select>
    </div>

    <div class="col-md-2">
        <select name="status" class="form-control">
            <option value="">All Status</option>
            <option value="available" {{ request('status')=='available'?'selected':'' }}>Available</option>
            <option value="assigned" {{ request('status')=='assigned'?'selected':'' }}>Assigned</option>
            <option value="maintenance" {{ request('status')=='maintenance'?'selected':'' }}>Maintenance</option>
        </select>
    </div>

    <div class="col-md-4">
        <button class="btn btn-primary">Filter</button>
        <a href="{{ route('inventory.history.index') }}" class="btn btn-secondary">Reset</a>
    </div>

</form>

<div class="card border-0 shadow-sm">
<div class="card-body">

<div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
    <form method="GET" class="d-flex align-items-center gap-2">
        <label>Show</label>
        <select name="per_page" onchange="this.form.submit()" class="form-control form-control-sm">
            <option value="5" {{ request('per_page')==5?'selected':'' }}>5</option>
            <option value="10" {{ request('per_page')==10?'selected':'' }}>10</option>
            <option value="20" {{ request('per_page')==20?'selected':'' }}>20</option>
        </select>
        <span>entries</span>

        <input type="hidden" name="item" value="{{ request('item') }}">
        <input type="hidden" name="action" value="{{ request('action') }}">
        <input type="hidden" name="status" value="{{ request('status') }}">
    </form>
</div>

<div class="table-responsive">
<table class="table table-hover">
<thead class="table-light">
<tr>
<th>#</th>
<th>Item</th>
<th>Action</th>
<th>Status</th>
<th>Date</th>
<th class="">View</th>
</tr>
</thead>

<tbody>
@forelse($histories as $index => $history)
<tr>
<td>{{ $histories->firstItem() + $index }}</td>
<td>{{ $history->item->item_name ?? '-' }}</td>
<td class="text-capitalize">{{ $history->action_type }}</td>
<td>
<span class="badge p-2
    {{ $history->new_status == 'available' ? 'bg-success' :
       ($history->new_status == 'assigned' ? 'bg-warning text-dark' :
       ($history->new_status == 'maintenance' ? 'bg-info' : 'bg-secondary')) }}">
    {{ $history->new_status }}
</span>
</td>
<td>{{ $history->action_date ? \Carbon\Carbon::parse($history->action_date)->format('d-m-Y') : '-' }}</td>
<td class="">
<button class="btn btn-sm btn-outline-info view-history"
data-bs-toggle="modal"
data-bs-target="#viewModal"
data-item="{{ $history->item->item_name }}"
data-action="{{ $history->action_type }}"
data-old="{{ $history->old_status }}"
data-new="{{ $history->new_status }}"
data-notes="{{ $history->notes }}"
data-date="{{ \Carbon\Carbon::parse($history->action_date)->format('d-m-Y') }}">
<i class="bi bi-eye"></i>
</button>
</td>
</tr>
@empty
<tr>
<td colspan="6" class="text-center text-muted">No records found</td>
</tr>
@endforelse
</tbody>
</table>
</div>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
<div>
Showing {{ $histories->firstItem() }} to {{ $histories->lastItem() }}
of {{ $histories->total() }} entries
</div>

<div>
{{ $histories->onEachSide(1)->links('pagination::bootstrap-5') }}
</div>
</div>

</div>
</div>
</div>

<div class="modal fade" id="viewModal">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content border-0 shadow-lg rounded-4 p-4">

<div class="d-flex justify-content-between border-bottom pb-3 mb-3">
<div>
<h4 class="fw-bold mb-0">History Invoice</h4>
<small class="text-muted">Inventory Activity</small>
</div>
<span class="fw-bold" id="b_action"></span>
</div>

<div class="row">

<div class="col-md-6">
<div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
<h6 class="fw-bold text-primary">Item Info</h6>
<p><b>Item:</b> <span id="b_item"></span></p>
<p><b>Action:</b> <span id="b_action_text"></span></p>
</div>
</div>

<div class="col-md-6">
<div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
<h6 class="fw-bold text-primary">Status Change</h6>
<p><b>Old:</b> <span id="b_old"></span></p>
<p><b>New:</b> <span id="b_new"></span></p>
</div>
</div>

<div class="col-12">
<div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
<h6 class="fw-bold text-primary">Notes</h6>
<p id="b_notes"></p>
</div>
</div>

<div class="col-12">
<div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-light">
<h6 class="fw-bold text-primary">Date</h6>
<h5 id="b_date"></h5>
</div>
</div>

</div>

<div class="text-end mt-3">
<button onclick="window.print()" class="btn btn-success">🖨 Print</button>
</div>

</div>
</div>
</div>

<script>
$(document).on('click','.view-history',function(){

    let action = $(this).data('action');
    let newStatus = $(this).data('new');

    $('#b_item').text($(this).data('item'));
    $('#b_action_text').text(action.charAt(0).toUpperCase() + action.slice(1));
    $('#b_old').text($(this).data('old'));
    $('#b_new').text(newStatus);
    $('#b_notes').text($(this).data('notes') || '-');
    $('#b_date').text($(this).data('date'));

    let el = $('#b_action');

    el.text(newStatus);
    el.removeClass();

    if(newStatus === 'available'){
        el.addClass('fw-bold text-success');
    }
    else if(newStatus === 'assigned'){
        el.addClass('fw-bold text-warning');
    }
    else if(newStatus === 'maintenance'){
        el.addClass('fw-bold text-info');
    }
    else{
        el.addClass('fw-bold text-secondary');
    }

});
</script>

</x-layout>
