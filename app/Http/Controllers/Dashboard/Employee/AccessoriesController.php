<?php

namespace App\Http\Controllers\Dashboard\Employee;

use App\Http\Controllers\Controller;
use App\Models\InventoryAssignment;
use App\Models\InventoryItem;
use App\Models\InventoryMaintenance;
use Illuminate\Http\Request;

class AccessoriesController extends Controller
{
    public function index(Request $request)
    {
        // Get the logged-in user's employee record
        $user = auth()->user();
        $employeeId = null;

        if ($user->employee) {
            $employeeId = $user->employee->emp_id;
        }

        if (!$employeeId) {
            $employee = \App\Models\Employee::where('user_id', $user->id)->first();
            if ($employee) {
                $employeeId = $employee->emp_id;
            }
        }

        // Build query with filters
        $query = InventoryAssignment::with(['item', 'department'])
            ->where('employee_id', $employeeId)
            ->where('status', 0);

        // Filter by item name
        if ($request->filled('item')) {
            $query->whereHas('item', function($q) use ($request) {
                $q->where('item_name', 'like', '%' . $request->item . '%');
            });
        }

        // Filter by item type
        if ($request->filled('item_type')) {
            $query->whereHas('item', function($q) use ($request) {
                $q->where('item_type', $request->item_type);
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 3);
        $assignments = $query->latest()->paginate($perPage)->withQueryString();

        // Add maintenance tracking info to paginated items
        foreach ($assignments as $assignment) {
            // Check for pending maintenance
            $hasPendingMaintenance = InventoryMaintenance::where('item_id', $assignment->item_id)
                ->where('status', 0)
                ->exists();
            $assignment->has_pending_maintenance = $hasPendingMaintenance;

            // Get ALL maintenance records for this item
            $allMaintenances = InventoryMaintenance::where('item_id', $assignment->item_id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Count total maintenance requests
            $assignment->maintenance_count = $allMaintenances->count();

            // Get the latest maintenance record
            $latestMaintenance = $allMaintenances->first();
            if ($latestMaintenance) {
                // Generate tracking ID from maintenance ID (MNT-000001 format)
                $assignment->last_tracking_id = 'MNT-' . str_pad($latestMaintenance->id, 6, '0', STR_PAD_LEFT);
                $assignment->last_maintenance_date = $latestMaintenance->created_at;
                $assignment->last_maintenance_status = $latestMaintenance->status;
                $assignment->last_maintenance_type = $latestMaintenance->maintenance_type;
            } else {
                $assignment->last_tracking_id = null;
                $assignment->last_maintenance_date = null;
                $assignment->last_maintenance_status = null;
                $assignment->last_maintenance_type = null;
            }
        }

        $scrappedItems = InventoryMaintenance::where('maintenance_type', 0) // scrap
    ->where('status', 1) // completed
    ->where('employee_id', $employeeId)
    ->with('item')
    ->latest()
    ->take(3)
    ->get();
      return view('dashboard.employee.accessories.index', compact(
    'assignments',
    'employeeId',
    'scrappedItems'
));
    }

    // Method to request maintenance from employee side
    public function requestMaintenance(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'issue_description' => 'required|min:5|max:500',
             'maintenance_type' => 'required|in:0,1,2',
            'remarks' => 'nullable|max:300',
        ]);

        // Get employee ID
        $user = auth()->user();
        $employeeId = null;

        if ($user->employee) {
            $employeeId = $user->employee->emp_id;
        }

        if (!$employeeId) {
            $employee = \App\Models\Employee::where('user_id', $user->id)->first();
            if ($employee) {
                $employeeId = $employee->emp_id;
            }
        }

        if (!$employeeId) {
            return response()->json([
                'status' => false,
                'message' => 'Employee record not found'
            ]);
        }

        // Verify the item is assigned to this employee
        $assignment = InventoryAssignment::where('item_id', $request->item_id)
            ->where('employee_id', $employeeId)
            ->where('status', 0)
            ->first();

        if (!$assignment) {
            return response()->json([
                'status' => false,
                'message' => 'Item not assigned to you'
            ]);
        }

        // Check if there's already a pending maintenance for this item
        $existingMaintenance = InventoryMaintenance::where('item_id', $request->item_id)
            ->where('status', 0)
            ->first();

        if ($existingMaintenance) {
            return response()->json([
                'status' => false,
                'message' => 'A maintenance request for this item is already pending'
            ]);
        }

        // Create maintenance record
        $maintenance = InventoryMaintenance::create([
            'item_id' => $request->item_id,
            'employee_id' => $employeeId,
            'issue_description' => $request->issue_description,
            'maintenance_type' => $request->maintenance_type,
            'cost' => 0,
            'vendor_name' => null,
            'start_date' => now(),
            'status' => 0,
            'remarks' => $request->remarks,
        ]);

        // Generate tracking ID
        $trackingId = 'MNT-' . str_pad($maintenance->id, 6, '0', STR_PAD_LEFT);

        return response()->json([
            'status' => true,
            'message' => 'Maintenance request submitted successfully. Tracking ID: ' . $trackingId,
            'tracking_id' => $trackingId,
            'maintenance_id' => $maintenance->id
        ]);
    }

    // View maintenance history for an item
    public function maintenanceHistory(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:inventory_items,id'
        ]);

        $itemId = $request->item_id;

        // Verify the employee has access to this item
        $user = auth()->user();
        $employeeId = null;

        if ($user->employee) {
            $employeeId = $user->employee->emp_id;
        }

        if (!$employeeId) {
            $employee = \App\Models\Employee::where('user_id', $user->id)->first();
            if ($employee) {
                $employeeId = $employee->emp_id;
            }
        }

        // Check if item is assigned to this employee
        $isAssigned = InventoryAssignment::where('item_id', $itemId)
            ->where('employee_id', $employeeId)
            ->where('status', 0)
            ->exists();

        if (!$isAssigned) {
            return response()->json([
                'status' => false,
                'message' => 'You do not have access to this item'
            ]);
        }

        $maintenances = InventoryMaintenance::where('item_id', $itemId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $maintenances,
            'count' => $maintenances->count()
        ]);
    }
}
