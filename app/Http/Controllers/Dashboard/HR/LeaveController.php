<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\Leavetype;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class LeaveController extends Controller
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
    })->only(['leaveindex']);


}


    // public  function __construct()
    // {
    //     $this->middleware('permission:attendance->assignleave view')->only(['leaveindex']);
    // }


    public function getEmployeeHolidays(Request $request)
    {
        $employeeId = $request->input('employee_id');
        $start = $request->input('start');
        $end = $request->input('end');
        $date = $request->input('date');

        // Get employee department
        $employee = Employee::find($employeeId);
        if (!$employee) {
            return response()->json([]);
        }

        $employeeDepartmentId = $employee->cur_department;

        $query = Holiday::where('delete_status', 1);

        // Apply date filters
        if ($start && $end) {
            $query->whereBetween('holiday_date', [$start, $end]);
        } elseif ($date) {
            $query->where('holiday_date', $date);
        } else {
            // Default to next 30 days if no date range provided
            $query->whereBetween('holiday_date', [now(), now()->addDays(30)]);
        }

        // Filter by department - include holidays that apply to the employee's department
        // or holidays that apply to all departments (if holiday_department is empty or null)
        $query->where(function ($q) use ($employeeDepartmentId) {
            $q->whereNull('holiday_department')
                ->orWhere('holiday_department', '')
                ->orWhereRaw("FIND_IN_SET(?, holiday_department)", [$employeeDepartmentId]);
        });

        $holidays = $query->orderBy('holiday_date', 'asc')
            ->get()
            ->map(function ($holiday) {
                return [
                    'id' => $holiday->holiday_id,
                    'name' => $holiday->occasion,
                    'date' => $holiday->holiday_date,
                    'type' => $holiday->holidayType ? $holiday->holidayType->holidaytype_name : 'Unknown'
                ];
            });

        return response()->json($holidays);
    }

    // In your LeaveController or HolidayController
    public function checkHoliday(Request $request)
    {
        $date = $request->input('date');
        $employeeId = $request->input('employee_id');

        if (!$date) {
            return response()->json(['is_holiday' => false]);
        }

        // Check if it's Sunday
        $dateObj = Carbon::parse($date);
        if ($dateObj->isSunday()) {
            return response()->json([
                'is_holiday' => true,
                'holiday_name' => 'Sunday',
                'reason' => 'Sunday'
            ]);
        }

        // Get employee department
        $employee = Employee::find($employeeId);
        if (!$employee) {
            return response()->json(['is_holiday' => false]);
        }

        $employeeDepartmentId = $employee->cur_department;

        // Check if it's a holiday for the employee's department
        $holiday = Holiday::where('holiday_date', $date)
            ->where('delete_status', 1)
            ->where(function ($query) use ($employeeDepartmentId) {
                $query->whereNull('holiday_department')
                    ->orWhere('holiday_department', '')
                    ->orWhereRaw("FIND_IN_SET(?, holiday_department)", [$employeeDepartmentId]);
            })
            ->first();

        if ($holiday) {
            return response()->json([
                'is_holiday' => true,
                'holiday_name' => $holiday->occasion,
                'reason' => 'Holiday'
            ]);
        }

        return response()->json(['is_holiday' => false]);
    }

    // Update the isDateUnavailable method to consider department
    // Update the isDateUnavailable method to consider department and day-off shifts
    private function isDateUnavailable($date, $employeeId = null)
    {
        $dateObj = \Carbon\Carbon::parse($date);

        // Check if Sunday
        if ($dateObj->isSunday()) {
            return true;
        }

        // Check if it's a day-off shift
        if ($employeeId && $this->isDayOffShift($date, $employeeId)) {
            return true;
        }

        // If no employee ID provided, check for any holiday
        if (!$employeeId) {
            return Holiday::where('holiday_date', $date)
                ->where('delete_status', 1)
                ->exists();
        }

        // Get employee department
        $employee = Employee::find($employeeId);
        if (!$employee) {
            return false;
        }

        $employeeDepartmentId = $employee->cur_department;

        // Check if it's a holiday for the employee's department
        return Holiday::where('holiday_date', $date)
            ->where('delete_status', 1)
            ->where(function ($query) use ($employeeDepartmentId) {
                $query->whereNull('holiday_department')
                    ->orWhere('holiday_department', '')
                    ->orWhereRaw("FIND_IN_SET(?, holiday_department)", [$employeeDepartmentId]);
            })
            ->exists();
    }
