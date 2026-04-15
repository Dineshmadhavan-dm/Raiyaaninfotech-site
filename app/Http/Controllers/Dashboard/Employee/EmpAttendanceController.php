<?php

namespace App\Http\Controllers\Dashboard\Employee;

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
use App\Models\Leave;

class EmpAttendanceController extends Controller
{
    public  function __construct()
    {
        $this->middleware('permission:attendance->attendance view')->only(['empattendlist']);
    }

    public function empattendlist()
    {
        $authUser = auth()->user();

        if ($authUser->categorie == 2) {
            $employees = Employee::where('delete_status', 1)
                ->where('emp_id', $authUser->employeerole_id)
                ->with('Departmentid')
                ->get();
        } else {
            $employees = Employee::where('delete_status', 1)
                ->with('Departmentid')
                ->get();
        }

        $locations = Branch::where('delete_status', 1)->get();

        // Get shifts based on employee filter
        if ($authUser->categorie == 2) {
            $shifts = Shift::where('delete_status', 1)
                ->where('employee_id', $authUser->employeerole_id)
                ->get()
                ->groupBy(['employee_id', function ($shift) {
                    return Carbon::parse($shift->date_no)->format('Y-m-d');
                }])
                ->map(function ($employeeShifts) {
                    return $employeeShifts->map(function ($shift) {
                        return $shift->first();
                    });
                });
        } else {
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
        }

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


        // Get approved leaves based on employee filter
        if ($authUser->categorie == 2) {
            $approvedLeaves = Leave::where('leave_status', 1)
                ->where('delete_status', 1)
                ->where('employee_id', $authUser->employeerole_id)
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
        } else {
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
        }

        return view('dashboard.employee.attendance.index', compact(
            'employees',
            'departments',
            'superAdminName',
            'locations',
            'shifts',

            'approvedLeaves',
            'authUser'
        ));
    }

    public function getAttendances()
    {
        $authUser = auth()->user();

        if ($authUser->categorie == 2) {
            $attendances = Attendance::with('employees', 'branchid')
                ->where('employee_id', $authUser->employeerole_id)
                ->get();
        } else {
            $attendances = Attendance::with('employees', 'branchid')->get();
        }

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
            'leave_type_id' => 0,
            'select_duration' => 1,
            'leave_status' => 2,
            'reason_forleave' => 'Absent marked in attendance',
            'leavedate_no' => $attendance->attendancedate_no,
            'attend_id' => $attendance->attendance_id
        ]);
    }

    public function attend_create(Request $request)
    {
        $authUser = auth()->user();

        // If employee, ensure they can only create attendance for themselves
        if ($authUser->categorie == 2) {
            $request->merge(['employee_id' => $authUser->employeerole_id]);
        }

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


            // Convert times from 12-hour to 24-hour format
            $clockIn = $request->clock_in ? Carbon::createFromFormat('h:i A', $request->clock_in)->format('H:i:s') : null;
            $clockOut = $request->clock_out ? Carbon::createFromFormat('h:i A', $request->clock_out)->format('H:i:s') : null;

            // Get shift times for this employee and date
            $shift = Shift::where('employee_id', $request->employee_id)
                ->where('date_no', $request->attendancedate_no)
                ->first();

            // Determine if it's late based on shift time
            $isLate = false;
            $attendanceType = $request->attendance_type;

            if ($shift && $clockIn) {
                $shiftStart = Carbon::parse($shift->shift_from_time);
                $clockInTime = Carbon::parse($clockIn);

                // If clock in is after shift start by more than 5 minutes, it's late
                if ($clockInTime->gt($shiftStart->copy()->addMinutes(5))) {
                    $isLate = true;
                    // If attendance type was set to present, change it to late
                    if ($attendanceType == '1') {
                        $attendanceType = '2';
                    }
                }
            }

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
                    'attendance_type' => $attendanceType,
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
            if ($attendanceType == 0) {
                $this->createLeaveForAbsentAttendance($attendance);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Attendance saved successfully',
                'attendance' => $attendance,

                'is_late' => $isLate
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error processing attendance: ' . $e->getMessage()
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


}
