<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leavetype;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveTypeController extends Controller
{

        public function __construct()
{
    // Restrict all Kanban board routes to only Super Admin (categorie 1) and Admin (categorie 3)
    $this->middleware(function ($request, $next) {
        $user = auth()->user();

        // Check user category - only allow 1 (Super Admin) and 3 (Admin)
        if ($user->categorie == 2) { // Employee
            return redirect()->route('emphome')->with('error', 'Access denied .');
        }

        return $next($request);
    })->only(['leavetypeindex']);


}


    // public  function __construct()
    // {
    //     $this->middleware('permission:attendance->addleave view')->only(['leavetypeindex']);
    // }

    private $defaultColumns = [
        'image',
        'employee',
        'department',
        'PL',
        'CL',
        'SL',
        'ML',
        'Comp-off',
        'MAL',
        'PAL',
        'BL',
        'UL',
        'total_days',
        'duration',
        'action'
    ];

    private $allColumns = [
        'employee' => [
            'image' => 'Image',
            'employee' => 'Employee',
            'department' => 'Department'
        ],
        'leave_types' => [
            'PL' => 'PL',
            'CL' => 'CL',
            'SL' => 'SL',
            'UL' => 'UL',
            'ML' => 'ML',
            'Comp-off' => 'Comp-off',
            'MAL' => 'MAL',
            'PAL' => 'PAL',
            'BL' => 'BL'
        ],
        'other' => [
            'duration' => 'Duration',
            'total_days' => 'Total Days',
            'action' => 'Actions'
        ]
    ];

    public function leavetypeindex()
    {
        // Get column preferences from the first leave type record
        $leaveTypePrefs = Leavetype::first();
        $savedPrefs = $leaveTypePrefs && $leaveTypePrefs->column_preferences
            ? json_decode($leaveTypePrefs->column_preferences, true)
            : null;

        // Set default visible columns if none are saved
        $visibleColumns = $savedPrefs['visible_columns'] ?? $this->defaultColumns;
        $columnOrder = $savedPrefs['column_order'] ?? $this->defaultColumns;

        // Get all active leave types with relationships and group by employee
        $groupedleaveTypes = Leavetype::where('delete_status', 1)
            ->with(['employee', 'department'])
            ->get()
            ->groupBy('employee_name_id');

        $employees = Employee::where('delete_status', 1)->with('Departmentid')->get();
        $departments = Department::all();

        // Prepare columns for display in the saved order
        $columns = [];
        foreach ($columnOrder as $key) {
            foreach ($this->allColumns as $category => $items) {
                if (array_key_exists($key, $items)) {
                    $columns[] = [
                        'key' => $key,
                        'label' => $items[$key],
                        'category' => $category
                    ];
                    break;
                }
            }
        }

        return view('dashboard.hr.leave.leavetype.leavetypeindex', [
            'groupedleaveTypes' => $groupedleaveTypes,
            'employees' => $employees,
            'departments' => $departments,
            'columns' => $columns,
            'visibleColumns' => $visibleColumns
        ]);
    }

    public function manageColumns()
    {
        // Get column preferences from the first leave type record
        $leaveTypePrefs = Leavetype::first();
        $savedPrefs = $leaveTypePrefs && $leaveTypePrefs->column_preferences
            ? json_decode($leaveTypePrefs->column_preferences, true)
            : null;

        // Get all possible column keys
        $allColumnKeys = [];
        foreach ($this->allColumns as $category => $columns) {
            $allColumnKeys = array_merge($allColumnKeys, array_keys($columns));
        }

        // Set defaults if no preferences exist
        if (empty($savedPrefs)) {
            $savedPrefs = [
                'visible_columns' => $this->defaultColumns,
                'column_order' => $this->defaultColumns
            ];
        }

        return view('dashboard.hr.leave.leavetype.columnmanageleavetype', [
            'allColumns' => $this->allColumns,
            'savedPrefs' => $savedPrefs,
            'defaultVisible' => $this->defaultColumns
        ]);
    }

    public function saveColumns(Request $request)
    {
        $request->validate([
            'visible_columns' => 'required|array',
            'column_order' => 'required|array'
        ]);

        // Save preferences to the first leave type record (or create one)
        $leaveType = Leavetype::firstOrNew([]);
        $leaveType->column_preferences = json_encode([
            'visible_columns' => $request->visible_columns,
            'column_order' => $request->column_order
        ]);
        $leaveType->save();

        return redirect()->route('leavetype.manage.columns')
            ->with('success', 'Column preferences saved successfully!');
    }

    public function resetColumns()
    {
        // Reset to default columns in leave type record
        $leaveType = Leavetype::firstOrNew([]);
        $leaveType->column_preferences = json_encode([
            'visible_columns' => $this->defaultColumns,
            'column_order' => $this->defaultColumns
        ]);
        $leaveType->save();

        return response()->json([
            'success' => true,
            'message' => 'Columns reset to default successfully'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_start_from'   => 'required|date_format:Y-m-d',
            'leave_end_to'       => 'required|date_format:Y-m-d|after_or_equal:leave_start_from',
            'leave_type_entries' => 'required|json'
        ]);

        $entries = json_decode($request->leave_type_entries, true) ?? [];

        // 1) Detect duplicates first
        $duplicates = [];
        foreach ($entries as $entry) {
            $existing = Leavetype::where('employee_name_id', $entry['employee_name_id'])
                ->where('leavetype_name_id', $entry['leavetype_name_id'])
                ->where('delete_status', 1)
                ->first();

            if ($existing) {
                // (Optional) build a friendlier message payload
                $emp  = Employee::find($entry['employee_name_id']);
                $type = $entry['leavetype_name_id']; // map id -> short name if you like
                $duplicates[] = [
                    'employee'   => $emp->fullname ?? ('#' . $entry['employee_name_id']),
                    'leave_type' => $type,
                ];
            }
        }

        // 2) If any duplicates and overwrite is not requested, stop with a 409
        if (!empty($duplicates) && !$request->boolean('overwrite')) {
            return response()->json([
                'success'    => false,
                'exists'     => true,
                'message'    => 'Some selected leave types already exist and will be overwritten if you continue.',
                'duplicates' => $duplicates,
            ], 409);
        }

        // 3) Proceed (create or overwrite)
        $successCount = 0;
        $errorMessages = [];

        foreach ($entries as $entry) {
            try {
                $existing = Leavetype::where('employee_name_id', $entry['employee_name_id'])
                    ->where('leavetype_name_id', $entry['leavetype_name_id'])
                    ->where('delete_status', 1)
                    ->first();

                if ($existing) {
                    // OVERWRITE
                    $existing->update([
                        'department_name_id' => $entry['department_name_id'],
                        'leave_start_from'   => $request->leave_start_from,
                        'leave_end_to'       => $request->leave_end_to,
                        'leave_days'         => $entry['leave_days'],
                    ]);
                } else {
                    // CREATE
                    Leavetype::create([
                        'employee_name_id'   => $entry['employee_name_id'],
                        'department_name_id' => $entry['department_name_id'],
                        'leavetype_name_id'  => $entry['leavetype_name_id'],
                        'leave_start_from'   => $request->leave_start_from,
                        'leave_end_to'       => $request->leave_end_to,
                        'leavetype_name'     => $entry['leavetype_name_id'], // keep existing behavior
                        'leave_days'         => $entry['leave_days'],
                        'delete_status'      => 1,
                    ]);
                }

                $successCount++;
            } catch (\Exception $e) {
                $errorMessages[] = "Error processing leave type: " . $e->getMessage();
            }
        }

        if ($successCount > 0) {
            $response = [
                'success' => true,
                'message' => "Successfully saved {$successCount} leave type record(s).",
            ];
            if (!empty($errorMessages)) {
                $response['warnings'] = $errorMessages;
            }
            return response()->json($response);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to create any leave type records.',
            'errors'  => $errorMessages
        ], 422);
    }



    public function destroy($id)
    {
        try {
            $count = Leavetype::where('employee_name_id', $id)
                ->update(['delete_status' => 0]);

            return response()->json([
                'success' => true,
                'message' => "Marked $count leave records as deleted for employee"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating leave records: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $leaveType = Leavetype::with(['employee', 'department'])->findOrFail($id);

        return response()->json([
            'leavetype_id' => $leaveType->leavetype_id,
            'employee_name_id' => $leaveType->employee_name_id,
            'department_name_id' => $leaveType->department_name_id,
            'leavetype_name_id' => $leaveType->leavetype_name_id,
            'leave_start_from' => $leaveType->leave_start_from,
            'leave_end_to' => $leaveType->leave_end_to,
            'leave_days' => $leaveType->leave_days,
            'employee' => [
                'fullname' => $leaveType->employee->fullname ?? 'N/A'
            ],
            'department' => [
                'dep_name' => $leaveType->department->dep_name ?? 'N/A'
            ]
        ]);
    }

    public function update(Request $request, $employeeId)
    {
        $validated = $request->validate([
            'leave_start_from' => 'required|date_format:Y-m-d',
            'leave_end_to' => 'required|date_format:Y-m-d|after_or_equal:leave_start_from',
            'leave_type_entries' => 'required|json'
        ]);

        $leaveTypeEntries = json_decode($request->leave_type_entries, true);
        $updatedCount = 0;
        $createdCount = 0;
        $errorMessages = [];

        DB::beginTransaction();
        try {
            // First get all existing leave types for this employee
            $existingLeaves = Leavetype::where('employee_name_id', $employeeId)->get();

            // Process each leave type entry
            foreach ($leaveTypeEntries as $entry) {
                try {
                    // Find if this leave type already exists for this employee
                    $existingLeave = $existingLeaves->firstWhere('leavetype_name_id', $entry['leavetype_name_id']);

                    if ($existingLeave) {
                        // Update existing record
                        $existingLeave->update([
                            'leave_days' => $entry['leave_days'],
                            'leave_start_from' => $request->leave_start_from,
                            'leave_end_to' => $request->leave_end_to,
                            'delete_status' => 1
                        ]);
                        $updatedCount++;
                    } else {
                        // Create new record
                        Leavetype::create([
                            'employee_name_id' => $employeeId,
                            'department_name_id' => $entry['department_name_id'],
                            'leavetype_name_id' => $entry['leavetype_name_id'],
                            'leave_start_from' => $request->leave_start_from,
                            'leave_end_to' => $request->leave_end_to,
                            'leavetype_name' => $entry['leavetype_name_id'], // You might want to map this to actual names
                            'leave_days' => $entry['leave_days'],
                            'delete_status' => 1
                        ]);
                        $createdCount++;
                    }
                } catch (\Exception $e) {
                    $errorMessages[] = "Error processing leave type ID {$entry['leavetype_name_id']}: " . $e->getMessage();
                }
            }

            // Delete any leave types that weren't included in the update
            $includedTypes = collect($leaveTypeEntries)->pluck('leavetype_name_id');
            $deletedCount = Leavetype::where('employee_name_id', $employeeId)
                ->whereNotIn('leavetype_name_id', $includedTypes)
                ->delete();

            DB::commit();

            $response = [
                'success' => true,
                'message' => "Leave types updated successfully. Updated: {$updatedCount}, Created: {$createdCount}, Deleted: {$deletedCount}"
            ];

            if (!empty($errorMessages)) {
                $response['warnings'] = $errorMessages;
            }

            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update leave types',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