public function getLeaveTypesByEmployee($employeeId)
{
    // Get the employee
    $employee = Employee::find($employeeId);

    if (!$employee) {
        return response()->json([
            'success' => false,
            'message' => 'Employee not found'
        ], 404);
    }

    // Define the 3 leave types only (PL, CL, SL)
    $leaveTypesData = [
        1 => ['name' => 'Privilege Leave (PL)', 'default_days' => 12],
        2 => ['name' => 'Casual Leave (CL)', 'default_days' => 12],
        3 => ['name' => 'Sick Leave (SL)', 'default_days' => 12]
    ];

    $result = [];

    foreach ($leaveTypesData as $id => $type) {
        // Calculate used days for this leave type for this specific employee
        $usedDays = Leave::where('employee_id', $employeeId)
            ->where('leave_type_id', $id)
            ->where('leave_status', '!=', 3) // Not rejected
            ->count();

        $remainingDays = $type['default_days'] - $usedDays;

        // Get date range from leave types table (if exists, otherwise use defaults)
        $leaveTypeRecord = Leavetype::where('delete_status', 1)
            ->where('leavetype_name_id', $id)
            ->first();

        $startDate = $leaveTypeRecord->leave_start_from ?? date('Y-m-d');
        $endDate = $leaveTypeRecord->leave_end_to ?? date('Y-m-d', strtotime('+1 year'));

        $result[] = [
            'id' => $id,
            'text' => $type['name'] .
                " [{$remainingDays}/{$type['default_days']} days remaining] " .
                "[" . date('M', strtotime($startDate)) . " - " .
                date('M', strtotime($endDate)) . "]",
            'leave_days' => $type['default_days'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'remaining_days' => $remainingDays
        ];
    }

    return response()->json($result);
}
    public function leaveindex()
    {
        $employees = Employee::where('delete_status', 1)
            ->with('Departmentid')
            ->get();

        $departments = Department::where('delete_status', 1)->get();

        return view('dashboard.hr.leave.index', compact('employees', 'departments'));
    }

    public function checkExistingLeaves(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,emp_id',
            'dates' => 'required|array',
            'current_leave_id' => 'nullable|exists:leaves,leave_id'
        ]);

        $employeeId = $request->employee_id;
        $dates = $request->dates;
        $currentLeaveId = $request->current_leave_id;

        $existingLeaves = Leave::where('employee_id', $employeeId)
            ->where(function ($query) use ($dates) {
                foreach ($dates as $date) {
                    $query->orWhere('leavedate_no', $date)
                        ->orWhere(function ($q) use ($date) {
                            $q->where('leavedaterange_from', '<=', $date)
                                ->where('leavedaterange_to', '>=', $date);
                        });
                }
            });

        if ($currentLeaveId) {
            $existingLeaves->where('leave_id', '!=', $currentLeaveId);
        }

        $results = $existingLeaves->get();

        return response()->json([
            'exists' => $results->count() > 0,
            'leaves' => $results,
            'count' => $results->count()
        ]);
    }




   public function getLeaveData(Request $request)
{
    $adminLeavesQuery = Leave::with(['employees' => function ($query) {
        $query->with('Departmentid');
    }, 'leavetype'])
        ->whereHas('employees', function ($q) {
            $q->whereHas('user', function ($userQuery) {
                $userQuery->role('Admin');
            });
        })
        ->orderBy('created_at', 'desc');

    $employeeLeavesQuery = Leave::with(['employees' => function ($query) {
        $query->with('Departmentid');
    }, 'leavetype'])
        ->whereHas('employees', function ($q) {
            $q->whereDoesntHave('user', function ($userQuery) {
                $userQuery->role('Admin');
            });
        })
        ->where('leave_type_id', '>', 0)
        ->orderBy('created_at', 'desc');

    $absentLeavesQuery = Leave::with(['employees' => function ($query) {
        $query->with('Departmentid');
    }, 'leavetype'])
        ->whereHas('employees', function ($q) {
            $q->whereDoesntHave('user', function ($userQuery) {
                $userQuery->role('Admin');
            });
        })
        ->where('leave_type_id', 0)
        ->orderBy('created_at', 'desc');

    if ($request->has('employee') && $request->employee) {
        $adminLeavesQuery->where('employee_id', $request->employee);
        $employeeLeavesQuery->where('employee_id', $request->employee);
        $absentLeavesQuery->where('employee_id', $request->employee);
    }

    if ($request->has('department') && $request->department) {
        $adminLeavesQuery->whereHas('employees.Departmentid', function ($q) use ($request) {
            $q->where('dep_name', $request->department);
        });
        $employeeLeavesQuery->whereHas('employees.Departmentid', function ($q) use ($request) {
            $q->where('dep_name', $request->department);
        });
        $absentLeavesQuery->whereHas('employees.Departmentid', function ($q) use ($request) {
            $q->where('dep_name', $request->department);
        });
    }

    if ($request->has('dateRange') && $request->dateRange) {
        $adminLeavesQuery->where(function ($q) use ($request) {
            $q->whereBetween('leavedate_no', [$request->dateRange['from'], $request->dateRange['to']])
                ->orWhereBetween('leavedaterange_from', [$request->dateRange['from'], $request->dateRange['to']])
                ->orWhereBetween('leavedaterange_to', [$request->dateRange['from'], $request->dateRange['to']]);
        });

        $employeeLeavesQuery->where(function ($q) use ($request) {
            $q->whereBetween('leavedate_no', [$request->dateRange['from'], $request->dateRange['to']])
                ->orWhereBetween('leavedaterange_from', [$request->dateRange['from'], $request->dateRange['to']])
                ->orWhereBetween('leavedaterange_to', [$request->dateRange['from'], $request->dateRange['to']]);
        });

        $absentLeavesQuery->where(function ($q) use ($request) {
            $q->whereBetween('leavedate_no', [$request->dateRange['from'], $request->dateRange['to']])
                ->orWhereBetween('leavedaterange_from', [$request->dateRange['from'], $request->dateRange['to']])
                ->orWhereBetween('leavedaterange_to', [$request->dateRange['from'], $request->dateRange['to']]);
        });
    }

    if ($request->has('status') && $request->status) {
        $adminLeavesQuery->where('leave_status', $request->status);
        $employeeLeavesQuery->where('leave_status', $request->status);
        $absentLeavesQuery->where('leave_status', $request->status);
    }

    $adminLeaves = $adminLeavesQuery->get();
    $employeeLeaves = $employeeLeavesQuery->get();
    $absentLeaves = $absentLeavesQuery->get();

    return response()->json([
        'success' => true,
        'admin_leaves' => $this->processLeaves($adminLeaves),
        'employee_leaves' => $this->processLeaves($employeeLeaves),
        'absent_leaves' => $this->processLeaves($absentLeaves)
    ]);
}
// In your LeaveController.php, update the markPresentFromAbsent method
public function markPresentFromAbsent(Request $request)
{
    $request->validate([
        'employee_id' => 'required|exists:employees,emp_id',
        'date' => 'required|date'
    ]);

    try {
        $employee = Employee::find($request->employee_id);

        // Parse the date correctly - remove timezone if present
        $date = Carbon::parse($request->date)->format('Y-m-d');

        // Check if attendance already exists for this date
        $attendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('attendancedate_no', $date)
            ->first();

        if ($attendance) {
            // Update existing attendance to present
            $attendance->update([
                'attendance_type' => 1, // 1 = present
                'half_day_type' => null,
                'clock_in' => now()->format('H:i:s'),
                'clock_out' => now()->format('H:i:s'),
                'attendance_empname' => $employee->emp_id,
                'attendance_depname' => $employee->Departmentid->dep_id ?? null,
            ]);
        } else {
            // Create new attendance record
            Attendance::create([
                'employee_id' => $request->employee_id,
                'attendancedate_no' => $date,
                'attendance_type' => 1, // 1 = present
                'clock_in' => now()->format('H:i:s'),
                'clock_out' => now()->format('H:i:s'),
                'attendance_empname' => $employee->emp_id,
                'attendance_depname' => $employee->Departmentid->dep_id ?? null,
                'attendance_loc' => $employee->Branchid->branch_id ?? null,
                'delete_status' => 1
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Attendance marked as present successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error marking attendance: ' . $e->getMessage()
        ], 500);
    }
}
public function updateLeaveType(Request $request, $id)
{
    $leave = Leave::find($id);
    if (!$leave) {
        return response()->json(['success' => false, 'message' => 'Leave not found'], 404);
    }

    $request->validate([
        'leave_type_id' => 'required|exists:leavetypes,leavetype_id',
    ]);

    try {
        // If the leave was absent (leave_type_id = 0), also set status to approved
        $updateData = [
            'leave_type_id' => $request->leave_type_id,
        ];

        // If this was an absent record (leave_type_id was 0), set status to approved
        if ($leave->leave_type_id == 0) {
            $updateData['leave_status'] = 1; // Approved

            // Also create attendance record for this approved leave
            $employee = Employee::find($leave->employee_id);
            if ($employee) {
                $this->createAttendanceForLeave($leave, $employee);
            }
        }

        $leave->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Leave type updated successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error updating leave type: ' . $e->getMessage()
        ], 500);
    }
}
    private function processLeaves($leaves)
    {
        // Your existing grouping logic
        $groupedLeaves = [];
        foreach ($leaves as $leave) {
            if ($leave->select_duration == 2) {
                $key = $leave->employee_id . '-' . $leave->leavedaterange_from . '-' . $leave->leavedaterange_to;
                if (!isset($groupedLeaves[$key])) {
                    $groupedLeaves[$key] = $leave;
                    $groupedLeaves[$key]->days_count = 0;
                }
                $groupedLeaves[$key]->days_count++;
            } else {
                $groupedLeaves[] = $leave;
            }
        }

        return array_values($groupedLeaves);
    }

    public function edit($id)
    {
        $leave = Leave::with(['employees.Departmentid', 'leavetype'])->find($id);

        if (!$leave) {
            return response()->json([
                'success' => false,
                'message' => 'Leave not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'leave' => $leave
        ]);
    }

    public function approveLeave($id)
    {
        $leave = Leave::find($id);

        if (!$leave) {
            return response()->json([
                'success' => false,
                'message' => 'Leave not found'
            ], 404);
        }

        // If it's a multiple day leave, approve all related leaves
        if ($leave->select_duration == 2) {
            $relatedLeaves = Leave::where('employee_id', $leave->employee_id)
                ->where('leavedaterange_from', $leave->leavedaterange_from)
                ->where('leavedaterange_to', $leave->leavedaterange_to)
                ->get();

            foreach ($relatedLeaves as $relatedLeave) {
                $relatedLeave->update(['leave_status' => 1]);

                // Update attendance for each approved leave
                $employee = Employee::with(['Departmentid', 'Branchid'])->find($relatedLeave->employee_id);
                $this->updateAttendanceForLeave($relatedLeave, $employee);
            }

            return response()->json([
                'success' => true,
                'message' => 'All related leaves approved successfully'
            ]);
        } else {
            $leave->update(['leave_status' => 1]);

            // Update attendance for the approved leave
            $employee = Employee::with(['Departmentid', 'Branchid'])->find($leave->employee_id);
            $this->updateAttendanceForLeave($leave, $employee);

            return response()->json([
                'success' => true,
                'message' => 'Leave approved successfully'
            ]);
        }
    }

    public function rejectLeave($id)
    {
        $leave = Leave::find($id);

        if (!$leave) {
            return response()->json([
                'success' => false,
                'message' => 'Leave not found'
            ], 404);
        }

        // If it's a multiple day leave, reject all related leaves
        if ($leave->select_duration == 2) {
            $relatedLeaves = Leave::where('employee_id', $leave->employee_id)
                ->where('leavedaterange_from', $leave->leavedaterange_from)
                ->where('leavedaterange_to', $leave->leavedaterange_to)
                ->get();

            foreach ($relatedLeaves as $relatedLeave) {
                $relatedLeave->update(['leave_status' => 3]);

                // Remove attendance records for rejected leaves
                Attendance::where('employee_id', $relatedLeave->employee_id)
                    ->whereDate('attendancedate_no', $relatedLeave->leavedate_no)
                    ->where('attendance_type', 5)
                    ->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'All related leaves rejected successfully'
            ]);
        } else {
            $leave->update(['leave_status' => 3]);

            // Remove attendance record for rejected leave
            Attendance::where('employee_id', $leave->employee_id)
                ->whereDate('attendancedate_no', $leave->leavedate_no)
                ->where('attendance_type', 5)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Leave rejected successfully'
            ]);
        }
    }

    public function store(Request $request)
{
    // Check if it's a single date assignment (from attendance modal)
    if ($request->has('leavedate_no') && !$request->has('available_dates')) {
        $request->validate([
            'employee_id' => 'required|exists:employees,emp_id',
            'leave_type_id' => 'required|exists:leavetypes,leavetype_id',
            'leavedate_no' => 'required|date',
            'duration' => 'required|in:1,2,3,4',
            'reason' => 'required|string',
            'status' => 'nullable|in:1,2,3'
        ]);

        // Use leavedate_no directly
        $dates = [$request->leavedate_no];
    } else {
        $request->validate([
            'employee_id' => 'required|exists:employees,emp_id',
            'leave_type_id' => 'required|exists:leavetypes,leavetype_id',
            'duration' => 'required|in:1,2,3,4',
            'reason' => 'required|string',
            'available_dates' => 'nullable|json',
        ]);

        $dates = $request->has('available_dates') ? json_decode($request->available_dates, true) : [];
        if (!is_array($dates)) $dates = [];
    }

    $leaveType = Leavetype::find($request->leave_type_id);

    // Check remaining days
    if ($leaveType) {
        $usedDays = Leave::where('employee_id', $request->employee_id)
            ->where('leave_type_id', $leaveType->leavetype_id)
            ->where('leave_status', '!=', 3)
            ->whereBetween('leavedate_no', [$leaveType->leave_start_from, $leaveType->leave_end_to])
            ->count();

        $remainingDays = $leaveType->leave_days - $usedDays;

        if ($request->duration == 2 && count($dates) > $remainingDays) {
            return response()->json([
                'success' => false,
                'message' => "Cannot assign leave - requested " . count($dates) . " days exceeds remaining {$remainingDays} days"
            ]);
        } elseif (($request->duration == 3 || $request->duration == 4) && $remainingDays < 0.5) {
            return response()->json([
                'success' => false,
                'message' => "Cannot assign leave - not enough remaining days for this leave type"
            ]);
        } elseif ($request->duration == 1 && $remainingDays <= 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot assign leave - no remaining days available for this leave type"
            ]);
        }
    }

    try {
        // Handle file upload safely
        $fileData = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if ($file->isValid()) {
                $destinationPath = public_path('leave_files');
                if (!file_exists($destinationPath)) mkdir($destinationPath, 0777, true);

                $filename = now()->format('YmdHis') . '.' .  $file->getClientOriginalExtension();
                $file->move($destinationPath, $filename);

                $fileData = [
                    'name' => $filename,
                    'type' => $file->getClientMimeType(),
                    'size' => filesize($destinationPath . '/' . $filename),
                    'url'  => asset('leave_files/' . $filename),
                    'path' => 'leave_files/' . $filename,
                ];
            }
        }

        $employee = Employee::find($request->employee_id);

        // Filter out day-off shifts
        $filteredDates = [];
        foreach ($dates as $date) {
            if (!$this->isDayOffShift($date, $request->employee_id)) {
                $filteredDates[] = $date;
            }
        }

        // If no dates after filtering and we have a single date request
        if (empty($filteredDates) && $request->has('leavedate_no')) {
            $filteredDates[] = $request->leavedate_no;
        }

        // Fallback for date_range_from if still empty
        if (empty($filteredDates) && $request->filled('date_range_from')) {
            $filteredDates[] = $request->date_range_from;
        }

        foreach ($filteredDates as $date) {
            $leave = Leave::create([
                'employee_id' => $request->employee_id,
                'member' => $request->employee_id,
                'leave_type_id' => $request->leave_type_id,
                'select_duration' => $request->duration,
                'leave_status' => $request->status ?? 1, // Use provided status or default to approved
                'reason_forleave' => $request->reason,
                'leave_file' => $fileData ? json_encode($fileData) : null,
                'leavedaterange_from' => $request->date_range_from ?? $date,
                'leavedaterange_to' => $request->date_range_to ?? $date,
                'leavedate_no' => $date,
                'attend_id' => $request->attendance_id ?? null,
            ]);

            // If status is approved (1), create attendance record
            if (($request->status ?? 1) == 1) {
                $this->createAttendanceForLeave($leave, $employee);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Leave saved successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error saving leave: ' . $e->getMessage()
        ], 500);
    }
}

    // --- UPDATE LEAVE ---
    public function update(Request $request, $id)
    {
        $leave = Leave::find($id);
        if (!$leave) return response()->json(['success' => false, 'message' => 'Leave not found'], 404);

        $request->validate([
            'leave_type_id' => 'required|exists:leavetypes,leavetype_id',
            'duration' => 'required|in:1,2,3,4',
            'reason' => 'required|string',
            'dates' => 'nullable|json',
        ]);

        $leaveType = Leavetype::find($request->leave_type_id);
        if ($leaveType) {
            $usedDays = Leave::where('employee_id', $leave->employee_id)
                ->where('leave_type_id', $leaveType->leavetype_id)
                ->where('leave_status', '!=', 3)
                ->where('leave_id', '!=', $id)
                ->whereBetween('leavedate_no', [$leaveType->leave_start_from, $leaveType->leave_end_to])
                ->count();
            $remainingDays = $leaveType->leave_days - $usedDays;

            $dates = $request->has('dates') ? json_decode($request->dates, true) : [];
            $filteredDates = [];
            foreach ($dates as $date) {
                if (!$this->isDayOffShift($date, $leave->employee_id)) $filteredDates[] = $date;
            }

            if ($request->duration == 2 && count($filteredDates) > $remainingDays) {
                return response()->json(['success' => false, 'message' => "Cannot update leave - requested " . count($filteredDates) . " days exceeds remaining {$remainingDays} days"]);
            }
        }

        try {
            // Handle file upload
            $fileData = null;
            if ($leave->leave_file) $fileData = json_decode($leave->leave_file, true);

            // Delete file if requested
            if ($request->has('delete_file') && $request->delete_file && $fileData && file_exists(public_path($fileData['path']))) {
                unlink(public_path($fileData['path']));
                $fileData = null;
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                if ($file->isValid()) {
                    // Delete old file
                    if ($fileData && file_exists(public_path($fileData['path']))) unlink(public_path($fileData['path']));

                    $destinationPath = public_path('leave_files');
                    if (!file_exists($destinationPath)) mkdir($destinationPath, 0777, true);

                    $filename = now()->format('YmdHis') . '.' .  $file->getClientOriginalExtension();
                    $file->move($destinationPath, $filename);

                    $fileData = [
                        'name' => $filename,
                        'type' => $file->getClientMimeType(),
                        'size' => filesize($destinationPath . '/' . $filename),
                        'url' => asset('leave_files/' . $filename),
                        'path' => 'leave_files/' . $filename,
                    ];
                }
            }

            $leave->update([
                'leave_type_id' => $request->leave_type_id,
                'select_duration' => $request->duration,
                'reason_forleave' => $request->reason,
                'leave_file' => $fileData ? json_encode($fileData) : null,
                'leavedaterange_from' => $request->date_range_from ?? $leave->leavedate_no,
                'leavedaterange_to' => $request->date_range_to ?? $leave->leavedate_no,
                'leavedate_no' => $request->date ?? $leave->leavedate_no,
            ]);

            return response()->json(['success' => true, 'message' => 'Leave updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error updating leave: ' . $e->getMessage()], 500);
        }
    }

    // --- DESTROY LEAVE ---
    public function destroy($id)
    {
        $leave = Leave::find($id);
        if (!$leave) return response()->json(['success' => false, 'message' => 'Leave not found'], 404);

        try {
            // Delete associated attendance
            Attendance::where('employee_id', $leave->employee_id)
                ->whereDate('attendancedate_no', $leave->leavedate_no)
                ->where('attendance_type', 5)
                ->delete();

            // Delete file
            if ($leave->leave_file) {
                $files = json_decode($leave->leave_file, true);
                if ($files && isset($files['path']) && file_exists(public_path($files['path']))) {
                    unlink(public_path($files['path']));
                }
            }

            $leave->delete();

            return response()->json(['success' => true, 'message' => 'Leave deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting leave: ' . $e->getMessage()], 500);
        }
    }



    public function show($id)
    {
        $leave = Leave::with(['employees.Departmentid', 'leavetype'])->find($id);

        if (!$leave) {
            return response()->json([
                'success' => false,
                'message' => 'Leave not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'leave' => $leave
        ]);
    }

    protected function createAttendanceForLeave(Leave $leave, Employee $employee)
    {
        if ($leave->leave_status != 1) { // Only create attendance for approved leaves
            return;
        }

        $attendanceType = 5; // Default for full day leave
        $halfDayType = null;

        if ($leave->select_duration == 3) { // First half
            $attendanceType = 3;
            $halfDayType = 0;
        } elseif ($leave->select_duration == 4) { // Second half
            $attendanceType = 3;
            $halfDayType = 1;
        }

        // Create or update attendance record
        Attendance::updateOrCreate(
            [
                'employee_id' => $leave->employee_id,
                'attendancedate_no' => $leave->leavedate_no
            ],
            [
                'attendance_type' => $attendanceType,
                'half_day_type' => $halfDayType,
                'clock_in' => null,
                'clock_out' => null,
                'attendance_empname' => $employee->emp_id,
                'attendance_depname' => $employee->Departmentid->dep_id ?? null,
                'attendance_depadmin' => $this->getDepartmentAdminId($employee->Departmentid->dep_id ?? null),
                'attendance_loc' => $employee->Branchid->branch_id ?? null,
                'delete_status' => 1
            ]
        );
    }

    protected function updateAttendanceForLeave(Leave $leave, Employee $employee)
    {
        if ($leave->leave_status != 1) { // Only update attendance for approved leaves
            return;
        }

        // Determine attendance type based on leave duration
        $attendanceType = 5; // Default for full day leave
        $halfDayType = null;

        if ($leave->select_duration == 3) { // First half
            $attendanceType = 3;
            $halfDayType = 0;
        } elseif ($leave->select_duration == 4) { // Second half
            $attendanceType = 3;
            $halfDayType = 1;
        }

        $attendance = Attendance::where('employee_id', $leave->employee_id)
            ->whereDate('attendancedate_no', $leave->leavedate_no)
            ->first();

        if ($attendance) {
            $attendance->update([
                'attendance_type' => $attendanceType,
                'half_day_type' => $halfDayType,
            ]);
        } else {
            $this->createAttendanceForLeave($leave, $employee);
        }
    }

    protected function getDepartmentAdminId($departmentId)
    {
        $admin = User::role('Admin')
            ->whereHas('employee', function ($q) use ($departmentId) {
                $q->where('cur_department', $departmentId);
            })->first();

        return $admin?->id ?? User::role('Super admin')->pluck('id')->first();
    }

    private function getDatesBetween($startDate, $endDate)
    {
        $dates = [];
        $current = strtotime($startDate);
        $end = strtotime($endDate);

        while ($current <= $end) {
            $dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }

        return $dates;
    }



    public function getLeaveDates($id)
    {
        $leave = Leave::with(['employees.Departmentid'])->find($id);

        if (!$leave) {
            return response()->json([
                'success' => false,
                'message' => 'Leave not found'
            ], 404);
        }

        // For multiple day leaves, get all dates in the range
        if ($leave->select_duration == 2) {
            $dates = [];
            $startDate = Carbon::parse($leave->leavedaterange_from);
            $endDate = Carbon::parse($leave->leavedaterange_to);

            // Get all dates in the range
            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                $dateStr = $date->format('Y-m-d');

                // Check if it's a holiday, weekend, or day-off
                $isHoliday = $this->isDateUnavailable($dateStr, $leave->employee_id);
                $isSunday = $date->isSunday();

                // NEW: Check if it's a day-off shift
                $isDayOff = $this->isDayOffShift($dateStr, $leave->employee_id);

                $dateType = 'working-day';
                $holidayName = null;
                $holidayType = null;

                if ($isHoliday && !$isSunday && !$isDayOff) {
                    $dateType = 'holiday';
                    // Get holiday details
                    $holiday = Holiday::where('holiday_date', $dateStr)
                        ->where('delete_status', 1)
                        ->first();
                    if ($holiday) {
                        $holidayName = $holiday->occasion;
                        $holidayType = $holiday->holidayType ? $holiday->holidayType->holidaytype_name : 'Holiday';
                    }
                } elseif ($isSunday) {
                    $dateType = 'weekend';
                    $holidayName = 'Sunday';
                    $holidayType = 'Weekend Holiday';
                } elseif ($isDayOff) {
                    $dateType = 'dayoff';
                    $holidayName = 'Day Off';
                    $holidayType = 'Scheduled Day Off';
                }

                $dates[] = [
                    'date' => $dateStr,
                    'formatted_date' => $date->format('d-m-Y (l)'),
                    'type' => $dateType,
                    'holiday_name' => $holidayName,
                    'holiday_type' => $holidayType
                ];
            }

            return response()->json([
                'success' => true,
                'dates' => $dates
            ]);
        } else {
            // For single day leaves, just return that date
            $date = Carbon::parse($leave->leavedate_no);
            $dateStr = $date->format('Y-m-d');

            // Check if it's a holiday, weekend, or day-off
            $isHoliday = $this->isDateUnavailable($dateStr, $leave->employee_id);
            $isSunday = $date->isSunday();
            $isDayOff = $this->isDayOffShift($dateStr, $leave->employee_id);

            $dateType = 'working-day';
            $holidayName = null;
            $holidayType = null;

            if ($isHoliday && !$isSunday && !$isDayOff) {
                $dateType = 'holiday';
                // Get holiday details
                $holiday = Holiday::where('holiday_date', $dateStr)
                    ->where('delete_status', 1)
                    ->first();
                if ($holiday) {
                    $holidayName = $holiday->occasion;
                    $holidayType = $holiday->holidayType ? $holiday->holidayType->holidaytype_name : 'Holiday';
                }
            } elseif ($isSunday) {
                $dateType = 'weekend';
                $holidayName = 'Sunday';
                $holidayType = 'Weekend Holiday';
            } elseif ($isDayOff) {
                $dateType = 'dayoff';
                $holidayName = 'Day Off';
                $holidayType = 'Scheduled Day Off';
            }

            $dates = [[
                'date' => $dateStr,
                'formatted_date' => $date->format('d-m-Y (l)'),
                'type' => $dateType,
                'holiday_name' => $holidayName,
                'holiday_type' => $holidayType
            ]];

            return response()->json([
                'success' => true,
                'dates' => $dates
            ]);
        }
    }

    // NEW: Helper method to check if a date is a day-off shift
    private function isDayOffShift($date, $employeeId)
    {
        $shift = Shift::where('employee_id', $employeeId)
            ->where('date_no', $date)
            ->first();

        return $shift && $shift->shift_type == 3; // 3 = dayoff
    }

    public function checkDayOff(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,emp_id',
            'date' => 'required|date'
        ]);

        $shift = Shift::where('employee_id', $request->employee_id)
            ->where('date_no', $request->date)
            ->first();

        return response()->json([
            'is_dayoff' => $shift && $shift->shift_type == 3, // 3 = dayoff
            'date' => $request->date
        ]);
    }
}
