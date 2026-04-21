<?php

namespace App\Http\Controllers\Dashboard\HR;


use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use App\Imports\AttendanceImport;
use App\Models\Leave;
use Barryvdh\DomPDF\Facade\Pdf;


class AttendanceController extends Controller
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
    })->only(['attendancelist']);


}






   public function attendlist()
{
    // $employees = Employee::where('delete_status', 1)
    //     ->with('Departmentid')
    //     ->get();


    $employees = Employee::where('delete_status', 1)
    ->whereDoesntHave('resignation')
    ->with('Departmentid')
    ->get();
    $locations = Branch::where('delete_status', 1)->get();

    // Get all shifts grouped by employee_id and date
    $shifts = Shift::where('delete_status', 1)
        ->get()
        ->groupBy(['employee_id', function ($shift) {
            return Carbon::parse($shift->date_no)->format('Y-m-d');
        }])
        ->map(function ($employeeShifts) {
            return $employeeShifts->map(function ($shift) {
                return $shift->first();
            });
        });

    $departments = Department::where('delete_status', 1)->get()
        ->map(function ($department) {
            $admin = User::role('Admin')
                ->whereHas('employee', function ($q) use ($department) {
                    $q->where('cur_department', $department->dep_id);
                })
                ->with('employee')
                ->first();

            if ($admin && $admin->employee) {
                $department->admin_name = $admin->employee->fullname . ' [Admin]';
                return $department;
            }

            $superadmin = User::role('Super admin')->first();
            if ($superadmin) {
                $department->admin_name = $superadmin->name . ' [Super admin]';
                return $department;
            }

            $department->admin_name = 'No admin assigned';
            return $department;
        });

    $superadmin = User::role('Super admin')->first();
    $superAdminName = $superadmin ? $superadmin->name . ' [Super admin]' : 'No admin assigned';


    // FIX 1: Fetch holidays for the entire current year
    $currentYear = now()->year;
  $holidays = Shift::where('shift_type', 4)
    ->where('delete_status', 1)
    ->get()
    ->groupBy(function ($item) {
        return $item->employee_id . '_' . $item->date_no;
    });

    $approvedLeaves = Leave::where('leave_status', 1)
        ->where('delete_status', 1)
        ->with('leavetype')
        ->get()
        ->groupBy(['employee_id', function ($leave) {
            return Carbon::parse($leave->leavedate_no)->format('Y-m-d');
        }])
        ->map(function ($employeeLeaves) {
            return $employeeLeaves->map(function ($leaves) {
                return $leaves->first();
            });
        });

    return view('dashboard.hr.attendance.index', compact(
        'employees',
        'departments',
        'superAdminName',
        'locations',
        'shifts',
        'holidays',
        'approvedLeaves'
    ));
}
    public function getAttendances()
    {
        $attendances = Attendance::with('employees', 'branchid')->get();
        return response()->json($attendances);
    }


    protected function createLeaveForAbsentAttendance(Attendance $attendance)
    {
        // Only create leave if attendance type is absent (0)
        if ($attendance->attendance_type != 0) {
            return;
        }

        // Check if leave already exists for this date and employee
        $existingLeave = Leave::where('employee_id', $attendance->employee_id)
            ->whereDate('leavedate_no', $attendance->attendancedate_no)
            ->first();

        if ($existingLeave) {
            return;
        }

        // Create the leave record
        Leave::create([
            'employee_id' => $attendance->employee_id,
            'member' => $attendance->attendance_empname,
            'leave_type_id' => 0, // You may want to create a special leave type for absences
            'select_duration' => 1, // Single day
            'leave_status' => 2, // Pending
            'reason_forleave' => 'Absent marked in attendance',
            'leavedate_no' => $attendance->attendancedate_no,
            'attend_id' => $attendance->attendance_id
        ]);
    }
    public function attend_create(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,emp_id',
            'attendancedate_no' => 'required|date',
            'attendance_type' => 'required|in:0,1,2,3',
            'clock_in' => 'required_if:attendance_type,1,2,3',
            'clock_out' => 'required_if:attendance_type,1,2,3',
            'attendance_workfrom' => 'nullable|in:0,1',
            'half_day_type' => 'nullable'
        ]);

        try {
            DB::beginTransaction();

            $employee = Employee::with(['Departmentid', 'Branchid'])->findOrFail($request->employee_id);

            $shift = Shift::where('employee_id', $request->employee_id)
    ->where('date_no', $request->attendancedate_no)
    ->first();

$wasHoliday = $shift && $shift->shift_type == 4;

            // Convert times from 12-hour to 24-hour format
            $clockIn = $request->clock_in ? Carbon::createFromFormat('h:i A', $request->clock_in)->format('H:i:s') : null;
            $clockOut = $request->clock_out ? Carbon::createFromFormat('h:i A', $request->clock_out)->format('H:i:s') : null;

            // Get shift times for this employee and date
            $shift = Shift::where('employee_id', $request->employee_id)
                ->where('date_no', $request->attendancedate_no)
                ->first();

            // Calculate early clock in/out if shift exists
            $earlyClockIn = null;
            $earlyClockOut = null;

            if ($shift && $clockIn && $clockOut) {
                $shiftStart = Carbon::parse($shift->shift_from_time);
                $shiftEnd = Carbon::parse($shift->shift_to_time);
                $clockInTime = Carbon::parse($clockIn);
                $clockOutTime = Carbon::parse($clockOut);

                if ($clockInTime->lt($shiftStart)) {
                    $earlyClockIn = $clockInTime->diff($shiftStart)->format('%H:%I:%S');
                }

                if ($clockOutTime->lt($shiftEnd)) {
                    $earlyClockOut = $clockOutTime->diff($shiftEnd)->format('%H:%I:%S');
                }
            }
                $clockInIp  = $request->clock_in_ip;
$clockOutIp = $request->clock_out_ip;

// Prefer clock-in IP
$attendanceLocation = $this->getLocationFromIp($clockInIp)
    ?? $this->getLocationFromIp($clockOutIp);

            $attendance = Attendance::updateOrCreate(
                [
                    'employee_id' => $request->employee_id,
                    'attendancedate_no' => $request->attendancedate_no
                ],
                [
                    'attendance_type' => $request->attendance_type,
                    'clock_in' => $clockIn,
                    'clock_out' => $clockOut,
                    'early_clock_in_time' => $earlyClockIn,
                    'early_clock_out_time' => $earlyClockOut,
                    'clock_in_ip' => $request->clock_in_ip,
                    'clock_out_ip' => $request->clock_out_ip,
                     'attendance_loc' => $attendanceLocation ?? null,
                    'attendance_workfrom' => $request->attendance_workfrom,
                    'half_day_type' => $request->attendance_type == 3 ? $request->half_day_type : null,
                    'attendance_empname' => $employee->emp_id,
                    'attendance_depname' => $employee->Departmentid->dep_id ?? null,
                    'attendance_depadmin' => $this->getDepartmentAdminId($employee->Departmentid->dep_id ?? null),
                ]
            );

            // After creating/updating the attendance
            if ($request->attendance_type == 0) {
                $this->createLeaveForAbsentAttendance($attendance);
            }
            // If this was a holiday for this employee and status is being changed to non-holiday
            $holidayRemoved = false;
            if ($wasHoliday && $request->attendance_type != 4) {
                $holidayRemoved = true;
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Attendance saved successfully',
                'attendance' => $attendance,
                'holiday_removed' => $holidayRemoved
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error processing attendance: ' . $e->getMessage()
            ], 500);
        }
    }
    public function attend_update(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required|exists:attendances,attendance_id',
            'employee_id' => 'required|exists:employees,emp_id',
            'attendancedate_no' => 'required|date',
            'attendance_type' => 'required|in:0,1,2,3',
            'clock_in' => 'required_if:attendance_type,1,2,3',
            'clock_out' => 'required_if:attendance_type,1,2,3',
            'attendance_workfrom' => 'nullable|in:0,1',
            'half_day_type' => 'nullable|required_if:attendance_type,3|in:0,1'
        ]);


        try {
            DB::beginTransaction();

            $employee = Employee::with(['Departmentid', 'Branchid'])->findOrFail($request->employee_id);
            $attendance = Attendance::findOrFail($request->attendance_id);

            $shift = Shift::where('employee_id', $request->employee_id)
    ->where('date_no', $request->attendancedate_no)
    ->first();

$wasHoliday = $shift && $shift->shift_type == 4;

            // Convert times from 12-hour to 24-hour format
            $clockIn = $request->clock_in ? Carbon::createFromFormat('h:i A', $request->clock_in)->format('H:i:s') : null;
            $clockOut = $request->clock_out ? Carbon::createFromFormat('h:i A', $request->clock_out)->format('H:i:s') : null;

            // Get shift times for this employee and date
            $shift = Shift::where('employee_id', $request->employee_id)
                ->where('date_no', $request->attendancedate_no)
                ->first();

            // Calculate early clock in/out if shift exists
            $earlyClockIn = null;
            $earlyClockOut = null;

            if ($shift && $clockIn && $clockOut) {
                $shiftStart = Carbon::parse($shift->shift_from_time);
                $shiftEnd = Carbon::parse($shift->shift_to_time);
                $clockInTime = Carbon::parse($clockIn);
                $clockOutTime = Carbon::parse($clockOut);

                if ($clockInTime->lt($shiftStart)) {
                    $earlyClockIn = $clockInTime->diff($shiftStart)->format('%H:%I:%S');
                }

                if ($clockOutTime->lt($shiftEnd)) {
                    $earlyClockOut = $clockOutTime->diff($shiftEnd)->format('%H:%I:%S');
                }
            }
        $clockInIp  = $request->clock_in_ip;
$clockOutIp = $request->clock_out_ip;

// Prefer clock-in IP
$attendanceLocation = $this->getLocationFromIp($clockInIp)
    ?? $this->getLocationFromIp($clockOutIp);


            $attendance->update([
                'attendance_type' => $request->attendance_type,
                'clock_in' => $clockIn,
                'clock_out' => $clockOut,
                'early_clock_in_time' => $earlyClockIn,
                'early_clock_out_time' => $earlyClockOut,
                'clock_in_ip' => $request->clock_in_ip,
                'clock_out_ip' => $request->clock_out_ip,
                'attendance_loc' => $attendanceLocation ?? null,
                'attendance_workfrom' => $request->attendance_workfrom,
                'half_day_type' => $request->attendance_type == 3 ? $request->half_day_type : null,
                'attendance_empname' => $employee->emp_id,
                'attendance_depname' => $employee->Departmentid->dep_id ?? null,
                'attendance_depadmin' => $this->getDepartmentAdminId($employee->Departmentid->dep_id ?? null),
            ]);
            // After updating the attendance
            if ($request->attendance_type == 0) {
                $this->createLeaveForAbsentAttendance($attendance);
            }

            $holidayRemoved = false;
            if ($wasHoliday && $request->attendance_type != 4) {
                $holidayRemoved = true;
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Attendance updated successfully',
                'attendance' => $attendance,
                'holiday_removed' => $holidayRemoved
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating attendance: ' . $e->getMessage()
            ], 500);
        }
    }
    public function attend_bulkCreate(Request $request)
    {
        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,emp_id',
            'attendance_type' => 'required|in:0,1,2,3',
            'mark_attendance' => 'required|in:1,2',
            'attendance_workfrom' => 'nullable|in:0,1',
            'attenddaterange_from' => 'required_if:mark_attendance,1|date|nullable',
            'attenddaterange_to' => 'required_if:mark_attendance,1|date|after_or_equal:attenddaterange_from|nullable',
            'month_year' => 'required_if:mark_attendance,2|date|nullable',
            'clock_in' => 'required_if:attendance_type,1,2,3',
            'clock_out' => 'required_if:attendance_type,1,2,3',
            'half_day_type' => 'nullable|required_if:attendance_type,3|in:0,1'
        ]);

        try {
            DB::beginTransaction();

            // Convert times from 12-hour to 24-hour format
            $clockIn = $request->clock_in ? Carbon::createFromFormat('h:i A', $request->clock_in)->format('H:i:s') : null;
            $clockOut = $request->clock_out ? Carbon::createFromFormat('h:i A', $request->clock_out)->format('H:i:s') : null;

            // Get date range based on selection
            $dates = [];
            if ($request->mark_attendance == 1) {
                $startDate = Carbon::parse($request->attenddaterange_from);
                $endDate = Carbon::parse($request->attenddaterange_to);

                while ($startDate <= $endDate) {
                    $dates[] = $startDate->format('Y-m-d');
                    $startDate->addDay();
                }
            } else {
                $monthYear = Carbon::parse($request->month_year);
                $daysInMonth = $monthYear->daysInMonth;

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $dates[] = $monthYear->copy()->day($day)->format('Y-m-d');
                }
            }

            $createdCount = 0;
            $holidaysRemoved = 0;

            foreach ($request->employee_ids as $employeeId) {
                $employee = Employee::with(['Departmentid', 'Branchid'])->find($employeeId);

                foreach ($dates as $date) {
                    // Only mark attendance if shift exists for this date
                    $shiftExists = Shift::where('employee_id', $employeeId)
                        ->where('date_no', $date)
                        ->exists();

                    if (!$shiftExists) {
                        continue;
                    }

                    // Check if this date was a holiday for this specific employee
                 $shift = Shift::where('employee_id', $employeeId)
    ->where('date_no', $date)
    ->first();

$wasHoliday = $shift && $shift->shift_type == 4;
                    // Get shift details
                    $shift = Shift::where('employee_id', $employeeId)
                        ->where('date_no', $date)
                        ->first();

                    // Calculate early clock in/out if shift exists
                    $earlyClockIn = null;
                    $earlyClockOut = null;

                    if ($shift && $clockIn && $clockOut) {
                        $shiftStart = Carbon::parse($shift->shift_from_time);
                        $shiftEnd = Carbon::parse($shift->shift_to_time);
                        $clockInTime = Carbon::parse($clockIn);
                        $clockOutTime = Carbon::parse($clockOut);

                        if ($clockInTime->lt($shiftStart)) {
                            $earlyClockIn = $clockInTime->diff($shiftStart)->format('%H:%I:%S');
                        }

                        if ($clockOutTime->lt($shiftEnd)) {
                            $earlyClockOut = $clockOutTime->diff($shiftEnd)->format('%H:%I:%S');
                        }
                    }
        $clockInIp  = $request->clock_in_ip;
$clockOutIp = $request->clock_out_ip;

// Prefer clock-in IP
$attendanceLocation = $this->getLocationFromIp($clockInIp)
    ?? $this->getLocationFromIp($clockOutIp);
                    $attendance = Attendance::updateOrCreate(
                        [
                            'employee_id' => $employeeId,
                            'attendancedate_no' => $date
                        ],
                        [
                            'attendance_type' => $request->attendance_type,
                            'clock_in' => $clockIn,
                            'clock_out' => $clockOut,
                            'early_clock_in_time' => $earlyClockIn,
                            'early_clock_out_time' => $earlyClockOut,
                            'attendance_workfrom' => $request->attendance_workfrom,
                            'half_day_type' => $request->attendance_type == 3 ? $request->half_day_type : null,
                            'attendance_empname' => $employee->emp_id,
                            'attendance_depname' => $employee->Departmentid->dep_id ?? null,
                            'attendance_depadmin' => $this->getDepartmentAdminId($employee->Departmentid->dep_id ?? null),
                            'mark_attendance' => $request->mark_attendance,
                            'attenddaterange_from' => $request->attenddaterange_from,
                            'attenddaterange_to' => $request->attenddaterange_to,
                            'attendance_loc' => $attendanceLocation ?? null,
                            'month_year' => $request->month_year,
                        ]
                    );

                    // If this was a holiday for this employee and status is being changed to non-holiday
                    if ($wasHoliday && $request->attendance_type != 4) {
                        $holidaysRemoved++;
                    }

                    $createdCount++;
                }
            }

            DB::commit();

            $message = "Successfully created/updated $createdCount attendance records";
            if ($holidaysRemoved > 0) {
                $message .= " and marked $holidaysRemoved holidays as not observed for these employees";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'holidays_removed' => $holidaysRemoved
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating bulk attendances: ' . $e->getMessage()
            ], 500);
        }
    }
    private function getDepartmentAdminId($departmentId)
    {
        $admin = User::role('Admin')
            ->whereHas('employee', function ($q) use ($departmentId) {
                $q->where('cur_department', $departmentId);
            })->first();

        return $admin?->id ?? User::role('Super admin')->pluck('id')->first();
    }
    public function attend_delete($id)
    {
        try {
            $attendance = Attendance::findOrFail($id);
            $attendance->delete();

            return response()->json([
                'success' => true,
                'message' => 'Attendance deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting attendance: ' . $e->getMessage()
            ], 500);
        }
    }



    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new AttendanceImport, $request->file('file'));
            return response()->json(['success' => true, 'message' => 'Attendance imported successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }






private function getLocationFromIp($ip)
{
    // Handle localhost & private IPs
    if (
        !$ip ||
        $ip === '127.0.0.1' ||
        $ip === '::1' ||
        str_starts_with($ip, '192.168.') ||
        str_starts_with($ip, '10.') ||
        str_starts_with($ip, '172.')
    ) {
        return 'Local Network';
    }

    try {
        $response = file_get_contents("http://ip-api.com/json/{$ip}");
        $data = json_decode($response, true);

        if ($data && $data['status'] === 'success') {
            return $data['city'] ?? null;
        }
    } catch (\Exception $e) {
        return null;
    }

    return null;
}





public function exportPdf(Request $request)
{
    // Fix: Properly handle employee_id array from query string
    $employeeIds = $request->input('employee_id');

    // If employee_id is a string (single value), convert to array
    if (is_string($employeeIds)) {
        $employeeIds = [$employeeIds];
    }

    // Filter employees - FIXED: Use correct relationship name 'Designationid'
    $employees = Employee::where('delete_status', 1)
        ->with(['Departmentid', 'Designationid'])  // Changed from 'designation' to 'Designationid'
        ->when($request->department && $request->department != 'all', function ($q) use ($request) {
            $q->whereHas('Departmentid', function ($qq) use ($request) {
                $qq->where('dep_name', $request->department);
            });
        })
        ->when($employeeIds && !in_array('all', $employeeIds), function ($q) use ($employeeIds) {
            $q->whereIn('emp_id', $employeeIds);
        })
        ->get();

    // Date range logic
    $dates = [];
    $reportTitle = '';
    $reportPeriod = '';
    $totalDays = 0;

    if ($request->start_date && $request->end_date) {
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);

        while ($start->lte($end)) {
            $dates[] = $start->format('Y-m-d');
            $start->addDay();
        }

        $reportTitle = "Attendance Report";
        $reportPeriod = Carbon::parse($request->start_date)->format('d-m-Y') . ' To ' .
                       Carbon::parse($request->end_date)->format('d-m-Y');
        $totalDays = count($dates);
    } elseif ($request->month) {
        $month = Carbon::parse($request->month);
        $daysInMonth = $month->daysInMonth;

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dates[] = $month->copy()->day($i)->format('Y-m-d');
        }

        $reportTitle = "Attendance Report - " . $month->format('F Y');
        $reportPeriod = $month->format('F Y');
        $totalDays = $daysInMonth;
    } else {
        $month = Carbon::now();
        $daysInMonth = $month->daysInMonth;

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dates[] = $month->copy()->day($i)->format('Y-m-d');
        }

        $reportTitle = "Attendance Report - " . $month->format('F Y');
        $reportPeriod = $month->format('F Y');
        $totalDays = $daysInMonth;
    }

    if (empty($dates)) {
        $dates[] = now()->format('Y-m-d');
    }

    $startDate = min($dates);
    $endDate = max($dates);

    // Fetch attendances
    $attendances = Attendance::where('delete_status', 1)
        ->whereDate('attendancedate_no', '>=', $startDate)
        ->whereDate('attendancedate_no', '<=', $endDate)
        ->get()
        ->keyBy(fn($item) => $item->employee_id . '_' . Carbon::parse($item->attendancedate_no)->format('Y-m-d'));

    // Fetch shifts
    $shifts = Shift::where('delete_status', 1)
        ->whereDate('date_no', '>=', $startDate)
        ->whereDate('date_no', '<=', $endDate)
        ->get()
        ->keyBy(fn($item) => $item->employee_id . '_' . Carbon::parse($item->date_no)->format('Y-m-d'));

    // Fetch approved leaves
    $leaves = Leave::where('delete_status', 1)
        ->where('leave_status', 1)
        ->whereDate('leavedate_no', '>=', $startDate)
        ->whereDate('leavedate_no', '<=', $endDate)
        ->with('leavetype')
        ->get();

    // Create leave lookup array
    $leaveLookup = [];
    foreach ($leaves as $leave) {
        $leaveDate = Carbon::parse($leave->leavedate_no)->format('Y-m-d');
        $key = $leave->employee_id . '_' . $leaveDate;
        $leaveLookup[$key] = $leave;
    }

    // Prepare monthly data
    $monthlyData = [];
    $reportGeneratedDate = Carbon::now()->format('d-m-Y H:i:s');
    $departmentName = $request->department && $request->department != 'all' ? $request->department : 'All Departments';

    foreach ($employees as $employee) {
        // Group dates by month
        $datesByMonth = [];
        foreach ($dates as $date) {
            $monthKey = Carbon::parse($date)->format('F Y');
            $monthNumber = Carbon::parse($date)->format('m');
            $year = Carbon::parse($date)->format('Y');

            if (!isset($datesByMonth[$monthKey])) {
                $datesByMonth[$monthKey] = [
                    'month_name' => $monthKey,
                    'month_number' => $monthNumber,
                    'year' => $year,
                    'days_in_month' => Carbon::parse($date)->daysInMonth,
                    'total_days_in_range' => 0,
                    'dates' => []
                ];
            }
            $datesByMonth[$monthKey]['dates'][] = $date;
            $datesByMonth[$monthKey]['total_days_in_range']++;
        }

        $employeeMonthlyData = [];

        foreach ($datesByMonth as $monthKey => $monthInfo) {
            $monthlySummary = [
                'present' => 0,
                'late' => 0,
                'absent' => 0,
                'holiday' => 0,
                'dayoff' => 0,
                'privilege_leave' => 0,
                'casual_leave' => 0,
                'sick_leave' => 0,
                'total_working_days' => 0
            ];

            foreach ($monthInfo['dates'] as $date) {
                $key = $employee->emp_id . '_' . $date;

                $attendance = $attendances[$key] ?? null;
                $shift = $shifts[$key] ?? null;
                $leaveRecord = $leaveLookup[$key] ?? null;

                $isHoliday = $shift && $shift->shift_type == 4;
                $isDayOff = $shift && $shift->shift_type == 3;

                if ($isHoliday) {
                    $monthlySummary['holiday']++;
                } elseif ($isDayOff) {
                    $monthlySummary['dayoff']++;
                } elseif ($leaveRecord) {
                    $isHalfDayLeave = in_array($leaveRecord->select_duration, [3, 4]);

                    if (!$isHalfDayLeave) {
                        $leaveTypeId = (int)$leaveRecord->leave_type_id;

                        if ($leaveTypeId == 1) {
                            $monthlySummary['privilege_leave']++;
                        } elseif ($leaveTypeId == 2) {
                            $monthlySummary['casual_leave']++;
                        } elseif ($leaveTypeId == 3) {
                            $monthlySummary['sick_leave']++;
                        }
                    }
                } elseif ($attendance) {
                    switch ($attendance->attendance_type) {
                        case 1:
                            $monthlySummary['present']++;
                            break;
                        case 2:
                            $monthlySummary['late']++;
                            break;
                        case 0:
                            $monthlySummary['absent']++;
                            break;
                    }
                } else {
                    if ($shift && !$isHoliday && !$isDayOff && !$leaveRecord) {
                        $monthlySummary['absent']++;
                    }
                }
            }

            $employeeMonthlyData[] = [
                'month' => $monthKey,
                'month_year' => Carbon::parse($monthInfo['dates'][0])->format('M - Y'),
                'days_in_month' => $monthInfo['days_in_month'],
                'total_days_in_range' => $monthInfo['total_days_in_range'],
                'summary' => $monthlySummary
            ];
        }

        $monthlyData[] = [
            'employee' => $employee,
            'months' => $employeeMonthlyData
        ];
    }

    $totalEmployees = count($employees);

    // Get company logo from settings
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

    $data = [
        'title' => $reportTitle,
        'report_period' => $reportPeriod,
        'report_generated_date' => $reportGeneratedDate,
        'department_name' => $departmentName,
        'total_days' => $totalDays,
        'monthly_data' => $monthlyData,
        'total_employees' => $totalEmployees,
        'start_date' => Carbon::parse($startDate)->format('d-m-Y'),
        'end_date' => Carbon::parse($endDate)->format('d-m-Y'),
        'date_range_days' => Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1,
        'company_logo' => $companyLogo,
        'company_name' => $companyName,
    ];

    $pdf = Pdf::loadView('dashboard.hr.attendance.export-pdf', $data);
    $pdf->setPaper('A4', 'portrait');

    $pdf->setOptions([
        'defaultFont' => 'DejaVu Sans',
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true,
    ]);

    return $pdf->download('attendance_report_' . Carbon::now()->format('Ymd_His') . '.pdf');
}
}
