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
