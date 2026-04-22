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

// ✅ DOCUMENT UPLOAD
if ($request->hasFile('document_file')) {
    $file = $request->file('document_file');
    $filename = 'mnt_' . time() . '.' . $file->getClientOriginalExtension();
    $file->move(public_path('maintenance_docs'), $filename);
    $data['document'] = $filename;
}

$maintenance = InventoryMaintenance::create($data);
// 🔥 IF SCRAP → AUTO RETURN + MARK SCRAP
if ($request->maintenance_type == 0) {

    $assignments = InventoryAssignment::where('item_id', $request->item_id)
        ->where('status', 0)
        ->get();

    foreach ($assignments as $assignment) {

        $assignment->update([
            'status' => 1, // returned
            'condition_status' => 0, // scrap
            'return_date' => now()
        ]);

        // ✅ ADD HISTORY (IMPORTANT 🔥)
        InventoryHistory::create([
            'item_id' => $assignment->item_id,
            'employee_id' => $assignment->employee_id,
            'action_type' => 1, // returned
            'action_date' => now(),
        ]);
    }
}
        InventoryHistory::create([
            'item_id' => $request->item_id,
            'employee_id' => $request->employee_id,
            'action_type' => $request->maintenance_type,
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
            'maintenance_type' => 'required|in:0,1,2',
            'issue_description' => 'required|min:5|max:500',
            'cost' => 'required|numeric|min:0',
            'vendor_name' => 'required|min:2',
            'start_date' => 'required|date',
            'status' => 'required|in:0,1',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'remarks' => 'nullable|max:500',
        ]);

    $data = $request->except('_token', '_method');

// ✅ DOCUMENT UPLOAD
if ($request->hasFile('document_file')) {
    $file = $request->file('document_file');
    $filename = 'mnt_' . time() . '.' . $file->getClientOriginalExtension();
    $file->move(public_path('maintenance_docs'), $filename);

    if ($maintenance->document && file_exists(public_path('maintenance_docs/'.$maintenance->document))) {
        unlink(public_path('maintenance_docs/'.$maintenance->document));
    }

    $data['document'] = $filename;
}

// reset end date
if ($request->status == 0) {
    $data['end_date'] = null;
}

$maintenance->update($data);

if ($request->maintenance_type == 0) {

    $assignments = InventoryAssignment::where('item_id', $maintenance->item_id)
        ->where('status', 0)
        ->get();

    foreach ($assignments as $assignment) {

        $assignment->update([
            'status' => 1,
            'condition_status' => 0,
            'return_date' => now()
        ]);

        InventoryHistory::create([
            'item_id' => $assignment->item_id,
            'employee_id' => $assignment->employee_id,
            'action_type' => 1,
            'action_date' => now(),
        ]);
    }
}

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
