<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryHistory extends Model
{
    protected $table = 'inventory_history';
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
}


<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryCategory extends Model
{
    protected $fillable = [
        'category_name',
        'description',
        'delete_status',
    ];

    public function items()
    {
        return $this->hasMany(InventoryItem::class, 'category_id');
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
        'available_stock',
        'status',
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
}


------------------------------------------
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
            $table->integer('available_stock')->default(1);
            $table->enum('status', ['available','assigned','maintenance','damaged'])->default('available');
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
            $table->text('issue_description');
            $table->enum('maintenance_type', ['repair','service','upgrade']);
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('vendor_name')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['pending','completed'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_maintenance');
    }
};
-------------------------------------------------------------------------------
<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\InventoryAssignment;
use App\Models\InventoryItem;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;

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

    // ✅ LIST
    public function index()
    {
        $assignments = InventoryAssignment::with(['item', 'employee'])
            ->latest()
            ->get();

        return view('dashboard.hr.inventory.assignment.index', compact('assignments'));
    }

    // ✅ ASSIGN FORM
    public function create()
    {
        $items = InventoryItem::where('delete_status', 1)
            ->where('status', 'available')
            ->get();

        $employees = Employee::where('delete_status', 1)->get();
        $departments = Department::where('delete_status', 1)->get();

        return view('dashboard.hr.inventory.assignment.create', compact('items', 'employees', 'departments'));
    }

    // ✅ STORE (ASSIGN)
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'employee_id' => 'required',
            'department_id' => 'required',
            'assigned_date' => 'required|date',
        ]);

        // Create assignment
        $assignment = InventoryAssignment::create([
            'item_id' => $request->item_id,
            'employee_id' => $request->employee_id,
            'department_id' => $request->department_id,
            'assigned_date' => $request->assigned_date,
            'status' => 'assigned',
            'remarks' => $request->remarks,
        ]);

        // Update item status
        $item = InventoryItem::find($request->item_id);
        $item->status = 'assigned';
        $item->save();

        return response()->json([
            'status' => true,
            'message' => 'Item assigned successfully'
        ]);
    }

    // ✅ RETURN ITEM
    public function returnItem($id)
    {
        $assignment = InventoryAssignment::findOrFail($id);

        if ($assignment->status == 'returned') {
            return response()->json([
                'status' => false,
                'message' => 'Already returned'
            ]);
        }

        // Update assignment
        $assignment->status = 'returned';
        $assignment->return_date = now();
        $assignment->save();

        // Update item
        $item = InventoryItem::find($assignment->item_id);
        $item->status = 'available';
        $item->save();

        return response()->json([
            'status' => true,
            'message' => 'Item returned successfully'
        ]);
    }

    // ✅ VIEW
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

    // ✅ LIST (ONLY ACTIVE)
    public function index()
    {
        $categories = InventoryCategory::where('delete_status', 1)->latest()->get();
        return view('dashboard.hr.inventory.category.index', compact('categories'));
    }

    // ✅ CREATE FORM
    public function create()
    {
        return view('dashboard.hr.inventory.category.create');
    }

    // ✅ STORE
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|unique:inventory_categories,category_name',
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

    // ✅ SHOW
    public function show($id)
    {
        $category = InventoryCategory::findOrFail($id);
        return response()->json([
            'status' => true,
            'category' => $category
        ]);
    }

    // ✅ EDIT FORM
    public function edit($id)
    {
        $category = InventoryCategory::findOrFail($id);
        return view('dashboard.hr.inventory.category.edit', compact('category'));
    }

    // ✅ UPDATE
    public function update(Request $request, $id)
    {
        $category = InventoryCategory::findOrFail($id);

        $request->validate([
            'category_name' => 'required|unique:inventory_categories,category_name,' . $id,
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

    // ✅ SOFT DELETE
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
    public function index()
    {
        $histories = InventoryHistory::with(['item'])
            ->latest()
            ->get();

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

    // ✅ LIST (ONLY ACTIVE)
    public function index()
    {
        $items = InventoryItem::where('delete_status', 1)->latest()->get();
        return view('dashboard.hr.inventory.item.index', compact('items'));
    }

    // ✅ CREATE FORM
    public function create()
    {
        $categories = InventoryCategory::where('delete_status', 1)->get();
        return view('dashboard.hr.inventory.item.create', compact('categories'));
    }

    // ✅ STORE
    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required',
            'item_code' => 'required|unique:inventory_items,item_code',
            'category_id' => 'required',
        ]);

        $item = new InventoryItem();
        $item->fill($request->all());

        // IMAGE
        if ($request->filled('item_image') && is_string($request->item_image)) {
            $this->processBase64Image($request->item_image, $item);
        }

        // DOCUMENT
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $filename = 'doc_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('inventory_docs'), $filename);
            $item->document_file = $filename;
        }

        $item->save();

        return response()->json([
            'status' => true,
            'message' => 'Inventory item created successfully'
        ]);
    }

    // ✅ SHOW
    public function show($id)
    {
        $item = InventoryItem::findOrFail($id);
        return response()->json([
            'status' => true,
            'item' => $item
        ]);
    }

    // ✅ EDIT FORM
    public function edit($id)
    {
        $item = InventoryItem::findOrFail($id);
        $categories = InventoryCategory::where('delete_status', 1)->get();

        return view('dashboard.hr.inventory.item.edit', compact('item', 'categories'));
    }

    // ✅ UPDATE
    public function update(Request $request, $id)
    {
        $item = InventoryItem::findOrFail($id);

        $request->validate([
            'item_name' => 'required',
            'item_code' => 'required|unique:inventory_items,item_code,' . $id,
        ]);

        $item->fill($request->all());

        // IMAGE UPDATE
        if ($request->filled('item_image') && is_string($request->item_image)) {
            $this->processBase64Image($request->item_image, $item);
        }

        // DOCUMENT UPDATE
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $filename = 'doc_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('inventory_docs'), $filename);
            $item->document_file = $filename;
        }

        $item->save();

        return response()->json([
            'status' => true,
            'message' => 'Inventory item updated successfully'
        ]);
    }

    // ✅ SOFT DELETE
    public function destroy($id)
    {
        $item = InventoryItem::findOrFail($id);
        $item->update(['delete_status' => 0]);

        return response()->json([
            'status' => true,
            'message' => 'Item deleted successfully'
        ]);
    }

    // ✅ IMAGE PROCESS (BASE64)
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

            // delete old image
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
use App\Models\InventoryMaintenance;
use App\Models\InventoryItem;
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

    // ✅ LIST
    public function index()
    {
        $maintenances = InventoryMaintenance::with('item')->latest()->get();
        return view('dashboard.hr.inventory.maintenance.index', compact('maintenances'));
    }

    // ✅ CREATE FORM
    public function create()
    {
        $items = InventoryItem::where('delete_status', 1)->get();

        return view('dashboard.hr.inventory.maintenance.create', compact('items'));
    }

    // ✅ STORE (ADD REPAIR)
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'issue_description' => 'required',
            'maintenance_type' => 'required',
        ]);

        $maintenance = InventoryMaintenance::create([
            'item_id' => $request->item_id,
            'issue_description' => $request->issue_description,
            'maintenance_type' => $request->maintenance_type,
            'cost' => $request->cost,
            'vendor_name' => $request->vendor_name,
            'start_date' => $request->start_date,
            'status' => 'pending',
            'remarks' => $request->remarks,
        ]);

        // 🔥 update item status
        $item = InventoryItem::find($request->item_id);
        $item->status = 'maintenance';
        $item->save();

        return response()->json([
            'status' => true,
            'message' => 'Maintenance added successfully'
        ]);
    }

    // ✅ COMPLETE MAINTENANCE
    public function complete($id)
    {
        $maintenance = InventoryMaintenance::findOrFail($id);

        if ($maintenance->status == 'completed') {
            return response()->json([
                'status' => false,
                'message' => 'Already completed'
            ]);
        }

        // Update maintenance
        $maintenance->status = 'completed';
        $maintenance->end_date = now();
        $maintenance->save();

        // Update item
        $item = InventoryItem::find($maintenance->item_id);
        $item->status = 'available';
        $item->save();

        return response()->json([
            'status' => true,
            'message' => 'Maintenance completed'
        ]);
    }

    // ✅ VIEW
    public function show($id)
    {
        $maintenance = InventoryMaintenance::with('item')->findOrFail($id);

        return response()->json([
            'status' => true,
            'maintenance' => $maintenance
        ]);
    }
}


