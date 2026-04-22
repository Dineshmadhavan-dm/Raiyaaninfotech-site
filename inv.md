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
            ->where('status', 'assigned')
            ->exists();

        return response()->json([
            'assigned' => $exists
        ]);
    }



public function exportPdf(Request $request)
{
    try {
        $query = InventoryAssignment::with(['item', 'employee', 'department']);

        $exportType = $request->input('export_type', 'all');

        if ($exportType === 'status' && $request->has('status')) {
            $status = $request->input('status');
            if (!empty($status)) {
                $query->where('status', $status);
            }
        }

        if ($exportType === 'employee' && $request->has('employee_ids')) {
            $employeeIds = $request->input('employee_ids');
            if (!empty($employeeIds) && is_array($employeeIds)) {
                $query->whereIn('employee_id', $employeeIds);
            }
        }

        $sortBy = $request->input('sort_by', 'assigned_date');
        $sortOrder = $request->input('sort_order', 'asc');

        // Simple sorting without joins
        if ($sortBy === 'assigned_date' || $sortBy === 'status') {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            // Default sort by assigned date
            $query->orderBy('assigned_date', 'desc');
        }

        $assignments = $query->get();

        // Calculate totals
        $totalAssignments = $assignments->count();
        $assignedCount = $assignments->where('status', 'assigned')->count();
        $returnedCount = $assignments->where('status', 'returned')->count();

        // Group by department for summary
        $departmentSummary = [];
        foreach ($assignments as $assignment) {
            $deptName = $assignment->department->dep_name ?? 'Unknown';
            if (!isset($departmentSummary[$deptName])) {
                $departmentSummary[$deptName] = [
                    'total' => 0,
                    'assigned' => 0,
                    'returned' => 0
                ];
            }
            $departmentSummary[$deptName]['total']++;
            if ($assignment->status == 'assigned') {
                $departmentSummary[$deptName]['assigned']++;
            } else {
                $departmentSummary[$deptName]['returned']++;
            }
        }

        // Group by employee
        $employeeSummary = [];
        foreach ($assignments as $assignment) {
            $empName = $assignment->employee->fullname ?? 'Unknown';
            if (!isset($employeeSummary[$empName])) {
                $employeeSummary[$empName] = [
                    'total' => 0,
                    'assigned' => 0,
                    'returned' => 0
                ];
            }
            $employeeSummary[$empName]['total']++;
            if ($assignment->status == 'assigned') {
                $employeeSummary[$empName]['assigned']++;
            } else {
                $employeeSummary[$empName]['returned']++;
            }
        }

        // Get company details
        $companyLogo = null;
        $companyName = 'RAIYAAN INFOTECH';
        $settings = \App\Models\Setting::first();

        if ($settings) {
            if ($settings->webname) {
                $companyName = $settings->webname;
            }
            if ($settings->weblogo) {
                $logoPath = public_path('weblogo/' . $settings->weblogo);
                if (file_exists($logoPath)) {
                    $logoData = file_get_contents($logoPath);
                    $mimeType = mime_content_type($logoPath);
                    $companyLogo = 'data:' . $mimeType . ';base64,' . base64_encode($logoData);
                }
            }
        }

        // Prepare filter description
        $filterDescription = 'All Assignments';
        if ($exportType === 'status' && $request->has('status')) {
            $filterDescription = 'Status: ' . ucfirst($request->input('status'));
        } elseif ($exportType === 'employee' && $request->has('employee_ids')) {
            $employeeIds = $request->input('employee_ids');
            $employeeNames = \App\Models\Employee::whereIn('emp_id', $employeeIds)->pluck('fullname')->toArray();
            $filterDescription = 'Employee(s): ' . implode(', ', $employeeNames);
        }

        $data = [
            'title' => 'Inventory Assignment Report',
            'filter_description' => $filterDescription,
            'report_generated_date' => now()->format('d-m-Y H:i:s'),
            'assignments' => $assignments,
            'total_assignments' => $totalAssignments,
            'assigned_count' => $assignedCount,
            'returned_count' => $returnedCount,
            'department_summary' => $departmentSummary,
            'employee_summary' => $employeeSummary,
            'company_logo' => $companyLogo,
            'company_name' => $companyName,
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
        ];

        // Check if view exists
        if (!view()->exists('dashboard.hr.inventory.assignment.export-pdf')) {
            return response()->json(['error' => 'PDF view not found'], 500);
        }

        $pdf = Pdf::loadView('dashboard.hr.inventory.assignment.export-pdf', $data);
        $pdf->setPaper('A4', 'landscape');

        $pdf->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);

        return $pdf->download('assignment_report_' . now()->format('Ymd_His') . '.pdf');

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
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
    $assignments = $query->latest()->paginate($perPage)->withQueryString();

    // Get departments for export modal
    $departments = Department::where('delete_status', 1)->get();

    // Get employees for export modal
    $employees = Employee::where('delete_status', 1)
        ->whereDoesntHave('resignation')
        ->select('emp_id', 'fullname', 'employee_id')
        ->get();

    return view('dashboard.hr.inventory.assignment.index', compact('assignments', 'departments', 'employees'));
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


public function exportPdf(Request $request)
{
    $query = InventoryItem::where('delete_status', 1)
        ->with('category');

    // Filter by export type
    $exportType = $request->input('export_type', 'all');

    if ($exportType === 'category' && $request->has('category_ids')) {
        $categoryIds = $request->input('category_ids');
        if (is_array($categoryIds) && !empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }
    }

    if ($exportType === 'item_type' && $request->has('item_type')) {
        $itemType = $request->input('item_type');
        if (!empty($itemType)) {
            $query->where('item_type', $itemType);
        }
    }

    // Sort by
    $sortBy = $request->input('sort_by', 'item_name');
    $sortOrder = $request->input('sort_order', 'asc');

    $allowedSortFields = ['item_name', 'item_code', 'purchase_date', 'purchase_cost'];
    if (in_array($sortBy, $allowedSortFields)) {
        $query->orderBy($sortBy, $sortOrder);
    } elseif ($sortBy === 'category') {
        $query->join('inventory_categories', 'inventory_items.category_id', '=', 'inventory_categories.id')
              ->orderBy('inventory_categories.category_name', $sortOrder)
              ->select('inventory_items.*');
    } else {
        $query->orderBy('item_name', 'asc');
    }

    $items = $query->get();

    // Calculate totals
    $totalItems = $items->count();
    $totalCost = $items->sum('purchase_cost');
    $totalQuantity = $items->sum('quantity');

    // Group by category for summary
    $categoriesSummary = [];
    foreach ($items as $item) {
        $categoryName = $item->category->category_name ?? 'Uncategorized';
        if (!isset($categoriesSummary[$categoryName])) {
            $categoriesSummary[$categoryName] = [
                'count' => 0,
                'total_cost' => 0,
                'total_quantity' => 0
            ];
        }
        $categoriesSummary[$categoryName]['count']++;
        $categoriesSummary[$categoryName]['total_cost'] += $item->purchase_cost;
        $categoriesSummary[$categoryName]['total_quantity'] += $item->quantity;
    }

    // Group by item type
    $itemTypeSummary = [
        'new' => ['count' => 0, 'total_cost' => 0, 'total_quantity' => 0],
        'refurbished' => ['count' => 0, 'total_cost' => 0, 'total_quantity' => 0],
        'other' => ['count' => 0, 'total_cost' => 0, 'total_quantity' => 0]
    ];

    foreach ($items as $item) {
        $type = $item->item_type ?? 'other';
        if (!isset($itemTypeSummary[$type])) {
            $type = 'other';
        }
        $itemTypeSummary[$type]['count']++;
        $itemTypeSummary[$type]['total_cost'] += $item->purchase_cost;
        $itemTypeSummary[$type]['total_quantity'] += $item->quantity;
    }

    // Get company details
    $companyLogo = null;
    $companyName = 'RAIYAAN INFOTECH';
    $settings = \App\Models\Setting::first();

    if ($settings) {
        if ($settings->webname) {
            $companyName = $settings->webname;
        }
        if ($settings->weblogo) {
            $logoPath = public_path('weblogo/' . $settings->weblogo);
            if (file_exists($logoPath)) {
                $logoData = file_get_contents($logoPath);
                $mimeType = mime_content_type($logoPath);
                $companyLogo = 'data:' . $mimeType . ';base64,' . base64_encode($logoData);
            }
        }
    }

    // Prepare filter description
    $filterDescription = 'All Items';
    if ($exportType === 'category' && $request->has('category_ids')) {
        $categoryNames = \App\Models\InventoryCategory::whereIn('id', $request->input('category_ids'))
            ->pluck('category_name')
            ->toArray();
        $filterDescription = 'Categories: ' . implode(', ', $categoryNames);
    } elseif ($exportType === 'item_type' && $request->has('item_type')) {
        $filterDescription = 'Item Type: ' . ucfirst($request->input('item_type'));
    }

    $data = [
        'title' => 'Inventory Report',
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
    ];

    $pdf = Pdf::loadView('dashboard.hr.inventory.item.export-pdf', $data);
    $pdf->setPaper('A4', 'landscape');

    $pdf->setOptions([
        'defaultFont' => 'DejaVu Sans',
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true,
    ]);

    return $pdf->download('inventory_report_' . now()->format('Ymd_His') . '.pdf');
}
    public function index(Request $request)
{
    $query = InventoryItem::where('delete_status', 1);

    // 🔍 FILTERS
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

    // categories for filter dropdown
    $categories = InventoryCategory::where('delete_status', 1)->get();

    return view('dashboard.hr.inventory.item.index', compact('items','categories'));
}

    // ✅ CREATE FORM
    public function create()
    {
        $categories = InventoryCategory::where('delete_status', 1)->get();

        $lastItem = InventoryItem::latest()->first();
$lastId = $lastItem ? $lastItem->id : 0;


        return view('dashboard.hr.inventory.item.create', compact('categories','lastId'));
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
------------------------------
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
---------------------------------------------------------------
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



```
└── 📁inventory
    └── 📁assignment
        ├── create.blade.php
        ├── export-pdf.blade.php
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
        ├── export-pdf.blade.php
        ├── index.blade.php
    └── 📁maintenance
        ├── create.blade.php
        ├── edit.blade.php
        └── index.blade.php
```

-------------------------------
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
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
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
            max-height: 60px;
            margin-bottom: 10px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #2c7da0;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0 5px;
        }
        .report-info {
            font-size: 9px;
            color: #666;
            margin-bottom: 3px;
        }
        .summary-container {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .summary-card {
            flex: 1;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            border: 1px solid #e0e0e0;
        }
        .summary-card h4 {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }
        .summary-card .value {
            font-size: 18px;
            font-weight: bold;
            color: #2c7da0;
        }
        .badge-assigned {
            background-color: #ffc107;
            color: #856404;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 7px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-returned {
            background-color: #28a745;
            color: white;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 7px;
            font-weight: bold;
            display: inline-block;
        }
        .sub-summary {
            margin-bottom: 20px;
        }
        .sub-summary h4 {
            font-size: 12px;
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
        .summary-table th, .summary-table td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }
        .summary-table th {
            background-color: #e9ecef;
            font-weight: bold;
            font-size: 9px;
        }
        .text-right {
            text-align: right;
        }
        .assignments-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .assignments-table th {
            background-color: #2c7da0;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
        }
        .assignments-table td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 8px;
        }
        .assignments-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            text-align: center;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($company_logo)
            <img src="{{ $company_logo }}" class="company-logo">
        @endif
        <div class="company-name">{{ $company_name }}</div>
        <div class="report-title">{{ $title }}</div>
        <div class="report-info">Filter: {{ $filter_description }}</div>
        <div class="report-info">Generated On: {{ $report_generated_date }}</div>
    </div>

    <div class="summary-container">
        <div class="summary-card">
            <h4>Total Assignments</h4>
            <div class="value">{{ $total_assignments }}</div>
        </div>
        <div class="summary-card">
            <h4>Currently Assigned</h4>
            <div class="value">{{ $assigned_count }}</div>
        </div>
        <div class="summary-card">
            <h4>Returned</h4>
            <div class="value">{{ $returned_count }}</div>
        </div>
    </div>

    <div class="sub-summary">
        <h4>Summary by Department</h4>
        <table class="summary-table">
            <thead>
                <tr><th>Department</th><th class="text-right">Total</th><th class="text-right">Assigned</th><th class="text-right">Returned</th></tr>
            </thead>
            <tbody>
                @foreach($department_summary as $deptName => $summary)
                <tr>
                    <td>{{ $deptName }}</td>
                    <td class="text-right">{{ $summary['total'] }}</td>
                    <td class="text-right">{{ $summary['assigned'] }}</td>
                    <td class="text-right">{{ $summary['returned'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="sub-summary">
        <h4>Assignment Details</h4>
        <table class="assignments-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item Name</th>
                    <th>Department</th>
                    <th>Employee</th>
                    <th>Status</th>
                    <th>Assigned Date</th>
                    <th>Return Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignments as $index => $a)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $a->item->item_name ?? '-' }}</td>
                    <td>{{ $a->department->dep_name ?? '-' }}</td>
                    <td>{{ $a->employee->fullname ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge-{{ $a->status }}">{{ ucfirst($a->status) }}</span>
                    </td>
                    <td>{{ $a->assigned_date ? \Carbon\Carbon::parse($a->assigned_date)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $a->return_date ? \Carbon\Carbon::parse($a->return_date)->format('d-m-Y') : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>{{ $company_name }} - Inventory Management System</p>
    </div>
</body>
</html>

<x-layout>
    @section('title', 'Inventory Assignments')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <style>
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

        <div class="d-flex justify-content-between mb-4">
            <h3>Inventory Assignments</h3>
            <div>
                <button type="button" class="btn btn-success me-2" id="exportAssignmentBtn">
                    <i class="bi bi-file-pdf"></i> Export PDF
                </button>
                <a href="{{ route('inventory.assignments.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Assign Item
                </a>
            </div>
        </div>

        <form method="GET" class="row mb-3">
            <div class="col-md-3">
                <input type="text" name="item" value="{{ request('item') }}" class="form-control" placeholder="Item Name">
            </div>
            <div class="col-md-3">
                <input type="text" name="employee" value="{{ request('employee') }}" class="form-control" placeholder="Employee Name">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="assigned" {{ request('status')=='assigned'?'selected':'' }}>Assigned</option>
                    <option value="returned" {{ request('status')=='returned'?'selected':'' }}>Returned</option>
                </select>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary">Filter</button>
                <a href="{{ route('inventory.assignments.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex mb-3">
                    <form method="GET" class="mb-3 d-flex align-items-center gap-2">
                        <label>Show</label>
                        <select name="per_page" onchange="this.form.submit()" class="form-control form-control-sm">
                            <option value="5" {{ request('per_page')==5?'selected':'' }}>5</option>
                            <option value="10" {{ request('per_page')==10?'selected':'' }}>10</option>
                            <option value="20" {{ request('per_page')==20?'selected':'' }}>20</option>
                        </select>
                        <span>entries</span>
                        <input type="hidden" name="item" value="{{ request('item') }}">
                        <input type="hidden" name="employee" value="{{ request('employee') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    </form>
                </div>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Department</th>
                            <th>Employee</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments as $i => $a)
                        <tr>
                            <td>{{ $assignments->firstItem() + $i }}</td>
                            <td>{{ $a->item->item_name ?? '-' }}</td>
                            <td>{{ $a->department->dep_name ?? '-' }}</td>
                            <td>{{ $a->employee->fullname ?? '-' }}</td>
                            <td>
                                <span class="badge p-2 {{ $a->status == 'assigned' ? 'bg-warning':'bg-success' }}">
                                    {{ $a->status }}
                                </span>
                            </td>
                            <td>{{ $a->assigned_date ? \Carbon\Carbon::parse($a->assigned_date)->format('d-m-Y') : '-' }}</td>
                            <td>
                                <button class="btn btn-sm btn-info view-btn"
                                    data-item="{{ $a->item->item_name }}"
                                    data-code="{{ $a->item->item_code }}"
                                    data-image="{{ asset('inventory_images/'.$a->item->item_image) }}"
                                    data-dept="{{ $a->department->dep_name }}"
                                    data-emp="{{ $a->employee->fullname }}"
                                    data-status="{{ $a->status }}"
                                    data-date="{{ \Carbon\Carbon::parse($a->assigned_date)->format('d-m-Y') }}"
                                    data-return="{{ $a->return_date ? \Carbon\Carbon::parse($a->return_date)->format('d-m-Y') : '' }}"
                                    data-remarks="{{ $a->remarks }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#billModal">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @if($a->status == 'assigned')
                                <button class="btn btn-sm btn-success return-btn" data-id="{{ $a->id }}">
                                    <i class="bi bi-arrow-return-left"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-between mt-3">
                    <div>Showing {{ $assignments->firstItem() }} to {{ $assignments->lastItem() }} of {{ $assignments->total() }} entries</div>
                    <div>{{ $assignments->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="billModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow-lg rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                    <div>
                        <h4 class="fw-bold mb-0">Assignment Invoice</h4>
                        <small class="text-muted">Inventory Tracking</small>
                    </div>
                    <span class="badge px-3 py-2" id="b_status"></span>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                            <h6 class="fw-bold text-primary mb-3">Item Details</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><b>Item:</b> <span id="b_item"></span></p>
                                    <p><b>Code:</b> <span id="b_code"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><b>Department:</b> <span id="b_dept"></span></p>
                                    <p><b>Employee:</b> <span id="b_emp"></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                            <h6 class="fw-bold text-primary mb-3">Assignment Info</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><b>Assigned Date:</b> <span id="b_date"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><b>Return Date:</b> <span id="b_return"></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="card border-0 shadow-sm rounded-4 p-3">
                            <h6 class="fw-bold text-primary">Remarks</h6>
                            <p id="b_remarks" class="text-muted"></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-4 p-3 mb-3 text-center">
                            <h6 class="fw-bold text-primary mb-2">Item Image</h6>
                            <img id="b_image" class="img-fluid rounded-3" style="max-height:200px; object-fit:cover;">
                        </div>
                        <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-light">
                            <h6 class="fw-bold text-primary">Status</h6>
                            <h4 id="b_status_text"></h4>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-4">
                    <button onclick="window.print()" class="btn btn-success">🖨 Print</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exportAssignmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Export Assignment Report (PDF)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Export Type</label>
                        <div class="d-flex gap-4 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="export_type" id="exportAllRadio" value="all" checked>
                                <label class="form-check-label" for="exportAllRadio">All Assignments</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="export_type" id="exportStatusRadio" value="status">
                                <label class="form-check-label" for="exportStatusRadio">By Status</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="export_type" id="exportEmployeeRadio" value="employee">
                                <label class="form-check-label" for="exportEmployeeRadio">By Employee</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 d-none" id="exportStatusDiv">
                        <label class="form-label fw-bold">Status</label>
                        <select id="exportStatusSelect" class="form-select">
                            <option value="">Select Status</option>
                            <option value="assigned">Assigned</option>
                            <option value="returned">Returned</option>
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="exportEmployeeDiv">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Department <span class="text-danger">*</span></label>
                            <select id="exportEmployeeDepartmentSelect" class="form-select">
                                <option value="">Select Department</option>
                                @foreach($departments ?? [] as $dept)
                                    <option value="{{ $dept->dep_id }}">{{ $dept->dep_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Select Employee(s) <span class="text-danger">*</span></label>

                            <div>
                                 <select id="exportEmployeeSelect" class="form-select " multiple="multiple">
                            </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Sort By</label>
                        <select id="exportSortBy" class="form-select">
                            <option value="assigned_date">Assigned Date</option>
                            <option value="item_name">Item Name</option>
                            <option value="employee_name">Employee Name</option>
                            <option value="department_name">Department Name</option>
                            <option value="status">Status</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Sort Order</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sort_order" id="sortAsc" value="asc" checked>
                                <label class="form-check-label" for="sortAsc">Ascending</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sort_order" id="sortDesc" value="desc">
                                <label class="form-check-label" for="sortDesc">Descending</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="exportFinalConfirmBtn">Generate PDF</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).on('click','.view-btn',function(){
        $('#b_item').text($(this).data('item'));
        $('#b_code').text($(this).data('code'));
        $('#b_dept').text($(this).data('dept'));
        $('#b_emp').text($(this).data('emp'));
        let status = $(this).data('status');
        $('#b_status_text').text(status.toUpperCase());
        $('#b_status').text(status);
        if(status === 'assigned'){
            $('#b_status').removeClass().addClass('badge bg-warning px-3 py-2');
        }else{
            $('#b_status').removeClass().addClass('badge bg-success px-3 py-2');
        }
        $('#b_date').text($(this).data('date'));
        $('#b_return').text($(this).data('return') || 'Not Returned');
        $('#b_remarks').text($(this).data('remarks') || '-');
        $('#b_image').attr('src', $(this).data('image'));
    });

    $(document).on('click','.return-btn',function(){
        let id = $(this).data('id');
        Swal.fire({
            title:'Return Item?',
            icon:'warning',
            showCancelButton:true
        }).then((res)=>{
            if(res.isConfirmed){
                let url = "{{ route('inventory.assignments.return', ['id' => 'ID']) }}";
                url = url.replace('ID', id);
                $.post(url,{
                    _token:'{{ csrf_token() }}'
                },function(resp){
                    if(resp.status){
                        Swal.fire('Returned','Item returned','success').then(()=>{
                            location.reload();
                        });
                    }else{
                        Swal.fire('Error',resp.message,'error');
                    }
                });
            }
        });
    });
    </script>

<script>
(function() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initExportModal);
    } else {
        initExportModal();
    }

    function initExportModal() {
        const exportAllRadio = document.getElementById('exportAllRadio');
        const exportStatusRadio = document.getElementById('exportStatusRadio');
        const exportEmployeeRadio = document.getElementById('exportEmployeeRadio');
        const exportStatusDiv = document.getElementById('exportStatusDiv');
        const exportEmployeeDiv = document.getElementById('exportEmployeeDiv');
        const exportBtn = document.getElementById('exportAssignmentBtn');
        const confirmBtn = document.getElementById('exportFinalConfirmBtn');
        const exportStatusSelect = document.getElementById('exportStatusSelect');
        const exportEmployeeDepartmentSelect = document.getElementById('exportEmployeeDepartmentSelect');
        const exportEmployeeSelect = document.getElementById('exportEmployeeSelect');

        let employeeSelect2 = null;

        if (exportEmployeeSelect) {
            employeeSelect2 = $(exportEmployeeSelect).select2({
                dropdownParent: $('#exportAssignmentModal'),
                placeholder: 'Select Employees',
                allowClear: true,
                closeOnSelect: false
            });
        }

        if (exportAllRadio && exportStatusRadio && exportEmployeeRadio) {
            exportAllRadio.addEventListener('change', function() {
                if (this.checked) {
                    exportStatusDiv.classList.add('d-none');
                    exportEmployeeDiv.classList.add('d-none');
                }
            });

            exportStatusRadio.addEventListener('change', function() {
                if (this.checked) {
                    exportStatusDiv.classList.remove('d-none');
                    exportEmployeeDiv.classList.add('d-none');
                }
            });

            exportEmployeeRadio.addEventListener('change', function() {
                if (this.checked) {
                    exportStatusDiv.classList.add('d-none');
                    exportEmployeeDiv.classList.remove('d-none');
                }
            });
        }

        if (exportEmployeeDepartmentSelect) {
            $(exportEmployeeDepartmentSelect).on('change', function() {
                const depId = $(this).val();
                if (depId) {
                    $.ajax({
                        url: '/dashboard/employees/inventory-assignments/get-employees/' + depId,
                        type: 'GET',
                        success: function(response) {
                            let options = '';
                            $.each(response, function(key, emp) {
                                options += '<option value="' + emp.emp_id + '">' + emp.fullname + '</option>';
                            });
                            exportEmployeeSelect.innerHTML = options;
                            if (employeeSelect2) {
                                employeeSelect2.val(null).trigger('change');
                            }
                        },
                        error: function(xhr) {
                            console.log('Error loading employees:', xhr);
                        }
                    });
                } else {
                    exportEmployeeSelect.innerHTML = '';
                    if (employeeSelect2) {
                        employeeSelect2.val(null).trigger('change');
                    }
                }
            });
        }

        if (exportBtn) {
            exportBtn.addEventListener('click', function() {
                const exportModal = new bootstrap.Modal(document.getElementById('exportAssignmentModal'));
                exportModal.show();
            });
        }

        const exportModalElement = document.getElementById('exportAssignmentModal');
        if (exportModalElement) {
            exportModalElement.addEventListener('show.bs.modal', function() {
                if (exportAllRadio) exportAllRadio.checked = true;
                if (exportStatusDiv) exportStatusDiv.classList.add('d-none');
                if (exportEmployeeDiv) exportEmployeeDiv.classList.add('d-none');
                if (exportStatusSelect) exportStatusSelect.value = '';
                if (exportEmployeeDepartmentSelect) {
                    $(exportEmployeeDepartmentSelect).val(null).trigger('change');
                }
                if (exportEmployeeSelect) {
                    exportEmployeeSelect.innerHTML = '';
                    if (employeeSelect2) {
                        employeeSelect2.val(null).trigger('change');
                    }
                }
                const sortAsc = document.getElementById('sortAsc');
                if (sortAsc) sortAsc.checked = true;
                const sortBy = document.getElementById('exportSortBy');
                if (sortBy) sortBy.value = 'assigned_date';
            });
        }

        if (confirmBtn) {
            const newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

            newConfirmBtn.addEventListener('click', function() {
                const exportType = document.querySelector('input[name="export_type"]:checked').value;
                let params = new URLSearchParams();
                params.append('export_type', exportType);

                if (exportType === 'status' && exportStatusSelect) {
                    const status = exportStatusSelect.value;
                    if (!status) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please select a status',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        return;
                    }
                    params.append('status', status);
                }

                if (exportType === 'employee' && exportEmployeeSelect) {
                    const employeeIds = $(exportEmployeeSelect).val();
                    if (!employeeIds || employeeIds.length === 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please select at least one employee',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        return;
                    }
                    employeeIds.forEach(id => {
                        params.append('employee_ids[]', id);
                    });
                }

                const sortBy = document.getElementById('exportSortBy');
                if (sortBy) {
                    params.append('sort_by', sortBy.value);
                }

                const sortOrder = document.querySelector('input[name="sort_order"]:checked');
                if (sortOrder) {
                    params.append('sort_order', sortOrder.value);
                }

                const exportModal = bootstrap.Modal.getInstance(document.getElementById('exportAssignmentModal'));
                if (exportModal) exportModal.hide();

                // FIXED URL - using full path instead of route name
                window.location.href = "/dashboard/employees/inventory-assignments/export-pdf?" + params.toString();
            });
        }
    }
})();
</script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</x-layout>


-----------------------------
category 

<x-layout>
@section('title','Add Category')

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
                            <a href="{{ route('inventory.categories.index') }}" class="text-decoration-none text-dark">
                                Inventory Category
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary">Create</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('inventory.categories.index') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-arrow-left-circle me-2"></i>Back
            </a>
        </div>

<div class="row justify-content-center">

<!-- LEFT FORM -->
<div class="col-lg-6">

<div class="card border-0 shadow-sm p-4">


<div class="col-12 mt-3 ">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Category Create</h6>
    </div>
</div>

<form id="categoryForm">
@csrf

<div class="row g-4 mt-3">

<div class="mt-2">
    <label class="form-label">
    Category Name <span class="text-danger">*</span>
</label>
<input type="text" name="category_name"
       class="form-control"
       placeholder="Enter category name">

<div id="category-check-msg" class="small mt-1"></div>
</div>


       <div class="mt-2">
        <label class="form-label">
    Description
</label>
<textarea name="description"
          class="form-control"
          placeholder="Enter description"></textarea>
       </div>



</div>

</form>

</div>
</div>

<!-- RIGHT ACTION CARD -->
<div class="col-lg-3">

<div class="card border-0 shadow-sm p-4">

<h6 class="fw-bold mb-3 text-primary">Actions</h6>

<div class="d-flex gap-2">
    <button type="submit" form="categoryForm" class="btn btn-success w-100">
        Save
    </button>

    <a href="{{ route('inventory.categories.index') }}" class="btn btn-outline-secondary w-100">
        Cancel
    </a>
</div>

</div>

</div>

</div>
</div>

<script>
let checkTimeout;
let isDuplicate = false;

$('input[name="category_name"]').on('keyup', function(){

    let input = $(this);
    let val = input.val().trim();

    validateField(input);

    clearTimeout(checkTimeout);

    if(val.length < 3) {
        $('#category-check-msg').text('');
        isDuplicate = false;
        return;
    }

    checkTimeout = setTimeout(function(){

        $.ajax({
            url: '{{ route("inventory.categories.check") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                category_name: val
            },
           success: function(res){

    if(res.exists){
        isDuplicate = true;
        $('#category-check-msg').html('<span class="text-danger">Category already exists</span>');
        input.addClass('is-invalid');
    }else{
        isDuplicate = false;
        $('#category-check-msg').html('');
        input.removeClass('is-invalid');
    }

}
        });

    }, 500);

});

function showError(input, message){
    input.addClass('is-invalid');

    if(input.closest('div').find('.error-msg').length === 0){
        let error = $('<div class="text-danger small mt-1 error-msg">'+message+'</div>');
        input.closest('div').append(error);

        setTimeout(()=>{
            error.fadeOut(300, function(){ $(this).remove(); });
            input.removeClass('is-invalid');
        },2000);
    }
}

function clearError(input){
    input.removeClass('is-invalid');
    input.closest('div').find('.error-msg').remove();
}

function validateField(input){

    let val = input.val().trim();
    let name = input.attr('name');

    clearError(input);

    let alphaRegex = /^[A-Za-z ]+$/;

    if(name === 'category_name'){
        if(val === ''){
            showError(input,'Category name required');
            return false;
        }
        if(!alphaRegex.test(val)){
            showError(input,'Only letters allowed');
            return false;
        }
        if(val.length > 30){
            showError(input,'Max 30 characters');
            return false;
        }
    }

    if(name === 'description'){
        if(val !== ''){
            if(val.length < 5){
                showError(input,'Min 5 characters');
                return false;
            }
            if(val.length > 300){
                showError(input,'Max 300 characters');
                return false;
            }
        }
    }

    return true;
}

$('input[name="category_name"]').on('keypress', function(e){
    let char = String.fromCharCode(e.which);
    if(!/[A-Za-z ]/.test(char)){
        e.preventDefault();
    }
});

$('input, textarea').on('keyup change', function(){
    validateField($(this));
});

$('#categoryForm').submit(function(e){
    e.preventDefault();

    let valid = true;
    let firstError = null;

    $('input, textarea').each(function(){
        let ok = validateField($(this));

        if(!ok && valid){
            firstError = $(this);
            valid = false;
        }
    });
if(isDuplicate){
    let input = $('input[name="category_name"]');

    input.addClass('is-invalid');

    $('html, body').animate({
        scrollTop: input.offset().top - 100
    }, 300);

    return;
}

    if(!valid){
        $('html, body').animate({
            scrollTop: firstError.offset().top - 100
        }, 300);
        return;
    }

    $.post('{{ route("inventory.categories.store") }}', $(this).serialize(), function(res){
        if(res.status){
            Swal.fire('Success','Category created','success').then(()=>{
                window.location.href='{{ route("inventory.categories.index") }}';
            });
        }
    });

});
</script>
</x-layout>
<x-layout>
@section('title','Update Category')

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
                            <a href="{{ route('inventory.categories.index') }}" class="text-decoration-none text-dark">
                                Inventory Category
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary">Edit</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('inventory.categories.index') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-arrow-left-circle me-2"></i>Back
            </a>
        </div>

<div class="row justify-content-center">

<!-- LEFT FORM -->
<div class="col-lg-6">

<div class="card border-0 shadow-sm p-4">


<div class="col-12 mt-3 ">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Category Edit</h6>
    </div>
</div>

<form id="categoryForm">
@csrf
@method('PUT')
<div class="row g-4 mt-3">

<div class="mt-2">
    <label class="form-label">
    Category Name <span class="text-danger">*</span>
</label>
<input type="text" name="category_name"
       class="form-control"
       placeholder="Enter category name" value="{{ $category->category_name }}">

<div id="category-check-msg" class="small mt-1"></div>
</div>


       <div class="mt-2">
        <label class="form-label">
    Description
</label>
<textarea name="description"
          class="form-control"
          placeholder="Enter description">{{ $category->description }}</textarea>
       </div>



</div>

</form>

</div>
</div>

<!-- RIGHT ACTION CARD -->
<div class="col-lg-3">

<div class="card border-0 shadow-sm p-4">

<h6 class="fw-bold mb-3 text-primary">Actions</h6>

<div class="d-flex gap-2">
    <button type="submit" form="categoryForm" class="btn btn-success w-100">
        Update
    </button>

    <a href="{{ route('inventory.categories.index') }}" class="btn btn-outline-secondary w-100">
        Cancel
    </a>
</div>

</div>

</div>

</div>
</div>

<script>
let checkTimeout;
let isDuplicate = false;

$('input[name="category_name"]').on('keyup', function(){

    let input = $(this);
    let val = input.val().trim();

    validateField(input);

    clearTimeout(checkTimeout);

    if(val.length < 3) {
        $('#category-check-msg').text('');
        isDuplicate = false;
        return;
    }

    checkTimeout = setTimeout(function(){
$.ajax({
    url: '{{ route("inventory.categories.check") }}',
    type: 'POST',
    data: {
        _token: '{{ csrf_token() }}',
        category_name: val,
        id: {{ $category->id }}
    },
    success: function(res){

        if(res.exists){
            isDuplicate = true;
            $('#category-check-msg').html('<span class="text-danger">Category already exists</span>');
            input.addClass('is-invalid');
        }else{
            isDuplicate = false;
            $('#category-check-msg').html('');
            input.removeClass('is-invalid');
        }

    }
});

    }, 500);

});

function showError(input, message){
    input.addClass('is-invalid');

    if(input.closest('div').find('.error-msg').length === 0){
        let error = $('<div class="text-danger small mt-1 error-msg">'+message+'</div>');
        input.closest('div').append(error);

        setTimeout(()=>{
            error.fadeOut(300, function(){ $(this).remove(); });
            input.removeClass('is-invalid');
        },2000);
    }
}

function clearError(input){
    input.removeClass('is-invalid');
    input.closest('div').find('.error-msg').remove();
}

function validateField(input){

    let val = input.val().trim();
    let name = input.attr('name');

    clearError(input);

    let alphaRegex = /^[A-Za-z ]+$/;

    if(name === 'category_name'){
        if(val === ''){
            showError(input,'Category name required');
            return false;
        }
        if(!alphaRegex.test(val)){
            showError(input,'Only letters allowed');
            return false;
        }
        if(val.length > 30){
            showError(input,'Max 30 characters');
            return false;
        }
    }

    if(name === 'description'){
        if(val !== ''){
            if(val.length < 5){
                showError(input,'Min 5 characters');
                return false;
            }
            if(val.length > 300){
                showError(input,'Max 300 characters');
                return false;
            }
        }
    }

    return true;
}

$('input[name="category_name"]').on('keypress', function(e){
    let char = String.fromCharCode(e.which);
    if(!/[A-Za-z ]/.test(char)){
        e.preventDefault();
    }
});

$('input, textarea').on('keyup change', function(){
    validateField($(this));
});

$('#categoryForm').submit(function(e){
    e.preventDefault();

    let valid = true;
    let firstError = null;

    $('input, textarea').each(function(){
        let ok = validateField($(this));

        if(!ok && valid){
            firstError = $(this);
            valid = false;
        }
    });
if(isDuplicate){
    let input = $('input[name="category_name"]');

    input.addClass('is-invalid');

    $('html, body').animate({
        scrollTop: input.offset().top - 100
    }, 300);

    return;
}

    if(!valid){
        $('html, body').animate({
            scrollTop: firstError.offset().top - 100
        }, 300);
        return;
    }

  $.ajax({
    url: '{{ route("inventory.categories.update", $category->id) }}',
    type: 'PUT',
    data: $(this).serialize(),
    success:function(res){
        if(res.status){
            Swal.fire('Success','Category updated','success').then(()=>{
                window.location.href='{{ route("inventory.categories.index") }}';
            });
        }
    }
});

});
</script>
</x-layout>
<x-layout>
    @section('title', 'Inventory Categories')

    <div class="container-fluid p-4">

        <!-- Header -->
        <div class="d-flex justify-content-between mb-4">
            <h3>Inventory Categories</h3>

            <a href="{{ route('inventory.categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Category
            </a>
        </div>


        <form method="GET" action="{{ route('inventory.categories.index') }}" class="row mb-3">

    <div class="col-md-2">
        <input type="text" name="category_name" value="{{ request('category_name') }}"
               class="form-control" placeholder="Category Name">
    </div>



    <div class="col-md-4">
        <button class="btn btn-primary">Filter</button>
        <a href="{{ route('inventory.categories.index') }}" class="btn btn-secondary">Reset</a>
    </div>

</form>

        <!-- Table -->
        <div class="card shadow-sm">
            <div class="card-body">


        <div class="d-flex  mb-3">

            <form method="GET" action="{{ route('inventory.categories.index') }}" class="d-flex align-items-center gap-2">




                <label class="mb-0">Show</label>

                <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="5" {{ request('per_page')==5?'selected':'' }}>5</option>
                    <option value="10" {{ request('per_page')==10?'selected':'' }}>10</option>
                    <option value="20" {{ request('per_page')==20?'selected':'' }}>20</option>
                    <option value="50" {{ request('per_page')==50?'selected':'' }}>50</option>
                </select>

                <span>entries</span>

                {{-- keep filters --}}
                <input type="hidden" name="category_name" value="{{ request('category_name') }}">


            </form>

        </div>

                <table class="table table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Category Name</th>
            <th>Description</th>
            <th class="text-end">Actions</th>
        </tr>
    </thead>

    <tbody>
        @foreach($categories as $i => $cat)
        <tr>
            <td>{{ $categories->firstItem() + $i }}</td>
            <td>{{ $cat->category_name }}</td>
            <td>{{ $cat->description ?? '-' }}</td>

            <td class="text-end">

                <a href="{{ route('inventory.categories.edit', $cat->id) }}"
                   class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil"></i>
                </a>

                <button class="btn btn-sm btn-danger delete-btn"
                        data-id="{{ $cat->id }}">
                    <i class="bi bi-trash"></i>
                </button>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-between align-items-center mt-3">

    <div>
        Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }}
        of {{ $categories->total() }} entries
    </div>

    <div>
        {{ $categories->links('pagination::bootstrap-5') }}
    </div>

