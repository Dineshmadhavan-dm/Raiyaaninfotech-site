<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
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

    // ✅ LIST
  public function index(Request $request)
{
    $query = InventoryMaintenance::with('item');

    // 🔍 FILTER: ITEM
    if ($request->filled('item')) {
        $query->whereHas('item', function($q) use ($request){
            $q->where('item_name', 'like', '%' . $request->item . '%');
        });
    }

    // 🔍 FILTER: TYPE
    if ($request->filled('type')) {
        $query->where('maintenance_type', $request->type);
    }

    // 🔍 FILTER: STATUS
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $perPage = $request->get('per_page', 5);

    $maintenances = $query->latest()
        ->paginate($perPage)
        ->withQueryString();

    return view('dashboard.hr.inventory.maintenance.index', compact('maintenances'));
}
    // ✅ CREATE FORM
    public function create()
    {
        $items = InventoryItem::where('delete_status', 1)
        ->whereDoesntHave('maintenances', function($q){
            $q->where('status', 'pending'); // ❌ hide if still in maintenance
        })
        ->get();

        return view('dashboard.hr.inventory.maintenance.create', compact('items'));
    }

public function store(Request $request)
{
    $request->validate([
        'item_id' => 'required',
        'issue_description' => 'required',
        'maintenance_type' => 'required',
    ]);

    $item = InventoryItem::findOrFail($request->item_id);
    $oldStatus = $item->status;

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

    $item->status = 'maintenance';
    $item->save();

    InventoryHistory::create([
        'item_id' => $item->id,
        'action_type' => 'repaired',
        'old_status' => $oldStatus,
        'new_status' => 'maintenance',
        'notes' => 'Maintenance started',
        'action_date' => now(),
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Maintenance added successfully'
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

    $item = InventoryItem::findOrFail($maintenance->item_id);
    $oldStatus = $item->status;

    $item->status = 'available';
    $item->save();

    InventoryHistory::create([
        'item_id' => $item->id,
        'action_type' => 'repaired',
        'old_status' => $oldStatus,
        'new_status' => 'available',
        'notes' => 'Maintenance completed',
        'action_date' => now(),
    ]);

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