--------------------------------------------------------

<?php

use App\Http\Controllers\Dashboard\HR\InventoryItemController;
use Illuminate\Support\Facades\Route;

Route::prefix('inventory')->group(function () {

    Route::get('/', [InventoryItemController::class, 'index'])->name('inventory.index');

    Route::get('/create', [InventoryItemController::class, 'create'])->name('inventory.create');

    Route::post('/', [InventoryItemController::class, 'store'])->name('inventory.store');

    Route::get('/{id}/edit', [InventoryItemController::class, 'edit'])->name('inventory.edit');

    Route::put('/{id}', [InventoryItemController::class, 'update'])->name('inventory.update');

    Route::delete('/{id}', [InventoryItemController::class, 'destroy'])->name('inventory.destroy');

    Route::get('/{id}', [InventoryItemController::class, 'show'])->name('inventory.show');

});


<?php

use App\Http\Controllers\Dashboard\HR\InventoryAssignmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('inventory-assignments')->group(function () {

    Route::get('/', [InventoryAssignmentController::class, 'index'])->name('inventory.assignments.index');

    Route::get('/create', [InventoryAssignmentController::class, 'create'])->name('inventory.assignments.create');

    Route::post('/', [InventoryAssignmentController::class, 'store'])->name('inventory.assignments.store');

    Route::get('/{id}', [InventoryAssignmentController::class, 'show'])->name('inventory.assignments.show');

    // 🔥 SPECIAL ROUTE (RETURN)
    Route::post('/{id}/return', [InventoryAssignmentController::class, 'returnItem'])
        ->name('inventory.assignments.return');
});