</div>

            </div>
        </div>
    </div>

<script>
$(document).on('click','.delete-btn',function(){

    let id = $(this).data('id');

    Swal.fire({
        title:'Delete Category?',
        icon:'warning',
        showCancelButton:true
    }).then((res)=>{

        if(res.isConfirmed){

            $.ajax({
               url: "{{ route('inventory.categories.destroy', ':id') }}".replace(':id', id),
                type:'DELETE',
                data:{ _token:'{{ csrf_token() }}' },
                success:function(resp){
                    if(resp.status){
                        Swal.fire('Deleted','Success','success').then(()=>{
                            location.reload();
                        });
                    }
                }
            });

        }

    });

});
</script>

</x-layout>
-------------------------------------------
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
------------------------------
item 

<x-layout>
@section('title','Add Inventory Item')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<style>

    #docName {
    max-width: 180px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    text-align: center;
}

    .file-remove {
    position: absolute;
    top: 8px;
    right: 8px;
    background: #dc3545;
    color: #fff;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    text-align: center;
    line-height: 20px;
    font-weight: bold;
    cursor: pointer;
    z-index: 10;
}
.form-control, .form-select{
    min-height: 45px;
    font-size: 14px;
    transition: all 0.2s ease;
}
.form-control:focus, .form-select:focus{
    border-color: var(--primary);
    box-shadow: 0 0 0 0.15rem rgba(40,167,69,0.15);
}
textarea.form-control{
    min-height: 100px;
}

