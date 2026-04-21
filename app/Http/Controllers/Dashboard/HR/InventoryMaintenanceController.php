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