<?php

use App\Http\Controllers\Dashboard\HR\InventoryCategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('inventory-categories')->group(function () {

    Route::get('/', [InventoryCategoryController::class, 'index'])->name('inventory.categories.index');

    Route::get('/create', [InventoryCategoryController::class, 'create'])->name('inventory.categories.create');

    Route::post('/', [InventoryCategoryController::class, 'store'])->name('inventory.categories.store');

    Route::get('/{id}/edit', [InventoryCategoryController::class, 'edit'])->name('inventory.categories.edit');

    Route::put('/{id}', [InventoryCategoryController::class, 'update'])->name('inventory.categories.update');

    Route::delete('/{id}', [InventoryCategoryController::class, 'destroy'])->name('inventory.categories.destroy');

    Route::get('/{id}', [InventoryCategoryController::class, 'show'])->name('inventory.categories.show');

});


<?php

use App\Http\Controllers\Dashboard\HR\InventoryHistoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('inventory-history')->group(function () {

    Route::get('/', [InventoryHistoryController::class, 'index'])
        ->name('inventory.history.index');

    Route::get('/{id}', [InventoryHistoryController::class, 'show'])
        ->name('inventory.history.show');
});


<?php

use App\Http\Controllers\Dashboard\HR\InventoryMaintenanceController;
use Illuminate\Support\Facades\Route;

Route::prefix('inventory-maintenance')->group(function () {

    Route::get('/', [InventoryMaintenanceController::class, 'index'])->name('inventory.maintenance.index');

    Route::get('/create', [InventoryMaintenanceController::class, 'create'])->name('inventory.maintenance.create');

    Route::post('/', [InventoryMaintenanceController::class, 'store'])->name('inventory.maintenance.store');

    Route::get('/{id}', [InventoryMaintenanceController::class, 'show'])->name('inventory.maintenance.show');

    // 🔥 COMPLETE ACTION
    Route::post('/{id}/complete', [InventoryMaintenanceController::class, 'complete'])
        ->name('inventory.maintenance.complete');
});


---------------------------------------------------------------------------

