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



public function exportPdf(Request $request)
{
    try {
        $query = InventoryAssignment::with(['item', 'employee', 'department']);

        $exportType = $request->input('export_type', 'all');

        if ($exportType === 'status' && $request->has('status')) {
            $status = $request->input('status');
          if ($status !== null && $status !== ''){
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
     $assignedCount = $assignments->where('status', 0)->count();
$returnedCount = $assignments->where('status', 1)->count();

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
            if ($assignment->status == 0) {
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
            if ($assignment->status == 0) {
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

     $filterDescription = 'All Assignments';

if ($exportType === 'status') {

    $statusVal = $request->input('status');

    if ($statusVal !== null && $statusVal !== '') {
        $filterDescription = 'Status: ' . ($statusVal == 0 ? 'Assigned' : 'Returned');
    }

} elseif ($exportType === 'employee' && $request->has('employee_ids')) {

    $employeeIds = $request->input('employee_ids');

    if (!empty($employeeIds)) {
        $employeeNames = \App\Models\Employee::whereIn('emp_id', $employeeIds)
            ->pluck('fullname')
            ->toArray();

        $filterDescription = 'Employee(s): ' . implode(', ', $employeeNames);
    }
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


$item = InventoryItem::find($request->item_id);

if ($item && $item->condition_status == 0) {
    return response()->json([
        'status' => false,
        'message' => 'Scrap item cannot be assigned '
    ]);
}
        $assignment = InventoryAssignment::create([
            'item_id' => $request->item_id,
            'employee_id' => $request->employee_id,
            'department_id' => $request->department_id,
            'assigned_date' => $request->assigned_date,
            'status' => 0,
            'condition_status' => 1,
            'remarks' => $request->remarks,
        ]);

        InventoryHistory::create([
            'item_id' => $request->item_id,
            'employee_id' => $request->employee_id,
            'action_type' => 0,
            'action_date' => now(),
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

        // Validate return date
        $request->validate([
            'return_date' => 'required|date',
        ]);

        $assignment->status = 1;
        $assignment->return_date = $request->return_date;

        // Update remarks if provided, otherwise keep existing
        if ($request->filled('return_remarks')) {
            $assignment->remarks = $request->return_remarks;
        }

        $assignment->save();

        // Create history record
        InventoryHistory::create([
            'item_id' => $assignment->item_id,
            'employee_id' => $assignment->employee_id,
            'action_type' => 1,
            'action_date' => now(),
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