.upload-box {
    border: 2px dashed #cbd5e1;
    border-radius: 10px;
    height: 130px;
    width: 230px;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
}

.upload-box:hover {
    border-color: var(--primary);
    background: #f1fff5;
}

.upload-placeholder {
    text-align: center;
    color: #6c757d;
}

.upload-placeholder i {
    font-size: 30px;
    display: block;
    margin-bottom: 5px;
}



.preview-img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}


.action-card{
    position: sticky;

}
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
                            <a href="{{ route('inventory.index') }}" class="text-decoration-none text-dark">
                                Inventory
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary">Create</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('inventory.index') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-arrow-left-circle me-2"></i>Back
            </a>
        </div>



<div class="d-flex justify-content-center">
    <div class="card border-0 shadow-sm p-4 w-100" style="max-width:1100px;">

<h5 class="mb-4 fw-bold text-primary">Inventory Create</h5>

<form id="itemForm">
@csrf

<div class="row g-4">

<div class="col-12">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Basic Information</h6>
    </div>
</div>

<div class="col-md-4">
<label class="form-label">Item Name <span class="text-danger">*</span></label>
<input type="text" name="item_name" placeholder="Enter item name" class="form-control">
</div>





<div class="col-md-4">
<label class="form-label">Item Code <span class="text-danger">*</span></label>
<input type="text" name="item_code" readonly class="form-control bg-light" placeholder="Auto generated">

