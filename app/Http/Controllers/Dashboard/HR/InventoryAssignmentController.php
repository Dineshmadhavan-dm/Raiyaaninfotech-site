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
        ->where('status','assigned')
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
            ->whereDoesntHave('resignation') // ✅ ADD THIS
            ->select('emp_id', 'fullname')
            ->get()
    );
}
    // ✅ LIST
   public function index(Request $request)
{
    $query = InventoryAssignment::with(['item', 'employee', 'department']);
    // 🔍 FILTERS
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


public function store(Request $request)
{
    $request->validate([
        'item_id' => 'required',
        'employee_id' => 'required',
        'department_id' => 'required',
        'assigned_date' => 'required|date',
    ]);

    $item = InventoryItem::findOrFail($request->item_id);
    $oldStatus = $item->status;

    $assignment = InventoryAssignment::create([
        'item_id' => $request->item_id,
        'employee_id' => $request->employee_id,
        'department_id' => $request->department_id,
        'assigned_date' => $request->assigned_date,
        'status' => 'assigned',
        'remarks' => $request->remarks,
    ]);

    $item->status = 'assigned';
    $item->save();

    InventoryHistory::create([
        'item_id' => $item->id,
        'employee_id' => $request->employee_id,
        'action_type' => 'assigned',
        'old_status' => $oldStatus,
        'new_status' => 'assigned',
        'notes' => 'Item assigned',
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

    $item = InventoryItem::findOrFail($assignment->item_id);
    $oldStatus = $item->status;

    $item->status = 'available';
    $item->save();

    InventoryHistory::create([
        'item_id' => $item->id,
        'employee_id' => $assignment->employee_id,
        'action_type' => 'returned',
        'old_status' => $oldStatus,
        'new_status' => 'available',
        'notes' => 'Item returned',
        'action_date' => now(),
    ]);

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
