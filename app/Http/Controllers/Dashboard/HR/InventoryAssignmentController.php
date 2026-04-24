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
            ->where('status', 0)
            ->where('condition_status', 1)
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
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('item_name', 'like', '%' . $request->item . '%');
            });
        }

        if ($request->filled('employee')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('fullname', 'like', '%' . $request->employee . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->get('per_page', 5);
        $assignments = $query->latest()->paginate($perPage)->withQueryString();

        $departments = Department::where('delete_status', 1)->get();

        $employees = Employee::where('delete_status', 1)
            ->whereDoesntHave('resignation')
            ->select('emp_id', 'fullname', 'employee_id')
            ->get();

        return view('dashboard.hr.inventory.assignment.index', compact('assignments', 'departments', 'employees'));
    }

    public function create()
    {
        $items = InventoryItem::where('delete_status', 1)
            ->whereDoesntHave('assignments', function ($q) {
                $q->where(function ($sub) {
                    $sub->where('condition_status', 0)
                        ->orWhere('status', 0);
                });
            })
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

        $assignment = InventoryAssignment::create([
            'item_id' => $request->item_id,
            'employee_id' => $request->employee_id,
            'department_id' => $request->department_id,
            'assigned_date' => $request->assigned_date,
            'status' => 0,
            'condition_status' => 1,
            'remarks' => $request->remarks,
        ]);

        InventoryHistory::log([
            'module' => InventoryHistory::MODULE_ASSIGNMENT,
            'action' => InventoryHistory::ACTION_ASSIGNED,
            'item_id' => $assignment->item_id,
            'employee_id' => $assignment->employee_id,
            'assignment_id' => $assignment->id,
            'new_data' => $assignment->toArray(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Item assigned successfully'
        ]);
    }

    public function returnItem(Request $request, $id)
    {
        try {
            $assignment = InventoryAssignment::findOrFail($id);

            if ($assignment->status == 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Item already returned'
                ]);
            }

            $request->validate([
                'return_date' => 'required|date',
            ]);

            $oldData = $assignment->toArray();

            $assignment->status = 1;
            $assignment->return_date = $request->return_date;

            if ($request->filled('return_remarks')) {
                $assignment->remarks = $request->return_remarks;
            }

            $assignment->save();

            InventoryHistory::log([
                'module' => InventoryHistory::MODULE_ASSIGNMENT,
                'action' => InventoryHistory::ACTION_RETURNED,
                'item_id' => $assignment->item_id,
                'employee_id' => $assignment->employee_id,
                'assignment_id' => $assignment->id,
                'old_data' => $oldData,
                'new_data' => $assignment->toArray(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Item returned successfully on ' . date('d-m-Y', strtotime($request->return_date))
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Assignment record not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
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