<input type="hidden" id="last_id" value="{{ $lastId }}">
</div>


<div class="col-md-4">
<label class="form-label">Category <span class="text-danger">*</span></label>
<select name="category_id" class="form-select select2-category">
<option value="">Select category</option>
@foreach($categories as $cat)
<option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
@endforeach
</select>
</div>

<div class="col-md-4">
    <label class="form-label">Item Type</label>
    <select name="item_type" class="form-select select2-item_type">
        <option value="">Select Item Type</option>
        <option value="new">New</option>
        <option value="refurbished">Refurbished</option>
    </select>
</div>

<div class="col-12 mt-4">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Item Details</h6>
    </div>
</div>

<div class="col-md-4">
    <label class="form-label">Brand</label>
<input type="text" name="brand" placeholder="Brand (e.g. Dell, HP)" class="form-control">
</div>

<div class="col-md-4">
      <label class="form-label">Model Number</label>
<input type="text" name="model_number" placeholder="Model number" class="form-control">
</div>

<div class="col-md-4">
      <label class="form-label">Serial Number</label>
<input type="text" name="serial_number" placeholder="Serial number" class="form-control">
</div>

<div class="col-12 mt-3">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Purchase Details</h6>
    </div>
</div>

<div class="col-md-4">
    <label class="form-label">Purchase Date</label>
<input type="date" name="purchase_date" class="form-control">
</div>

<div class="col-md-4">
      <label class="form-label">Cost</label>
<input type="number" name="purchase_cost" placeholder="Purchase cost" class="form-control">
</div>

<div class="col-md-4">
    <label class="form-label">Vendor Name</label>
<input type="text" name="vendor_name" placeholder="Vendor name" class="form-control">
</div>

<div class="col-md-4">
    <label class="form-label">Invoice Number</label>
<input type="text" name="invoice_number" placeholder="Invoice number" class="form-control">
</div>

<div class="col-md-4">
       <label class="form-label">Warranty Expiry</label>
<input type="date" name="warranty_expiry" class="form-control">
</div>


<div class="col-md-4">
       <label class="form-label">Quantity</label>
<input type="number" name="quantity" placeholder="Total quantity" class="form-control">
</div>




<div class="col-12 mt-3 ">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Media</h6>
    </div>
</div>




<div class="col-md-6">
<label class="form-label">Item Image</label>

<input type="hidden" name="item_image" id="cropped_image_input">

<div class="upload-box position-relative">

    <!-- ❌ Remove button -->
    <span id="removeImage" class="file-remove d-none">×</span>

<img id="preview_image" class="preview-img d-none">
    <div class="upload-placeholder" id="image_placeholder">
        <i class="bi bi-image"></i>
        <p>No Image</p>
    </div>

</div>

<div class="mt-2">
    <button type="button" class="btn btn-sm btn-primary"
            onclick="$('#upload_image').click()">
        Choose Image
    </button>


</div>

<input type="file" id="upload_image" accept="image/*" hidden>
</div>


<div class="col-md-6  mb-3">
<label class="form-label">Document</label>

<div class="upload-box position-relative">

    <span id="removeDoc" class="file-remove d-none">×</span>

    <div class="upload-placeholder" id="doc_placeholder">
        <i class="bi bi-file-earmark-text"></i>
        <p>No File</p>
    </div>

    <div id="docName" class="small text-success"></div>

</div>

<div class="mt-2">
    <button type="button" class="btn btn-sm btn-primary"
            onclick="$('#docInput').click()">
        Choose File
    </button>


</div>

<input type="file" id="docInput" name="document_file"
       accept=".pdf,.doc,.docx" hidden>
</div>

<div class="col-12 mt-3">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Additional Info</h6>
    </div>
</div>
<div class="col-md-6">
     <label class="form-label">Description</label>
<textarea name="description" placeholder="Enter description" class="form-control"></textarea>
</div>

<div class="col-md-6">
     <label class="form-label">Remark</label>
<textarea name="remarks" placeholder="Enter remarks" class="form-control"></textarea>
</div>

</div>





</div>



<!-- RIGHT SIDE ACTION CARD -->
<div class="col-lg-3">

<div class="card  border-0 shadow-sm ms-4 p-4 action-card">

<h6 class="fw-bold mb-3 text-primary">Actions</h6>
<div class="d-flex gap-3">
    <button type="submit" class="btn btn-primary flex-fill py-2">
       Add
    </button>

    <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary flex-fill py-2">
        Cancel
    </a>
</div>
</div>

</div>
</form>


<div class="modal fade" id="cropModal">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">

<div class="modal-header">
    <h5>Crop Image</h5>
    <button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <div class="d-flex justify-content-center">
        <div class="border rounded overflow-hidden" style="width:100%; height:400px;">
            <img id="sample_image" class="w-100 h-100" style="object-fit:contain;">
        </div>
    </div>
</div>

<div class="modal-footer justify-content-between">

    <div class="d-flex gap-2">
        <button type="button" id="zoom_in" class="btn btn-outline-primary">
            <i class="bi bi-zoom-in"></i>
        </button>

        <button type="button" id="zoom_out" class="btn btn-outline-primary">
            <i class="bi bi-zoom-out"></i>
        </button>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button id="crop_btn" class="btn btn-success">Crop</button>
    </div>

</div>

</div>
</div>
</div>


</div>
</div>


<script>
let cropper;
let croppedImage = '';
let zoomLevel = 1;
const maxZoom = 2;
const minZoom = 0.5;

$('#upload_image').change(function(e){
    let file = e.target.files[0];
    if(!file) return;

    if(!file.type.startsWith('image/')){
        alert('Only images allowed');
        $(this).val('');
        return;
    }

    let reader = new FileReader();

    reader.onload = function(e){
        $('#sample_image').attr('src', e.target.result);
        $('#cropModal').modal('show');
    };

    reader.readAsDataURL(file);
});

$('#cropModal').on('shown.bs.modal', function(){

    cropper = new Cropper(document.getElementById('sample_image'), {
        aspectRatio: NaN,
        viewMode: 1,
        autoCropArea: 0.8,
        preview: '.preview',
        dragMode: 'move',
        cropBoxResizable: true,
        cropBoxMovable: true
    });

    $('#zoom_in').off().click(function(){
        if(zoomLevel < maxZoom){
            cropper.zoom(0.1);
            zoomLevel += 0.1;
        }
    });

    $('#zoom_out').off().click(function(){
        if(zoomLevel > minZoom){
            cropper.zoom(-0.1);
            zoomLevel -= 0.1;
        }
    });

}).on('hidden.bs.modal', function(){

    if(cropper){
        cropper.destroy();
        cropper = null;
    }

    zoomLevel = 1;
});

$('#crop_btn').click(function(){

   let canvas = cropper.getCroppedCanvas();

    canvas.toBlob(function(blob){

        let reader = new FileReader();

        reader.onloadend = function(){

            croppedImage = reader.result;

            $('#cropped_image_input').val(croppedImage);

              $('#preview_image')
    .attr('src', croppedImage)
    .removeClass('d-none');

$('#image_placeholder').hide();

            $('#removeImage').removeClass('d-none');

            $('#cropModal').modal('hide');
        };

        reader.readAsDataURL(blob);

    });
});

$('#removeImage').click(function(){


 $('#preview_image').attr('src','').addClass('d-none');
    $('#image_placeholder').show();

    $('#cropped_image_input').val('');
    $('#upload_image').val('');

    $(this).addClass('d-none');
});

$('#docInput').change(function(){

    let file = this.files[0];
    if(!file) return;

    let allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    if(!allowedTypes.includes(file.type)){
        alert('Only PDF, DOC, DOCX allowed');
        $(this).val('');
        return;
    }

    $('#docName').html(`<i class="bi bi-file-earmark text-success"></i> ${file.name}`);

    $('#doc_placeholder').hide();
    $('#removeDoc').removeClass('d-none');
});

$('#removeDoc').click(function(){

    $('#docName').text('');
    $('#doc_placeholder').show();

    $('#docInput').val('');

    $(this).addClass('d-none');
});
</script>

<script>

$(document).ready(function(){

    // Category dropdown
    $('.select2-category').select2({
        placeholder: "Select category",
        allowClear: true,
        width: '100%'
    });

    // Status dropdown
    $('.select2-item_type').select2({
        placeholder: "Select Item Type",
        allowClear: true,
        width: '100%'
    });

});

function showError(input, message){

    input.addClass('is-invalid');

    // 🔥 Handle Select2
    if(input.hasClass('select2-category') || input.hasClass('select2-item_type')){
        input.next('.select2-container').find('.select2-selection')
            .addClass('is-invalid');
    }

    if(input.closest('div').find('.error-msg').length === 0){

        let error = $('<div class="text-danger small mt-1 error-msg">'+message+'</div>');
        input.closest('div').append(error);

        setTimeout(()=>{
            error.fadeOut(500, function(){ $(this).remove(); });

            input.removeClass('is-invalid');

            // remove select2 border
            if(input.hasClass('select2-category') || input.hasClass('select2-item_type')){
                input.next('.select2-container').find('.select2-selection')
                    .removeClass('is-invalid');
            }

        },3000);
    }
}

function clearFieldError(input){

    input.removeClass('is-invalid');
    input.closest('div').find('.error-msg').remove();

    // 🔥 remove select2 border
    if(input.hasClass('select2-category') || input.hasClass('select2-item_type')){
        input.next('.select2-container').find('.select2-selection')
            .removeClass('is-invalid');
    }
}
function scrollToField(el){
    $('html, body').animate({
        scrollTop: el.offset().top - 120
    }, 400);
}

function validateField(input){

    let val = input.val().trim();
    let name = input.attr('name');

    clearFieldError(input);

    let nameRegex = /^[a-zA-Z0-9 ]+$/;
    let alphaRegex = /^[a-zA-Z ]+$/;

    if(name === 'item_name'){
        if(val=='' || val.length < 5 || val.length > 30 || !nameRegex.test(val)){
            showError(input,'Item name 5-30 chars (letters & numbers)');
            return false;
        }
    }

    if(name === 'item_code'){
        if(val==''){
            showError(input,'Item code required');
            return false;
        }
    }

    if(name === 'category_id'){
        if(val==''){
            showError(input,'Category required');
            return false;
        }
    }

    if(name === 'brand'){
        if(val!='' && (val.length < 5 || val.length > 30)){
            showError(input,'Brand 5-30 chars');
            return false;
        }
    }

    if(name === 'model_number'){
        if(val!='' && val.length > 40){
            showError(input,'Max 40 chars');
            return false;
        }
    }

    if(name === 'serial_number'){
        if(val!='' && val.length > 40){
            showError(input,'Max 40 chars');
            return false;
        }
    }

    if(name === 'vendor_name'){
        if(val!='' && (!alphaRegex.test(val) || val.length < 5 || val.length > 30)){
            showError(input,'Vendor 5-30 letters only');
            return false;
        }
    }

    if(name === 'invoice_number'){
        if(val!='' && val.length > 40){
            showError(input,'Max 40 chars');
            return false;
        }
    }

    if(name === 'description'){
        if(val!='' && (val.length < 5 || val.length > 100)){
            showError(input,'5-30 chars required');
            return false;
        }
    }

    if(name === 'warranty_expiry'){

    let purchase_date = $('input[name="purchase_date"]').val();

    if(val !== '' && purchase_date !== ''){

        let p = new Date(purchase_date);
        let w = new Date(val);

        if(w <= p){
            showError(input,'Warranty must be after purchase date');
            return false;
        }
    }
}

    if(name === 'remarks'){
        if(val!='' && (val.length < 5 || val.length > 100)){
            showError(input,'5-30 chars required');
            return false;
        }
    }

    return true;
}
$('input[name="purchase_date"], input[name="warranty_expiry"]').on('change', function(){

    validateField($('input[name="purchase_date"]'));
    validateField($('input[name="warranty_expiry"]'));

});

$('input, textarea, select').on('keyup change', function(){
    validateField($(this));
});

$('#itemForm').submit(function(e){
    e.preventDefault();

    let valid = true;
    let firstError = null;

    $('input, textarea, select').each(function(){

        let fieldValid = validateField($(this));

        if(!fieldValid && valid){
            firstError = $(this);
            valid = false;
        }

    });

    let purchase_date = $('input[name="purchase_date"]').val();
    let warranty = $('input[name="warranty_expiry"]').val();

    if(warranty && purchase_date){
        let p = new Date(purchase_date);
        let w = new Date(warranty);

        if(w <= p){
            let field = $('input[name="warranty_expiry"]');
            showError(field,'Warranty must be after purchase date');

            if(valid){
                firstError = field;
                valid = false;
            }
        }
    }

    if(!valid){
        scrollToField(firstError);
        return;
    }

    let formData = new FormData(this);
  formData.append('item_image', croppedImage);

    $.ajax({
        url:'{{ route("inventory.store") }}',
        type:'POST',
        data:formData,
        contentType:false,
        processData:false,
        success:function(res){
            if(res.status){
                Swal.fire('Success','Inventory item created successfully','success').then(()=>{
                    window.location.href='{{ route("inventory.index") }}';
                });
            }
        }
    });

});



