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
            ->where('status', 'assigned');

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

        // Pagination - ONLY GET PAGINATED RESULTS ONCE
        $perPage = $request->get('per_page', 3);
        $assignments = $query->latest()->paginate($perPage)->withQueryString();

        // Add pending maintenance status to paginated items
        foreach ($assignments as $assignment) {
            $hasPendingMaintenance = InventoryMaintenance::where('item_id', $assignment->item_id)
                ->where('status', 'pending')
                ->exists();
            $assignment->has_pending_maintenance = $hasPendingMaintenance;
        }

        return view('dashboard.employee.accessories.index', compact('assignments', 'employeeId'));
    }

    // Method to request maintenance from employee side
    public function requestMaintenance(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'issue_description' => 'required|min:5|max:500',
            'maintenance_type' => 'required',
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

        $assignment = InventoryAssignment::where('item_id', $request->item_id)
            ->where('employee_id', $employeeId)
            ->where('status', 'assigned')
            ->first();

        if (!$assignment) {
            return response()->json([
                'status' => false,
                'message' => 'Item not assigned to you'
            ]);
        }

        // Check if there's already a pending maintenance for this item
        $existingMaintenance = InventoryMaintenance::where('item_id', $request->item_id)
            ->where('status', 'pending')
            ->first();

        if ($existingMaintenance) {
            return response()->json([
                'status' => false,
                'message' => 'A maintenance request for this item is already pending'
            ]);
        }

        $maintenance = InventoryMaintenance::create([
            'item_id' => $request->item_id,
            'employee_id' => $employeeId,
            'issue_description' => $request->issue_description,
            'maintenance_type' => $request->maintenance_type,
            'cost' => 0,
            'vendor_name' => null,
            'start_date' => now(),
            'status' => 'pending',
            'remarks' => $request->remarks,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Maintenance request submitted successfully'
        ]);
    }
}