$(document).ready(function(){

    let lastId = parseInt($('#last_id').val()) || 0;

    let next = lastId + 1;

    let year = new Date().getFullYear();

    let code = 'ITM-' + year + '-' + String(next).padStart(4,'0');

    $('input[name="item_code"]').val(code);

});
</script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</x-layout>
<x-layout>
@section('title','Update Inventory Item')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<style>

    #docName {
    max-width: 180px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    text-align: center;
}

    .file-remove {
    position: absolute;
    top: 8px;
    right: 8px;
    background: #dc3545;
    color: #fff;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    text-align: center;
    line-height: 20px;
    font-weight: bold;
    cursor: pointer;
    z-index: 10;
}
.form-control, .form-select{
    min-height: 45px;
    font-size: 14px;
    transition: all 0.2s ease;
}
.form-control:focus, .form-select:focus{
    border-color: var(--primary);
    box-shadow: 0 0 0 0.15rem rgba(40,167,69,0.15);
}
textarea.form-control{
    min-height: 100px;
}

.upload-box {
    border: 2px dashed #cbd5e1;
    border-radius: 10px;
    height: 130px;
    width: 230px;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
}

.upload-box:hover {
    border-color: var(--primary);
    background: #f1fff5;
}

.upload-placeholder {
    text-align: center;
    color: #6c757d;
}

.upload-placeholder i {
    font-size: 30px;
    display: block;
    margin-bottom: 5px;
}



.preview-img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}


.action-card{
    position: sticky;

}
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
                            <a href="{{ route('inventory.index') }}" class="text-decoration-none text-dark">
                                Inventory
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-primary">Edit</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('inventory.index') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-arrow-left-circle me-2"></i>Back
            </a>
        </div>



<div class="d-flex justify-content-center">
    <div class="card border-0 shadow-sm p-4 w-100" style="max-width:1100px;">

<h5 class="mb-4 fw-bold text-primary">Inventory Edit</h5>

<form id="itemForm">
@csrf

<div class="row g-4">

<div class="col-12">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Basic Information</h6>
    </div>
</div>

<div class="col-md-4">
<label class="form-label">Item Name <span class="text-danger">*</span></label>
<input type="text" name="item_name" placeholder="Enter item name" class="form-control" value="{{ $item->item_name }}">
</div>





<div class="col-md-4">
<label class="form-label">Item Code <span class="text-danger">*</span></label>
<input type="text" name="item_code" readonly class="form-control bg-light" placeholder="Auto generated"  value="{{ $item->item_code }}">

</div>


<div class="col-md-4">
<label class="form-label">Category <span class="text-danger">*</span></label>
<select name="category_id" class="form-select select2-category">
<option value="">Select category</option>
@foreach($categories as $cat)
<option value="{{ $cat->id }}"
    {{ $item->category_id == $cat->id ? 'selected' : '' }}>
    {{ $cat->category_name }}
</option>
@endforeach
</select>
</div>

<div class="col-md-4">
    <label class="form-label">Item Type</label>
    <select name="item_type" class="form-select select2-item_type">
        <option value="">Select Item Type</option>
        <option value="new" {{ $item->item_type=='new' ? 'selected' : '' }}>New</option>
        <option value="refurbished" {{ $item->item_type=='refurbished' ? 'selected' : '' }}>Refurbished</option>
    </select>
</div>

<div class="col-12 mt-4">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Item Details</h6>
    </div>
</div>

<div class="col-md-4">
    <label class="form-label">Brand</label>
<input type="text" name="brand" placeholder="Brand (e.g. Dell, HP)" class="form-control" value="{{ $item->brand }}">
</div>

<div class="col-md-4">
      <label class="form-label">Model Number</label>
<input type="text" name="model_number" placeholder="Model number" class="form-control"  value="{{ $item->model_number }}">
</div>

<div class="col-md-4">
      <label class="form-label">Serial Number</label>
<input type="text" name="serial_number" placeholder="Serial number" class="form-control"  value="{{ $item->serial_number }}">
</div>

<div class="col-12 mt-3">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Purchase Details</h6>
    </div>
</div>

<div class="col-md-4">
    <label class="form-label">Purchase Date</label>
<input type="date" name="purchase_date" class="form-control"  value="{{ $item->purchase_date }}">
</div>

<div class="col-md-4">
      <label class="form-label">Cost</label>
<input type="number" name="purchase_cost" placeholder="Purchase cost" class="form-control"  value="{{ $item->purchase_cost }}">
</div>

<div class="col-md-4">
    <label class="form-label">Vendor Name</label>
<input type="text" name="vendor_name" placeholder="Vendor name" class="form-control" value="{{ $item->vendor_name }}">
</div>

<div class="col-md-4">
    <label class="form-label">Invoice Number</label>
<input type="text" name="invoice_number" placeholder="Invoice number" class="form-control" value="{{ $item->invoice_number }}">
</div>

<div class="col-md-4">
       <label class="form-label">Warranty Expiry</label>
<input type="date" name="warranty_expiry" class="form-control" value="{{ $item->warranty_expiry }}">
</div>

<div class="col-md-4">
       <label class="form-label">Quantity</label>
<input type="number" name="quantity" placeholder="Total quantity" class="form-control" value="{{ $item->quantity }}">
</div>







<div class="col-12 mt-3 ">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Media</h6>
    </div>
</div>




<div class="col-md-6">
<label class="form-label">Item Image</label>

<input type="hidden" name="item_image" id="cropped_image_input">

<div class="upload-box position-relative">

<span id="removeImage" class="file-remove {{ $item->item_image ? '' : 'd-none' }}">×</span>
<img id="preview_image"
     src="{{ $item->item_image ? asset('inventory_images/'.$item->item_image) : '' }}"
     class="preview-img {{ $item->item_image ? '' : 'd-none' }}">


    <div class="upload-placeholder {{ $item->item_image ? 'd-none' : '' }}" id="image_placeholder">
        <i class="bi bi-image"></i>
        <p>No Image</p>
    </div>

</div>

<div class="mt-2">
    <button type="button" class="btn btn-sm btn-primary"
            onclick="$('#upload_image').click()">
        Choose Image
    </button>


</div>

<input type="file" id="upload_image" accept="image/*" hidden>
</div>


<div class="col-md-6 mb-3">
<label class="form-label">Document</label>

<div class="upload-box position-relative">

    <span id="removeDoc"
          class="file-remove {{ $item->document_file ? '' : 'd-none' }}">×</span>

    <div id="doc_placeholder"
         class="upload-placeholder {{ $item->document_file ? 'd-none' : '' }}">
        <i class="bi bi-file-earmark-text"></i>
        <p>No File</p>
    </div>

    <div id="docName" class="small text-success">
        {{ $item->document_file }}
    </div>

</div>

<div class="mt-2">
    <button type="button" class="btn btn-sm btn-primary"
            onclick="$('#docInput').click()">
        Choose File
    </button>
</div>

<input type="file" id="docInput" name="document_file"
       accept=".pdf,.doc,.docx" hidden>
</div>

<div class="col-12 mt-3">
    <div class="bg-light rounded-3 p-3 mb-2">
        <h6 class="fw-bold text-primary mb-0">Additional Info</h6>
    </div>
</div>
<div class="col-md-6">
     <label class="form-label">Description</label>
<textarea name="description" placeholder="Enter description" class="form-control">{{ $item->description }}</textarea>
</div>

<div class="col-md-6">
     <label class="form-label">Remark</label>
<textarea name="remarks" placeholder="Enter remarks" class="form-control">{{ $item->remarks }}</textarea>
</div>

</div>





</div>



<!-- RIGHT SIDE ACTION CARD -->
<div class="col-lg-3">

<div class="card  border-0 shadow-sm ms-4 p-4 action-card">

<h6 class="fw-bold mb-3 text-primary">Actions</h6>
<div class="d-flex gap-3">
    <button type="submit" class="btn btn-primary flex-fill py-2">
    Update
    </button>

    <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary flex-fill py-2">
        Cancel
    </a>
</div>
</div>

</div>
</form>


<div class="modal fade" id="cropModal">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">

<div class="modal-header">
    <h5>Crop Image</h5>
    <button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <div class="d-flex justify-content-center">
        <div class="border rounded overflow-hidden" style="width:100%; height:400px;">
            <img id="sample_image" class="w-100 h-100" style="object-fit:contain;">
        </div>
    </div>
</div>

<div class="modal-footer justify-content-between">

    <div class="d-flex gap-2">
        <button type="button" id="zoom_in" class="btn btn-outline-primary">
            <i class="bi bi-zoom-in"></i>
        </button>

        <button type="button" id="zoom_out" class="btn btn-outline-primary">
            <i class="bi bi-zoom-out"></i>
        </button>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button id="crop_btn" class="btn btn-success">Crop</button>
    </div>

</div>

</div>
</div>
</div>


</div>
</div>


<script>
let cropper;
let croppedImage = '';
let zoomLevel = 1;
const maxZoom = 2;
const minZoom = 0.5;

$('#upload_image').change(function(e){
    let file = e.target.files[0];
    if(!file) return;

    if(!file.type.startsWith('image/')){
        alert('Only images allowed');
        $(this).val('');
        return;
    }

    let reader = new FileReader();

    reader.onload = function(e){
        $('#sample_image').attr('src', e.target.result);
        $('#cropModal').modal('show');
    };

    reader.readAsDataURL(file);
});

$('#cropModal').on('shown.bs.modal', function(){

    cropper = new Cropper(document.getElementById('sample_image'), {
        aspectRatio: NaN,
        viewMode: 1,
        autoCropArea: 0.8,
        preview: '.preview',
        dragMode: 'move',
        cropBoxResizable: true,
        cropBoxMovable: true
    });

    $('#zoom_in').off().click(function(){
        if(zoomLevel < maxZoom){
            cropper.zoom(0.1);
            zoomLevel += 0.1;
        }
    });

    $('#zoom_out').off().click(function(){
        if(zoomLevel > minZoom){
            cropper.zoom(-0.1);
            zoomLevel -= 0.1;
        }
    });

}).on('hidden.bs.modal', function(){

    if(cropper){
        cropper.destroy();
        cropper = null;
    }

    zoomLevel = 1;
});

$('#crop_btn').click(function(){

   let canvas = cropper.getCroppedCanvas();

    canvas.toBlob(function(blob){

        let reader = new FileReader();

        reader.onloadend = function(){

            croppedImage = reader.result;

            $('#cropped_image_input').val(croppedImage);

              $('#preview_image')
    .attr('src', croppedImage)
    .removeClass('d-none');

$('#image_placeholder').hide();

            $('#removeImage').removeClass('d-none');

            $('#cropModal').modal('hide');
        };

        reader.readAsDataURL(blob);

    });
});

$('#removeImage').click(function(){


 $('#preview_image').attr('src','').addClass('d-none');
    $('#image_placeholder').show();

    $('#cropped_image_input').val('');
    $('#upload_image').val('');

    $(this).addClass('d-none');
});

$('#docInput').change(function(){

    let file = this.files[0];
    if(!file) return;

    let allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    if(!allowedTypes.includes(file.type)){
        alert('Only PDF, DOC, DOCX allowed');
        $(this).val('');
        return;
    }

    $('#docName').html(`<i class="bi bi-file-earmark text-success"></i> ${file.name}`);

    $('#doc_placeholder').hide();
    $('#removeDoc').removeClass('d-none');
});

$('#removeDoc').click(function(){

    $('#docName').text('');
    $('#doc_placeholder').show();

    $('#docInput').val('');

    $(this).addClass('d-none');
});
</script>

<script>

$(document).ready(function(){

    // Category dropdown
    $('.select2-category').select2({
        placeholder: "Select category",
        allowClear: true,
        width: '100%'
    });

    // Status dropdown
    $('.select2-item_type').select2({
        placeholder: "Select Item Type",
        allowClear: true,
        width: '100%'
    });

});

function showError(input, message){

    input.addClass('is-invalid');

    // 🔥 Handle Select2
    if(input.hasClass('select2-category') || input.hasClass('select2-item_type')){
        input.next('.select2-container').find('.select2-selection')
            .addClass('is-invalid');
    }

    if(input.closest('div').find('.error-msg').length === 0){

        let error = $('<div class="text-danger small mt-1 error-msg">'+message+'</div>');
        input.closest('div').append(error);

        setTimeout(()=>{
            error.fadeOut(500, function(){ $(this).remove(); });

            input.removeClass('is-invalid');

            // remove select2 border
            if(input.hasClass('select2-category') || input.hasClass('select2-item_type')){
                input.next('.select2-container').find('.select2-selection')
                    .removeClass('is-invalid');
            }

        },3000);
    }
}

function clearFieldError(input){

    input.removeClass('is-invalid');
    input.closest('div').find('.error-msg').remove();

    // 🔥 remove select2 border
    if(input.hasClass('select2-category') || input.hasClass('select2-item_type')){
        input.next('.select2-container').find('.select2-selection')
            .removeClass('is-invalid');
    }
}
function scrollToField(el){
    $('html, body').animate({
        scrollTop: el.offset().top - 120
    }, 400);
}

function validateField(input){

    let val = input.val().trim();
    let name = input.attr('name');

    clearFieldError(input);

    let nameRegex = /^[a-zA-Z0-9 ]+$/;
    let alphaRegex = /^[a-zA-Z ]+$/;

    if(name === 'item_name'){
        if(val=='' || val.length < 5 || val.length > 30 || !nameRegex.test(val)){
            showError(input,'Item name 5-30 chars (letters & numbers)');
            return false;
        }
    }

    if(name === 'item_code'){
        if(val==''){
            showError(input,'Item code required');
            return false;
        }
    }

    if(name === 'category_id'){
        if(val==''){
            showError(input,'Category required');
            return false;
        }
    }

    if(name === 'brand'){
        if(val!='' && (val.length < 5 || val.length > 30)){
            showError(input,'Brand 5-30 chars');
            return false;
        }
    }

    if(name === 'model_number'){
        if(val!='' && val.length > 40){
            showError(input,'Max 40 chars');
            return false;
        }
    }

    if(name === 'serial_number'){
        if(val!='' && val.length > 40){
            showError(input,'Max 40 chars');
            return false;
        }
    }

    if(name === 'vendor_name'){
        if(val!='' && (!alphaRegex.test(val) || val.length < 5 || val.length > 30)){
            showError(input,'Vendor 5-30 letters only');
            return false;
        }
    }

    if(name === 'invoice_number'){
        if(val!='' && val.length > 40){
            showError(input,'Max 40 chars');
            return false;
        }
    }

    if(name === 'description'){
        if(val!='' && (val.length < 5 || val.length > 100)){
            showError(input,'5-30 chars required');
            return false;
        }
    }

    if(name === 'warranty_expiry'){

    let purchase_date = $('input[name="purchase_date"]').val();

    if(val !== '' && purchase_date !== ''){

        let p = new Date(purchase_date);
        let w = new Date(val);

        if(w <= p){
            showError(input,'Warranty must be after purchase date');
            return false;
        }
    }
}

    if(name === 'remarks'){
        if(val!='' && (val.length < 5 || val.length > 100)){
            showError(input,'5-30 chars required');
            return false;
        }
    }

    return true;
}
$('input[name="purchase_date"], input[name="warranty_expiry"]').on('change', function(){

    validateField($('input[name="purchase_date"]'));
    validateField($('input[name="warranty_expiry"]'));

});

$('input, textarea, select').on('keyup change', function(){
    validateField($(this));
});

$('#itemForm').submit(function(e){
    e.preventDefault();

    let valid = true;
    let firstError = null;

    $('input, textarea, select').each(function(){

        let fieldValid = validateField($(this));

        if(!fieldValid && valid){
            firstError = $(this);
            valid = false;
        }

    });

    let purchase_date = $('input[name="purchase_date"]').val();
    let warranty = $('input[name="warranty_expiry"]').val();

    if(warranty && purchase_date){
        let p = new Date(purchase_date);
        let w = new Date(warranty);

        if(w <= p){
            let field = $('input[name="warranty_expiry"]');
            showError(field,'Warranty must be after purchase date');

            if(valid){
                firstError = field;
                valid = false;
            }
        }
    }

    if(!valid){
        scrollToField(firstError);
        return;
    }

    let formData = new FormData(this);
    formData.append('_method','PUT');
if(croppedImage){
    formData.append('item_image', croppedImage);
}
    $.ajax({
       url:'{{ route("inventory.update", $item->id) }}',
type:'POST',
        data:formData,
        contentType:false,
        processData:false,
        success:function(res){
            if(res.status){
                Swal.fire('Success','Inventory item updated successfully','success').then(()=>{
                    window.location.href='{{ route("inventory.index") }}';
                });
            }
        }
    });

});




</script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }

        /* Header Styles */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2c7da0;
        }

        .company-logo {
            max-height: 60px;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #2c7da0;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0 5px;
        }

        .report-info {
            font-size: 9px;
            color: #666;
            margin-bottom: 3px;
        }

        /* Summary Cards */
        .summary-container {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .summary-card {
            flex: 1;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            border: 1px solid #e0e0e0;
        }

        .summary-card h4 {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }

        .summary-card .value {
            font-size: 18px;
            font-weight: bold;
            color: #2c7da0;
        }

        .summary-card .label {
            font-size: 9px;
            color: #888;
        }

        /* Category Summary Table */
        .sub-summary {
            margin-bottom: 20px;
        }

        .sub-summary h4 {
            font-size: 12px;
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
            padding: 6px 8px;
            text-align: left;
        }

        .summary-table th {
            background-color: #e9ecef;
            font-weight: bold;
            font-size: 9px;
        }

        .summary-table td {
            font-size: 9px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .items-table th {
            background-color: #2c7da0;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
        }

        .items-table td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 8px;
        }

        .items-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .items-table tr:hover {
            background-color: #f1f3f5;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            text-align: center;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #eee;
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 7px;
            font-weight: bold;
        }

        .badge-new {
            background-color: #28a745;
            color: white;
        }

        .badge-refurbished {
            background-color: #17a2b8;
            color: white;
        }

        /* Page break */
        .page-break {
            page-break-before: always;
        }

        /* Text alignment */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
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

    <!-- Main Summary Cards -->
    <div class="summary-container">
        <div class="summary-card">
            <h4>Total Items</h4>
            <div class="value">{{ $total_items }}</div>
            <div class="label">Inventory Items</div>
        </div>
        <div class="summary-card">
            <h4>Total Quantity</h4>
            <div class="value">{{ number_format($total_quantity) }}</div>
            <div class="label">Units in Stock</div>
        </div>
        <div class="summary-card">
            <h4>Total Cost</h4>
            <div class="value">₹ {{ number_format($total_cost, 2) }}</div>
            <div class="label">Purchase Value</div>
        </div>
        <div class="summary-card">
            <h4>Average Cost</h4>
            <div class="value">₹ {{ $total_items > 0 ? number_format($total_cost / $total_items, 2) : '0.00' }}</div>
            <div class="label">Per Item Average</div>
        </div>
    </div>

    <!-- Category Summary -->
    <div class="sub-summary">
        <h4>Summary by Category</h4>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="text-right">Items Count</th>
                    <th class="text-right">Total Quantity</th>
                    <th class="text-right">Total Cost (₹)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories_summary as $categoryName => $summary)
                <tr>
                    <td>{{ $categoryName }}</td>
                    <td class="text-right">{{ $summary['count'] }}</td>
                    <td class="text-right">{{ number_format($summary['total_quantity']) }}</td>
                    <td class="text-right">₹ {{ number_format($summary['total_cost'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Item Type Summary -->
    <div class="sub-summary">
        <h4>Summary by Item Type</h4>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Item Type</th>
                    <th class="text-right">Items Count</th>
                    <th class="text-right">Total Quantity</th>
                    <th class="text-right">Total Cost (₹)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($item_type_summary as $type => $summary)
                @if($summary['count'] > 0)
                <tr>
                    <td>{{ ucfirst($type) }}</td>
                    <td class="text-right">{{ $summary['count'] }}</td>
                    <td class="text-right">{{ number_format($summary['total_quantity']) }}</td>
                    <td class="text-right">₹ {{ number_format($summary['total_cost'], 2) }}</td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Items Details Table -->
    <div class="sub-summary">
        <h4>Item Details</h4>
        <table class="items-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Item Name</th>
                    <th>Item Code</th>
                    <th>Category</th>
                    <th>Item Type</th>
                    <th>Brand</th>
                    <th>Model No.</th>
                    <th>Serial No.</th>
                    <th>Quantity</th>
                    <th>Cost (₹)</th>
                    <th>Purchase Date</th>
                    <th>Vendor</th>
                    <th>Warranty Expiry</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">
                        @if($item->item_image && file_exists(public_path('inventory_images/'.$item->item_image)))
                            ✓
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->item_code }}</td>
                    <td>{{ $item->category->category_name ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge {{ $item->item_type == 'new' ? 'badge-new' : 'badge-refurbished' }}">
                            {{ ucfirst($item->item_type) }}
                        </span>
                    </td>
                    <td>{{ $item->brand ?? '-' }}</td>
                    <td>{{ $item->model_number ?? '-' }}</td>
                    <td>{{ $item->serial_number ?? '-' }}</td>
                    <td class="text-right">{{ number_format($item->quantity) }}</td>
                    <td class="text-right">₹ {{ number_format($item->purchase_cost, 2) }}</td>
                    <td>{{ $item->purchase_date ? \Carbon\Carbon::parse($item->purchase_date)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $item->vendor_name ?? '-' }}</td>
                    <td>{{ $item->warranty_expiry ? \Carbon\Carbon::parse($item->warranty_expiry)->format('d-m-Y') : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>This is a system-generated report. For any discrepancies, please contact the inventory department.</p>
        <p>RAIYAAN INFOTECH - Inventory Management System</p>
    </div>

</body>
</html>
<x-layout>
    @section('title', 'Inventory Items')

    <div class="container-fluid p-4">

        <!-- Header -->
       <!-- Add this button next to the "Add Item" button -->
<div class="d-flex justify-content-between mb-4">
    <h3>Inventory Items</h3>
    <div>
        <button type="button" class="btn btn-success me-2" id="exportInventoryBtn">
            <i class="bi bi-file-pdf"></i> Export PDF
        </button>
        <a href="{{ route('inventory.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Item
        </a>
    </div>
</div>

        <form method="GET" action="{{ route('inventory.index') }}" class="row mb-3">

    <div class="col-md-3">
        <input type="text" name="item_name" value="{{ request('item_name') }}"
               class="form-control" placeholder="Item Name">
    </div>

    <div class="col-md-2">
        <input type="text" name="item_code" value="{{ request('item_code') }}"
               class="form-control" placeholder="Item Code">
    </div>

    <div class="col-md-2">
        <select name="category_id" class="form-control">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                    {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->category_name }}
                </option>
            @endforeach
        </select>
    </div>

  <div class="col-md-2">
    <select name="item_type" class="form-control">
        <option value="">All Item Type</option>
        <option value="new" {{ request('item_type')=='new' ? 'selected' : '' }}>New</option>
        <option value="refurbished" {{ request('item_type')=='refurbished' ? 'selected' : '' }}>Refurbished</option>
    </select>
</div>

    <div class="col-md-3">
        <button class="btn btn-primary">Filter</button>
        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Reset</a>
    </div>

</form>

        <!-- Table -->
        <div class="card shadow-sm">
            <div class="card-body">


<div class="d-flex  mb-3">

    <form method="GET" action="{{ route('inventory.index') }}" class="d-flex align-items-center gap-2">

 <label class="mb-0">Show</label>

        <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
            <option value="5" {{ request('per_page')==5?'selected':'' }}>5</option>
            <option value="10" {{ request('per_page')==10?'selected':'' }}>10</option>
            <option value="20" {{ request('per_page')==20?'selected':'' }}>20</option>
            <option value="50" {{ request('per_page')==50?'selected':'' }}>50</option>
        </select>

        <span>entries</span>


        {{-- KEEP FILTER VALUES --}}
        <input type="hidden" name="item_name" value="{{ request('item_name') }}">
        <input type="hidden" name="item_code" value="{{ request('item_code') }}">
        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
        <input type="hidden" name="item_type" value="{{ request('item_type') }}">



    </form>

</div>


                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Item Name</th>
                            <th>Item Code</th>
                            <th>Category</th>
                            <th>Item Type</th>
                            <th class="">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($items as $i => $item)
                        <tr>
                          <td>{{ $items->firstItem() + $i }}</td>
                            <!-- IMAGE -->
                           <td>
   <img src="{{ asset('inventory_images/'.$item->item_image) }}"
     width="40" height="40"
     class="rounded-circle item-img"
     style="object-fit: cover;"
     onerror="this.onerror=null; this.src='/images/placeholder.jpg';">
</td>

                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->item_code }}</td>
                            <td>{{ $item->category->category_name ?? '-' }}</td>

                           <td>
    <span class="badge p-2
        {{ $item->item_type == 'new' ? 'bg-primary' :
           ($item->item_type == 'refurbished' ? 'bg-info' : 'bg-secondary') }}">
        {{ ucfirst($item->item_type) }}
    </span>
</td>

                            <td class="">

                                <!-- VIEW -->
         <button class="btn btn-sm btn-info view-btn"
    data-name="{{ $item->item_name }}"
    data-code="{{ $item->item_code }}"
    data-category="{{ $item->category->category_name ?? '-' }}"
    data-brand="{{ $item->brand }}"
    data-model="{{ $item->model_number }}"
    data-serial="{{ $item->serial_number }}"
    data-purchase_date="{{ $item->purchase_date }}"
    data-cost="{{ $item->purchase_cost }}"
    data-vendor="{{ $item->vendor_name }}"
    data-invoice="{{ $item->invoice_number }}"
    data-warranty="{{ $item->warranty_expiry }}"
    data-qty="{{ $item->quantity }}"
    data-item_type="{{ $item->item_type }}"
    data-desc="{{ $item->description }}"
    data-remarks="{{ $item->remarks }}"
data-image="{{
    (!empty($item->item_image) && file_exists(public_path('inventory_images/'.$item->item_image)))
    ? asset('inventory_images/'.$item->item_image)
    : asset('images/placeholder.jpg')
}}"
    data-bs-toggle="modal"
    data-bs-target="#billModal">
    <i class="bi bi-eye"></i>
</button>
                                <!-- EDIT -->
                                <a href="{{ route('inventory.edit',$item->id) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <!-- DELETE -->
                                <button class="btn btn-sm btn-danger delete-btn"
                                        data-id="{{ $item->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>


                <div class="d-flex justify-content-between align-items-center mt-3">

    <div>
        Showing {{ $items->firstItem() }} to {{ $items->lastItem() }}
        of {{ $items->total() }} entries
    </div>

    <div>
        {{ $items->links() }}
    </div>

</div>

            </div>
        </div>
    </div>

<div class="modal fade" id="billModal">
<div class="modal-dialog modal-xl">
<div class="modal-content border-0 shadow-lg rounded-4 p-4">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
    <div>
        <h4 class="fw-bold mb-0">Inventory Invoice</h4>
        <small class="text-muted">System Generated</small>
    </div>
    <span class="badge bg-success px-3 py-2" id="b_item_type"></span>
</div>

<div class="row">

<!-- LEFT SIDE -->
<div class="col-md-8">

    <!-- BASIC -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
        <h6 class="fw-bold mb-3 text-primary">Item Info</h6>

        <div class="row">
            <div class="col-md-6">
                <p><b>Name:</b> <span id="b_name"></span></p>
                <p><b>Code:</b> <span id="b_code"></span></p>
                <p><b>Category:</b> <span id="b_category"></span></p>
            </div>

            <div class="col-md-6">
                <p><b>Brand:</b> <span id="b_brand"></span></p>
                <p><b>Model:</b> <span id="b_model"></span></p>
                <p><b>Serial:</b> <span id="b_serial"></span></p>
            </div>
        </div>
    </div>

    <!-- PURCHASE -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
        <h6 class="fw-bold mb-3 text-primary">Purchase Details</h6>

        <div class="row">
            <div class="col-md-4"><b>Date:</b> <span id="b_purchase_date"></span></div>
            <div class="col-md-4"><b>Vendor:</b> <span id="b_vendor"></span></div>
            <div class="col-md-4"><b>Invoice:</b> <span id="b_invoice"></span></div>
        </div>
    </div>

    <!-- STOCK TABLE -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
        <h6 class="fw-bold mb-3 text-primary">Stock</h6>

        <table class="table table-borderless text-center align-middle">
            <thead class="bg-light rounded">
                <tr>
                    <th>Qty</th>
                    <th>Cost (₹)</th>
                </tr>
            </thead>
            <tbody>
                <tr class="fw-bold">
                    <td id="b_qty"></td>
                    <td>₹ <span id="b_cost"></span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- DESCRIPTION -->
    <div class="card border-0 shadow-sm rounded-4 p-3">
        <h6 class="fw-bold text-primary">Notes</h6>
        <p id="b_desc" class="mb-2"></p>
        <p id="b_remarks" class="text-muted"></p>
    </div>

</div>

<!-- RIGHT SIDE -->
<div class="col-md-4">

    <!-- IMAGE -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3 text-center">
        <h6 class="fw-bold text-primary mb-2">Item Image</h6>
        <img id="b_image" class="img-fluid rounded-3" style="max-height:200px; object-fit:cover;">
    </div>

    <!-- DOCUMENT -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3 text-center">
        <h6 class="fw-bold text-primary mb-2">Document</h6>

        <a id="b_doc" target="_blank" class="btn btn-outline-primary btn-sm">
            View Document
        </a>
    </div>

    <!-- SUMMARY -->
    <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-light">
        <h6 class="fw-bold text-primary">Total</h6>
        <h3 class="fw-bold">₹ <span id="b_cost_total"></span></h3>
        <small>Warranty till <span id="b_warranty"></span></small>
    </div>

</div>

</div>

<!-- FOOTER -->
<div class="text-end mt-4">
    <button onclick="window.print()" class="btn btn-success">
        🖨 Print
    </button>
</div>

</div>
</div>
</div>







<!-- Export Inventory Modal -->
<div class="modal fade" id="exportInventoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Inventory Report (PDF)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Export Type - Multiple Categories / All -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Export Type</label>
                    <div class="d-flex gap-4 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="export_type" id="exportAllRadio" value="all" checked>
                            <label class="form-check-label" for="exportAllRadio">All Items</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="export_type" id="exportCategoryRadio" value="category">
                            <label class="form-check-label" for="exportCategoryRadio">By Category</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="export_type" id="exportItemTypeRadio" value="item_type">
                            <label class="form-check-label" for="exportItemTypeRadio">By Item Type</label>
                        </div>
                    </div>
                </div>

                <!-- Categories Dropdown (shown when Category is selected) -->
                <div class="mb-3 d-none" id="exportCategoryDiv">
                    <label class="form-label fw-bold">Categories</label>
                    <div class="dropdown w-100">
                        <button class="form-select text-start" type="button" id="exportCategoryDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: white; text-align: left;">
                            <span id="exportCategoryText">Select Categories</span>
                        </button>
                        <ul class="dropdown-menu p-2 w-100" aria-labelledby="exportCategoryDropdownBtn" style="max-height: 300px; overflow-y: auto;">
                            <li>
                                <input type="text" class="form-control form-control-sm mb-2" placeholder="Search categories..." id="exportCategorySearchInput">
                            </li>
                            <li class="d-flex justify-content-between px-2 mb-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="exportSelectAllCategories">Select All</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="exportDeselectAllCategories">Deselect All</button>
                            </li>
                            <li>
                                <select id="exportCategorySelectList" class="form-select form-select-sm" size="6" multiple style="border: none;">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Item Type Dropdown (shown when Item Type is selected) -->
                <div class="mb-3 d-none" id="exportItemTypeDiv">
                    <label class="form-label fw-bold">Item Type</label>
                    <select id="exportItemTypeSelect" class="form-select">
                        <option value="">Select Item Type</option>
                        <option value="new">New</option>
                        <option value="refurbished">Refurbished</option>
                    </select>
                </div>

                <!-- Sort By Option -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Sort By</label>
                    <select id="exportSortBy" class="form-select">
                        <option value="item_name">Item Name</option>
                        <option value="item_code">Item Code</option>
                        <option value="category">Category</option>
                        <option value="purchase_date">Purchase Date</option>
                        <option value="purchase_cost">Cost</option>
                    </select>
                </div>

                <!-- Sort Order -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Sort Order</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort_order" id="sortAsc" value="asc" checked>
                            <label class="form-check-label" for="sortAsc">Ascending</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort_order" id="sortDesc" value="desc">
                            <label class="form-check-label" for="sortDesc">Descending</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="exportFinalConfirmBtn">Generate PDF</button>
            </div>
        </div>
    </div>
</div>




<script>
// ============================================
// INVENTORY PDF EXPORT MODAL
// ============================================
(function() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initExportModal);
    } else {
        initExportModal();
    }

    function initExportModal() {
        // Get DOM elements
        const exportAllRadio = document.getElementById('exportAllRadio');
        const exportCategoryRadio = document.getElementById('exportCategoryRadio');
        const exportItemTypeRadio = document.getElementById('exportItemTypeRadio');
        const exportCategoryDiv = document.getElementById('exportCategoryDiv');
        const exportItemTypeDiv = document.getElementById('exportItemTypeDiv');
        const exportBtn = document.getElementById('exportInventoryBtn');
        const confirmBtn = document.getElementById('exportFinalConfirmBtn');

        // Category dropdown elements
        const exportCategorySelect = document.getElementById('exportCategorySelectList');
        const exportCategoryText = document.getElementById('exportCategoryText');
        const exportCategorySearch = document.getElementById('exportCategorySearchInput');
        const exportSelectAllCategories = document.getElementById('exportSelectAllCategories');
        const exportDeselectAllCategories = document.getElementById('exportDeselectAllCategories');

        // Item type select
        const exportItemTypeSelect = document.getElementById('exportItemTypeSelect');

        // Toggle visibility based on export type
        if (exportAllRadio && exportCategoryRadio && exportItemTypeRadio) {
            exportAllRadio.addEventListener('change', function() {
                if (this.checked) {
                    exportCategoryDiv.classList.add('d-none');
                    exportItemTypeDiv.classList.add('d-none');
                }
            });

            exportCategoryRadio.addEventListener('change', function() {
                if (this.checked) {
                    exportCategoryDiv.classList.remove('d-none');
                    exportItemTypeDiv.classList.add('d-none');
                }
            });

            exportItemTypeRadio.addEventListener('change', function() {
                if (this.checked) {
                    exportCategoryDiv.classList.add('d-none');
                    exportItemTypeDiv.classList.remove('d-none');
                }
            });
        }

        // Update category button text
        function updateExportCategoryText() {
            if (!exportCategorySelect) return;
            const selected = Array.from(exportCategorySelect.selectedOptions);

            if (selected.length === 0) {
                exportCategoryText.textContent = 'Select Categories';
            } else if (selected.length === 1) {
                exportCategoryText.textContent = selected[0].textContent;
            } else {
                exportCategoryText.textContent = selected.length + ' categories selected';
            }
        }

        // Search categories
        if (exportCategorySearch && exportCategorySelect) {
            exportCategorySearch.addEventListener('input', function() {
                const term = this.value.toLowerCase();
                const options = exportCategorySelect.options;
                for (let i = 0; i < options.length; i++) {
                    const text = options[i].textContent.toLowerCase();
                    options[i].style.display = text.includes(term) ? '' : 'none';
                }
            });
        }

        // Select all categories
        if (exportSelectAllCategories && exportCategorySelect) {
            exportSelectAllCategories.addEventListener('click', function(e) {
                e.preventDefault();
                for (let i = 0; i < exportCategorySelect.options.length; i++) {
                    exportCategorySelect.options[i].selected = true;
                }
                updateExportCategoryText();
            });
        }

        // Deselect all categories
        if (exportDeselectAllCategories && exportCategorySelect) {
            exportDeselectAllCategories.addEventListener('click', function(e) {
                e.preventDefault();
                for (let i = 0; i < exportCategorySelect.options.length; i++) {
                    exportCategorySelect.options[i].selected = false;
                }
                updateExportCategoryText();
            });
        }

        if (exportCategorySelect) {
            exportCategorySelect.addEventListener('change', updateExportCategoryText);
        }

        // Open modal button
        if (exportBtn) {
            exportBtn.addEventListener('click', function() {
                const exportModal = new bootstrap.Modal(document.getElementById('exportInventoryModal'));
                exportModal.show();
            });
        }

        // Reset modal when opened
        const exportModalElement = document.getElementById('exportInventoryModal');
        if (exportModalElement) {
            exportModalElement.addEventListener('show.bs.modal', function() {
                // Reset to default
                if (exportAllRadio) exportAllRadio.checked = true;
                if (exportCategoryDiv) exportCategoryDiv.classList.add('d-none');
                if (exportItemTypeDiv) exportItemTypeDiv.classList.add('d-none');

                // Reset category selection
                if (exportCategorySelect) {
                    for (let i = 0; i < exportCategorySelect.options.length; i++) {
                        exportCategorySelect.options[i].selected = false;
                    }
                    updateExportCategoryText();
                }

                // Reset item type
                if (exportItemTypeSelect) exportItemTypeSelect.value = '';

                // Reset search
                if (exportCategorySearch) exportCategorySearch.value = '';

                // Reset sort order
                const sortAsc = document.getElementById('sortAsc');
                if (sortAsc) sortAsc.checked = true;

                const sortBy = document.getElementById('exportSortBy');
                if (sortBy) sortBy.value = 'item_name';
            });
        }

        // Generate PDF on confirm
        if (confirmBtn) {
            const newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

            newConfirmBtn.addEventListener('click', function() {
                const exportType = document.querySelector('input[name="export_type"]:checked').value;
                let params = new URLSearchParams();

                params.append('export_type', exportType);

                // Category filter
                if (exportType === 'category' && exportCategorySelect) {
                    const selectedCategories = Array.from(exportCategorySelect.selectedOptions).map(opt => opt.value);
                    if (selectedCategories.length > 0) {
                        selectedCategories.forEach(id => {
                            params.append('category_ids[]', id);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please select at least one category',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        return;
                    }
                }

                // Item type filter
                if (exportType === 'item_type' && exportItemTypeSelect) {
                    const itemType = exportItemTypeSelect.value;
                    if (!itemType) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please select an item type',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        return;
                    }
                    params.append('item_type', itemType);
                }

                // Sort by
                const sortBy = document.getElementById('exportSortBy');
                if (sortBy) {
                    params.append('sort_by', sortBy.value);
                }

                // Sort order
                const sortOrder = document.querySelector('input[name="sort_order"]:checked');
                if (sortOrder) {
                    params.append('sort_order', sortOrder.value);
                }

                // Close modal
                const exportModal = bootstrap.Modal.getInstance(document.getElementById('exportInventoryModal'));
                if (exportModal) exportModal.hide();

                // Download PDF
                window.location.href = "{{ route('inventory.export-pdf') }}?" + params.toString();
            });
        }

        // Initialize category text
        updateExportCategoryText();
    }
})();
</script>





<script>
// VIEW

function formatINR(value){
    return new Intl.NumberFormat('en-IN').format(value);
}
$(document).on('click','.view-btn',function(){

    $('#b_name').text($(this).data('name'));
    $('#b_code').text($(this).data('code'));
    $('#b_category').text($(this).data('category'));
    $('#b_item_type').text($(this).data('item_type'));

    $('#b_brand').text($(this).data('brand'));
    $('#b_model').text($(this).data('model'));
    $('#b_serial').text($(this).data('serial'));

    $('#b_purchase_date').text($(this).data('purchase_date'));
    $('#b_vendor').text($(this).data('vendor'));
    $('#b_invoice').text($(this).data('invoice'));

    $('#b_qty').text($(this).data('qty'));
    $('#b_stock').text($(this).data('stock'));
  let cost = $(this).data('cost');

$('#b_cost').text(formatINR(cost));


    $('#b_warranty').text($(this).data('warranty'));

    $('#b_desc').text($(this).data('desc'));
    $('#b_remarks').text($(this).data('remarks'));

  $('#b_cost_total').text(formatINR(cost));

    // IMAGE
    $('#b_image').attr('src', $(this).data('image'));

    // DOCUMENT
    let doc = $(this).data('doc');

    if(doc){
        $('#b_doc').attr('href', doc).show();
    }else{
        $('#b_doc').hide();
    }

});

// DELETE
$(document).on('click','.delete-btn',function(){

    let id = $(this).data('id');

    Swal.fire({
        title:'Delete Item?',
        icon:'warning',
        showCancelButton:true
    }).then((res)=>{

        if(res.isConfirmed){

            $.ajax({
                   url: "{{ route('inventory.destroy', ':id') }}".replace(':id', id),
                type:'DELETE',
                data:{ _token:'{{ csrf_token() }}' },
                success:function(resp){
                    if(resp.status){
                        Swal.fire('Deleted','Success','success').then(()=>{
                            location.reload();
                        });
                    }
                }
            });

        }

    });

});
</script>

</x-layout>
-------------------------
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
<x-layout>
@section('title','Edit Maintenance')

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

.employee-details-card {
    background: #f8f9fa;
    border-left: 4px solid #0d6efd;
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
                <li class="breadcrumb-item active text-primary">Edit</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('inventory.maintenance.index') }}" class="btn btn-outline-primary d-flex align-items-center">
        <i class="bi bi-arrow-left-circle me-2"></i>Back
    </a>
</div>

<form id="maintenanceForm">
@csrf
@method('PUT')

<div class="row">

<div class="col-lg-9">
<div class="card shadow-sm border-0 p-4">

<div class="bg-light rounded-3 p-3 mb-3">
    <h6 class="fw-bold text-primary mb-0">Edit Maintenance Details</h6>
</div>

<div class="row g-3">

<!-- ITEM (Read-only for edit) -->
<div class="col-md-6">
    <label>Item <span class="text-danger">*</span></label>
    <input type="text" class="form-control" value="{{ $maintenance->item->item_name }} ({{ $maintenance->item->item_code }})" readonly disabled>
    <input type="hidden" name="item_id" value="{{ $maintenance->item_id }}">
</div>

<!-- Assigned Employee Details -->
<div class="col-12">
    <div class="card employee-details-card">
        <div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-person-badge fs-2 text-primary"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1 text-primary">Item Assignment Details:</h6>
                    @php
                        $assignment = \App\Models\InventoryAssignment::where('item_id', $maintenance->item_id)
                            ->where('status', 'assigned')
                            ->with(['employee', 'department'])
                            ->first();
                    @endphp
                    @if($assignment)
                        <p class="mb-1"><strong>Assigned To:</strong> {{ $assignment->employee->fullname ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Department:</strong> {{ $assignment->department->dep_name ?? 'N/A' }}</p>
                        <p class="mb-0"><strong>Assigned Date:</strong> {{ $assignment->assigned_date ?? 'N/A' }}</p>
                        <input type="hidden" name="employee_id" value="{{ $assignment->employee_id }}">
                    @else
                        <p class="mb-0 text-warning">⚠️ This item is not currently assigned to any employee</p>
                        <input type="hidden" name="employee_id" value="">
                    @endif
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
        <option value="scrap" {{ $maintenance->maintenance_type == 'scrap' ? 'selected' : '' }}>Scrap</option>
        <option value="service" {{ $maintenance->maintenance_type == 'service' ? 'selected' : '' }}>Service</option>
        <option value="upgrade" {{ $maintenance->maintenance_type == 'upgrade' ? 'selected' : '' }}>Upgrade</option>
    </select>
</div>

<!-- STATUS -->
<div class="col-md-6">
    <label>Status <span class="text-danger">*</span></label>
    <select name="status" class="form-select select2">
        <option value="pending" {{ $maintenance->status == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="completed" {{ $maintenance->status == 'completed' ? 'selected' : '' }}>Completed</option>
    </select>
</div>

<!-- ISSUE -->
<div class="col-md-6">
    <label>Issue <span class="text-danger">*</span></label>
    <textarea name="issue_description" class="form-control" placeholder="Enter issue description" rows="3">{{ $maintenance->issue_description }}</textarea>
</div>

<!-- COST -->
<div class="col-md-6">
    <label>Cost <span class="text-danger">*</span></label>
    <input type="number" step="0.01" name="cost" class="form-control" value="{{ $maintenance->cost }}" placeholder="Enter cost">
</div>

<!-- VENDOR -->
<div class="col-md-6">
    <label>Vendor Name <span class="text-danger">*</span></label>
    <input type="text" name="vendor_name" class="form-control" value="{{ $maintenance->vendor_name }}" placeholder="Enter vendor name">
</div>

<!-- START DATE -->
<div class="col-md-6">
    <label>Start Date <span class="text-danger">*</span></label>
    <input type="date" name="start_date" class="form-control" value="{{ $maintenance->start_date }}">
</div>

<!-- END DATE (only show if completed) -->
<div class="col-md-6" id="endDateContainer" style="{{ $maintenance->status == 'completed' ? 'display:block' : 'display:none' }}">
    <label>End Date</label>
    <input type="date" name="end_date" class="form-control" value="{{ $maintenance->end_date }}">
</div>

<!-- REMARK -->
<div class="col-12">
    <label>Remarks</label>
    <textarea name="remarks" class="form-control" placeholder="Optional remarks" rows="2">{{ $maintenance->remarks }}</textarea>
</div>

</div>

</div>
</div>

<!-- RIGHT ACTION CARD -->
<div class="col-lg-3">
    <div class="card shadow-sm border-0 p-4 position-sticky" style="top:100px;">
        <h6 class="fw-bold text-primary mb-3">Actions</h6>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Update</button>
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

    // Show/hide end date based on status
    $('select[name="status"]').on('change', function(){
        if($(this).val() == 'completed'){
            $('#endDateContainer').slideDown();
        } else {
            $('#endDateContainer').slideUp();
        }
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

    if(name == 'maintenance_type' && !val){
        showError(input,'Type required');
        return false;
    }

    if(name == 'status' && !val){
        showError(input,'Status required');
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
        showError(input,'Start date required');
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
        if($(this).attr('name') && !$(this).prop('disabled') && !validate($(this))) {
            valid = false;
        }
    });

    if(!valid) return;

    $.ajax({
        url: '{{ route("inventory.maintenance.update", $maintenance->id) }}',
        type: 'POST',
        data: $(this).serialize(),
        success: function(res){
            if(res.status){
                Swal.fire('Success','Maintenance updated successfully','success').then(()=>{
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
<x-layout>
@section('title', 'Inventory Maintenance')

<div class="container-fluid p-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>Maintenance Management</h3>
        </div>
        <a href="{{ route('inventory.maintenance.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Maintenance
        </a>
    </div>

    <form method="GET" class="row mb-3">
        <div class="col-md-3">
            <input type="text" name="item" value="{{ request('item') }}"
                   class="form-control" placeholder="Item Name">
        </div>

        <div class="col-md-3">
            <select name="type" class="form-control">
                <option value="">All Type</option>
                <option value="scrap" {{ request('type')=='scrap' ? 'selected' : '' }}>Scrap</option>
                <option value="service" {{ request('type')=='service' ? 'selected' : '' }}>Service</option>
                <option value="upgrade" {{ request('type')=='upgrade' ? 'selected' : '' }}>Upgrade</option>
            </select>
        </div>

        <div class="col-md-2">
            <select name="status" class="form-control">
                <option value="">All Status</option>
                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Completed</option>
            </select>
        </div>

        <div class="col-md-4">
            <button class="btn btn-primary">Filter</button>
            <a href="{{ route('inventory.maintenance.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="d-flex">
                <form method="GET" class="mb-3 d-flex align-items-center gap-2">
                    <label>Show</label>
                    <select name="per_page" onchange="this.form.submit()" class="form-control form-control-sm">
                        <option value="5" {{ request('per_page')==5?'selected':'' }}>5</option>
                        <option value="10" {{ request('per_page')==10?'selected':'' }}>10</option>
                        <option value="20" {{ request('per_page')==20?'selected':'' }}>20</option>
                    </select>
                    <span>entries</span>

                    <!-- keep filters -->
                    <input type="hidden" name="item" value="{{ request('item') }}">
                    <input type="hidden" name="type" value="{{ request('type') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Cost</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($maintenances as $index => $m)
                        <tr>
                            <td>{{ $maintenances->firstItem() + $index }}</td>
                            <td>{{ $m->item->item_name ?? '-' }}</td>
                            <td>{{ ucfirst($m->maintenance_type) }}</td>
                            <td>
                                <span class="badge p-2 {{ $m->status == 'completed' ? 'bg-success':'bg-warning' }}">
                                    {{ ucfirst($m->status) }}
                                </span>
                            </td>
                            <td>{{ $m->cost ? '₹ '.number_format($m->cost, 0, '.', ',') : '-' }}</td>
                            <td>{{ $m->start_date ? \Carbon\Carbon::parse($m->start_date)->format('d-m-Y') : '-' }}</td>
                            <td>
                                <!-- View Button -->
                                <button class="btn btn-sm btn-info view-btn"
                                    data-id="{{ $m->id }}"
                                    data-item="{{ $m->item->item_name ?? '-' }}"
                                    data-image="{{ asset('inventory_images/'.$m->item->item_image) }}"
                                    data-type="{{ $m->maintenance_type }}"
                                    data-desc="{{ $m->issue_description }}"
                                    data-status="{{ $m->status }}"
                                    data-cost="{{ number_format($m->cost,0,'.',',') }}"
                                    data-vendor="{{ $m->vendor_name }}"
                                    data-date="{{ $m->start_date ? \Carbon\Carbon::parse($m->start_date)->format('d-m-Y') : '' }}"
                                    data-end="{{ $m->end_date ? \Carbon\Carbon::parse($m->end_date)->format('d-m-Y') : '' }}"
                                    data-remarks="{{ $m->remarks }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#billModal">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <!-- Edit Button - Only show for pending maintenance -->
                                @if($m->status == 'pending')
                                <a href="{{ route('inventory.maintenance.edit', $m->id) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif

                                <!-- Complete Button (only for pending) -->
                                @if($m->status == 'pending')
                                <button class="btn btn-sm btn-success complete-btn"
                                        data-id="{{ $m->id }}">
                                    <i class="bi bi-check-circle"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-between mt-3">
                    <div>
                        Showing {{ $maintenances->firstItem() }} to {{ $maintenances->lastItem() }}
                        of {{ $maintenances->total() }} entries
                    </div>
                    <div>
                        {{ $maintenances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- VIEW MODAL -->
<div class="modal fade" id="billModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg rounded-4 p-4">
            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-0">Maintenance Invoice</h4>
                    <small class="text-muted">Inventory Maintenance</small>
                </div>
                <span class="badge px-3 py-2" id="b_status"></span>
            </div>

            <div class="row">
                <!-- LEFT -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                        <h6 class="fw-bold text-primary">Item Info</h6>
                        <p><b>Item:</b> <span id="b_item"></span></p>
                        <p><b>Type:</b> <span id="b_type"></span></p>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                        <h6 class="fw-bold text-primary">Maintenance Details</h6>
                        <p><b>Issue:</b> <span id="b_desc"></span></p>
                        <p><b>Vendor:</b> <span id="b_vendor"></span></p>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                        <h6 class="fw-bold text-primary">Dates</h6>
                        <p><b>Start:</b> <span id="b_date"></span></p>
                        <p><b>End:</b> <span id="b_end"></span></p>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-3">
                        <h6 class="fw-bold text-primary">Remarks</h6>
                        <p id="b_remarks"></p>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3 text-center">
                        <h6 class="fw-bold text-primary">Item Image</h6>
                        <img id="b_image" class="img-fluid rounded-3" style="max-height:200px; object-fit:cover;">
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-light">
                        <h6 class="fw-bold text-primary">Total Cost</h6>
                        <h3>₹ <span id="b_cost"></span></h3>
                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="text-end mt-4">
                <button onclick="window.print()" class="btn btn-success">🖨 Print</button>
            </div>
        </div>
    </div>
</div>

<script>
    // VIEW BUTTON
    $(document).on('click', '.view-btn', function(){
        $('#b_item').text($(this).data('item'));
        $('#b_type').text($(this).data('type'));
        $('#b_desc').text($(this).data('desc'));
        $('#b_vendor').text($(this).data('vendor'));
        $('#b_date').text($(this).data('date'));
        $('#b_end').text($(this).data('end') || 'Not Completed');
        $('#b_cost').text($(this).data('cost'));
        $('#b_remarks').text($(this).data('remarks') || '-');
        $('#b_image').attr('src', $(this).data('image'));

        let status = $(this).data('status');
        $('#b_status').text(status);

        if(status === 'completed'){
            $('#b_status').removeClass().addClass('badge bg-success px-3 py-2');
        } else {
            $('#b_status').removeClass().addClass('badge bg-warning px-3 py-2');
        }
    });

    // COMPLETE BUTTON
    $(document).on('click', '.complete-btn', function(){
        let id = $(this).data('id');

        Swal.fire({
            title: 'Complete Maintenance?',
            icon: 'question',
            showCancelButton: true
        }).then((result) => {
            if(result.isConfirmed){
                let url = "{{ route('inventory.maintenance.complete', ['id'=>'ID']) }}";
                url = url.replace('ID', id);

                $.post(url, {
                    _token: '{{ csrf_token() }}'
                }, function(res){
                    if(res.status){
                        Swal.fire('Done', 'Maintenance completed successfully', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                });
            }
        });
    });
</script>

</x-layout>


-----------------------------
