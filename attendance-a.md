```
└── 📁app
    └── 📁Console
        └── 📁Commands
            ├── MarkAbsentAttendance.php
        ├── kernal.php
    └── 📁Exports
        ├── AttendanceExport.php
    └── 📁Http
        └── 📁Controllers
            └── 📁Dashboard
                └── 📁Employee
                    ├── ApplyleaveController.php
                    ├── EmpAttendanceController.php
                    ├── EmpdashboardController.php
                    ├── EmpholidayController.php
                    ├── EmpKanbanboardController.php
                    ├── EmpTaskchatController.php
                └── 📁HR
                    └── 📁Kanbanboard
                        ├── ChattaskController.php
                        ├── ModuloController.php
                        ├── ProjectController.php
                        ├── SubtaskController.php
                        ├── TaskController.php
                    └── 📁Other
                        ├── BloodgroupController.php
                        ├── BranchController.php
                        ├── DepartmentController.php
                        ├── DesignationController.php
                        ├── HolidaytypeController.php
                        ├── JobtypeController.php
                        ├── QualificationController.php
                        ├── RelationshipController.php
                        ├── SearchController.php
                    ├── AttendanceController.php
                    ├── ClientController.php
                    ├── CompanyEmailController.php
                    ├── EmploymentController.php
                    ├── HandoverController.php
                    ├── HolidayController.php
                    ├── HrDashboardController.php
                    ├── LeaveController.php
                    ├── LeaveTypeController.php
                    ├── PermissionController.php
                    ├── ProbationController.php
                    ├── PromotionController.php
                    ├── ResignationController.php
                    ├── RoleController.php
                    ├── ShiftController.php
                    ├── TerminationController.php
                └── 📁Netural
                    ├── SettingController.php
            ├── Controller.php
            ├── IndexController.php
            ├── LoginAccessController.php
            ├── LoginController.php
        └── 📁Middleware
            ├── CheckLoginAccess.php
    └── 📁Imports
        ├── AttendanceImport.php
        ├── EmployeesImport.php
    └── 📁Mail
        ├── ModuleCreatedEmail.php
        ├── ProjectCreatedEmail.php
        ├── SubtaskCreatedEmail.php
        ├── TaskCreatedEmail.php
    └── 📁Models
        ├── Attendance.php
        ├── BloodGroup.php
        ├── Branch.php
        ├── Chatimage.php
        ├── Chattask.php
        ├── Client.php
        ├── Department.php
        ├── Designation.php
        ├── Education.php
        ├── Employee.php
        ├── Emprole.php
        ├── Family.php
        ├── Handover.php
        ├── Holiday.php
        ├── Holidaytype.php
        ├── Jobtype.php
        ├── Leave.php
        ├── Leavetype.php
        ├── LoginActivity.php
        ├── Modulo.php
        ├── Pastemployee.php
        ├── PmtsImage.php
        ├── Probation.php
        ├── Professionalreference.php
        ├── Project.php
        ├── Promotion.php
        ├── Qualification.php
        ├── Relationship.php
        ├── Resignation.php
        ├── Setting.php
        ├── Shift.php
        ├── Site.php
        ├── Subtask.php
        ├── Task.php
        ├── Termination.php
        ├── User.php
    └── 📁Providers
        ├── AppServiceProvider.php
    └── 📁Rules
        ├── MatchOldPassword.php
    └── 📁Traits
        └── LogActivity.php
```

```
└── 📁resources
    └── 📁css
        ├── app.css
    └── 📁js
        ├── app.js
        ├── bootstrap.js
    └── 📁views
        └── 📁auth
            ├── forgetpwd.blade.php
            ├── login.blade.php
        └── 📁components
            ├── actionbtn.blade.php
            ├── chattasklayout.blade.php
            ├── hor-tabnav.blade.php
            ├── kanbandashboardlayout.blade.php
            ├── layout.blade.php
            ├── message.blade.php
            ├── site.blade.php
            ├── tabnav.blade.php
        └── 📁dashboard
            └── 📁employee
                └── 📁applyleave
                    ├── index.blade.php
                └── 📁attendance
                    ├── index.blade.php
                └── 📁empdashboard
                    ├── index.blade.php
                └── 📁empkanbanboard
                    ├── etaskchat.blade.php
                    ├── project.blade.php
                └── 📁holiday
                    ├── index.blade.php
            └── 📁hr
                └── 📁admin
                    ├── cpwd.blade.php
                    ├── list.blade.php
                    ├── managecolumn.blade.php
                    ├── profile.blade.php
                    ├── profileshow2.blade.php
                    ├── show.blade.php
                └── 📁attendance
                    ├── index.blade.php
                └── 📁client
                    ├── create.blade.php
                    ├── edit.blade.php
                    ├── index.blade.php
                └── 📁confirmedemp
                    ├── list.blade.php
                    ├── show.blade.php
                └── 📁email
                    ├── moduletemp.blade.php
                    ├── projecttemp.blade.php
                    ├── subtasktemp.blade.php
                    ├── tasktemp.blade.php
                └── 📁employee
                    ├── adminshow.blade.php
                    ├── columnmanage.blade.php
                    ├── create.blade.php
                    ├── edit.blade.php
                    ├── list.blade.php
                    ├── show.blade.php
                └── 📁handover
                    ├── create.blade.php
                    ├── edit.blade.php
                    ├── list.blade.php
                    ├── show.blade.php
                └── 📁holiday
                    ├── index.blade.php
                └── 📁hrdashboard
                    ├── index.blade.php
                └── 📁kanbanboard
                    └── 📁module
                        ├── create.blade.php
                        ├── edit.blade.php
                        ├── list.blade.php
                    └── 📁partials
                        ├── chat-message.blade.php
                    └── 📁project
                        ├── create.blade.php
                        ├── edit.blade.php
                        ├── list.blade.php
                    └── 📁subtask
                        ├── create.blade.php
                        ├── edit.blade.php
                        ├── list.blade.php
                    └── 📁task
                        ├── create.blade.php
                        ├── edit.blade.php
                        ├── list.blade.php
                    ├── kanbandashboard.blade.php
                    ├── task-chat.blade.php
                └── 📁leave
                    └── 📁leavetype
                        ├── columnmanageleavetype.blade.php
                        ├── leavetypeindex.blade.php
                    ├── index.blade.php
                └── 📁other
                    └── 📁bloodgroup
                        ├── list.blade.php
                    └── 📁branch
                        ├── list.blade.php
                    └── 📁companyemail
                        ├── create.blade.php
                        ├── edit.blade.php
                        ├── index.blade.php
                    └── 📁department
                        ├── list.blade.php
                    └── 📁designation
                        ├── list.blade.php
                    └── 📁holidaytype
                        ├── list.blade.php
                    └── 📁jobtype
                        ├── list.blade.php
                    └── 📁qualification
                        ├── list.blade.php
                    └── 📁relationship
                        ├── list.blade.php
                └── 📁probation
                    ├── create.blade.php
                    ├── edit.blade.php
                    ├── list.blade.php
                    ├── show.blade.php
                └── 📁promotion
                    ├── create.blade.php
                    ├── edit.blade.php
                    ├── list.blade.php
                    ├── show.blade.php
                └── 📁resignation
                    ├── create.blade.php
                    ├── edit.blade.php
                    ├── list.blade.php
                    ├── show.blade.php
                └── 📁role-and-permission
                    └── 📁permission
                        ├── create.blade.php
                        ├── edit.blade.php
                        ├── index.blade.php
                        ├── show.blade.php
                    └── 📁role
                        ├── addpermission.blade.php
                        ├── create.blade.php
                        ├── edit.blade.php
                        ├── index.blade.php
                        ├── show.blade.php
                └── 📁shiftplanner
                    ├── index.blade.php
                └── 📁termination
                    ├── create.blade.php
                    ├── edit.blade.php
                    ├── list.blade.php
                    ├── show.blade.php
            └── 📁netural
                └── 📁setting
                    ├── activitylog.blade.php
                    ├── applogo.blade.php
                    ├── cookies.blade.php
                    ├── favicon.blade.php
                    ├── sitecontrol.blade.php
                    ├── theme.blade.php
        └── 📁errors
            ├── 401.blade.php
            ├── 403.blade.php
            ├── 404.blade.php
            ├── 419.blade.php
            ├── 500.blade.php
        ├── index.blade.php
        └── site.blade.php
```
------------------------------------------------------------------------
Hr/attendance
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
use App\Models\Holiday;
use App\Models\Leave;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
    $employees = Employee::where('delete_status', 1)
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
    $holidayTypes = [
        1 => 'Local',
        2 => 'National',
        3 => 'Religious'
    ];

    // FIX 1: Fetch holidays for the entire current year
    $currentYear = now()->year;
    $holidays = Holiday::where('delete_status', 1)
        ->whereYear('holiday_date', $currentYear) // Get holidays for current year
        ->get()
        ->map(function ($holiday) use ($holidayTypes) {
            return [
                'date' => date('Y-m-d', strtotime($holiday->holiday_date)),
                'occasion' => $holiday->occasion,
                'type' => $holidayTypes[$holiday->holidaytype] ?? 'Other Holiday',
                'departments' => $holiday->holiday_department ?
                    explode(',', $holiday->holiday_department) : [],
                'designations' => $holiday->holiday_designation ?
                    explode(',', $holiday->holiday_designation) : [],
                'job_types' => $holiday->holiday_emptype ?
                    explode(',', $holiday->holiday_emptype) : [],
            ];
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

            // Check if this date was a holiday for this specific employee
            $wasHoliday = Holiday::where('holiday_date', $request->attendancedate_no)
                ->where('delete_status', 1)
                ->where(function ($query) use ($employee) {
                    // Check if holiday applies to this employee's department, designation, or job type
                    $query->where(function ($q) use ($employee) {
                        $q->where('holiday_department', 'LIKE', '%' . $employee->Departmentid->dep_id . '%')
                            ->orWhereNull('holiday_department')
                            ->orWhere('holiday_department', '');
                    });
                })
                ->exists();

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

            // Check if this date was a holiday for this specific employee
            $wasHoliday = Holiday::where('holiday_date', $request->original_date)
                ->where('delete_status', 1)
                ->where(function ($query) use ($employee) {
                    // Check if holiday applies to this employee's department, designation, or job type
                    $query->where(function ($q) use ($employee) {
                        $q->where('holiday_department', 'LIKE', '%' . $employee->Departmentid->dep_id . '%')
                            ->orWhereNull('holiday_department')
                            ->orWhere('holiday_department', '');
                    });
                })
                ->exists();

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
                    $wasHoliday = Holiday::where('holiday_date', $date)
                        ->where('delete_status', 1)
                        ->where(function ($query) use ($employee) {
                            // Check if holiday applies to this employee's department, designation, or job type
                            $query->where(function ($q) use ($employee) {
                                $q->where('holiday_department', 'LIKE', '%' . $employee->Departmentid->dep_id . '%')
                                    ->orWhereNull('holiday_department')
                                    ->orWhere('holiday_department', '');
                            });
                        })
                        ->exists();

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




   public function export(Request $request)
{
    $request->validate([
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'department' => 'nullable|string',
        'employee_id' => 'nullable|integer',
        'attendance_type' => 'nullable|string',
        'month' => 'nullable|string',
        'count' => 'nullable|integer'
    ]);

    $query = Attendance::with(['employees', 'branchid', 'employees.Departmentid']);

    /*
    |---------------------------------------
    | Employee Filter
    |---------------------------------------
    */
    if ($request->employee_id) {
        $query->where('employee_id', $request->employee_id);
    }

    /*
    |---------------------------------------
    | Department Filter
    |---------------------------------------
    */
    if ($request->department && $request->department !== 'all') {
        $query->where('attendance_depname', $request->department);
    }

    /*
    |---------------------------------------
    | Attendance Type Filter
    |---------------------------------------
    */
    if ($request->attendance_type && $request->attendance_type !== 'all') {
        $query->where('attendance_type', $request->attendance_type);
    }

    /*
    |---------------------------------------
    | Month Filter
    |---------------------------------------
    */
   // Month filter
if ($request->month) {

    if (str_contains($request->month, '-')) {

        $month = Carbon::parse($request->month);

        $query->whereMonth('attendancedate_no', $month->month)
              ->whereYear('attendancedate_no', $month->year);

    } else {

        $query->whereMonth('attendancedate_no', $request->month);

    }
}
    /*
    |---------------------------------------
    | Date Range Filter
    |---------------------------------------
    */
    if ($request->start_date && $request->end_date) {

        $query->whereBetween('attendancedate_no', [
            Carbon::parse($request->start_date)->startOfDay(),
            Carbon::parse($request->end_date)->endOfDay()
        ]);

    } elseif ($request->start_date) {

        $query->whereDate('attendancedate_no', '>=', $request->start_date);

    } elseif ($request->end_date) {

        $query->whereDate('attendancedate_no', '<=', $request->end_date);
    }

    /*
    |---------------------------------------
    | Order Data
    |---------------------------------------
    */
    $query->orderBy('attendance_id', 'asc');

    /*
    |---------------------------------------
    | Count Filter (3 / 5 / 10 etc)
    |---------------------------------------
    */
    if ($request->count) {
        $query->limit($request->count);
    }

    /*
    |---------------------------------------
    | Fetch Data
    |---------------------------------------
    */
    $data = $query->get()->map(function ($attendance) {

        return [
            'attendance_id' => $attendance->attendance_id,
            'employee_id' => $attendance->employee_id,
            'attendance_empname' => $attendance->employees->fullname ?? 'N/A',
            'attendance_depname' => $attendance->employees->Departmentid->dep_name ?? 'N/A',
            'attendancedate_no' => $attendance->attendancedate_no,
            'clock_in' => $attendance->clock_in,
            'clock_out' => $attendance->clock_out,
            'attendance_loc' => $attendance->branchid->branch_name ?? 'N/A',
            'attendance_workfrom' => $attendance->attendance_workfrom,
            'attendance_type' => $attendance->attendance_type,
            'status' => $this->getAttendanceStatusText($attendance->attendance_type),
            'clock_in_ip' => $attendance->clock_in_ip,
            'clock_out_ip' => $attendance->clock_out_ip,
            'early_clock_in_time' => $attendance->early_clock_in_time,
            'early_clock_out_time' => $attendance->early_clock_out_time,
            'half_day_type' => $attendance->half_day_type
        ];

    })->toArray();

    /*
    |---------------------------------------
    | Export File
    |---------------------------------------
    */
    $filename = 'employee_attendance_' . now()->format('Ymd_His') . '.xlsx';

    return Excel::download(
        new AttendanceExport($data, $request->start_date, $request->end_date),
        $filename
    );
}

    // Helper method to get attendance status text
    private function getAttendanceStatusText($status)
    {
        switch ($status) {
            case 0:
                return 'Absent';
            case 1:
                return 'Present';
            case 2:
                return 'Late';
            case 3:
                return 'Half Day';
            case 4:
                return 'Holiday';
            default:
                return 'Unknown';
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

}
---------------------------------------------------
hr/attendance.php

<?php

use App\Http\Controllers\Dashboard\HR\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('attendance', [AttendanceController::class, 'attendlist'])->name('attendancelist');
Route::get('attendances/get', [AttendanceController::class, 'getAttendances'])->name('attendance.get');
Route::post('attendances/create', [AttendanceController::class, 'attend_create'])->name('attendance.create');
Route::post('attendances/bulk-create', [AttendanceController::class, 'attend_bulkCreate'])->name('attendance.bulk-create');
Route::put('attendances/update', [AttendanceController::class, 'attend_update'])->name('attendance.update');
Route::delete('attendances/delete/{id}', [AttendanceController::class, 'attend_delete'])->name('attendance.delete');

Route::get('attendances/export-template', [AttendanceController::class, 'downloadTemplate'])->name('attendances.exportTemplate');
Route::get('attendances/export', [AttendanceController::class, 'export'])->name('attendances.export');
Route::post('attendances/import', [AttendanceController::class, 'import'])->name('attendances.import');

web.php
<?php

use App\Http\Controllers\Dashboard\Employee\EmpdashboardController;
use App\Http\Controllers\Dashboard\HR\HrDashboardController;
use App\Http\Controllers\LoginController;
use App\Models\Holiday;
use App\Models\Project;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;


//guest

Route::group(['middleware' => ['guest']], routes: function () {

    require __DIR__ . '/guest.php';
});


//auth

Route::group(['middleware' => ['auth']], routes: function () {


    Route::get('/emphome', [EmpdashboardController::class, 'emphome'])->name('emphome');
    Route::get('/dashboard', [HrDashboardController::class, 'index'])->name('dhome');

    Route::group(['prefix' => 'employees'], function () {

        require __DIR__ . '/Dashboard/Employee/Empattendance.php';
        require __DIR__ . '/Dashboard/Employee/Empkanbanboard.php';
        require __DIR__ . '/Dashboard/Employee/Applyleave.php';
        require __DIR__ . '/Dashboard/Employee/Holiday.php';
    });


    Route::group(['prefix' => 'dashboard'], function () {


        require __DIR__ . '/Dashboard/HR/RolePermission.php';
         require __DIR__ . '/Dashboard/HR/Kanbanboard/Project.php';
        require __DIR__ . '/Dashboard/HR/Kanbanboard/Task.php';
        require __DIR__ . '/Dashboard/HR/Kanbanboard/Chattask.php';
        require __DIR__ . '/Dashboard/HR/Kanbanboard/Subtask.php';
        require __DIR__ . '/Dashboard/HR/Kanbanboard/Modulo.php';

        Route::get('logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('/activity-logs', [LoginController::class, 'activitystatus'])->name('activity.logs');

        Route::group(['prefix' => 'setting'], function () {
            require __DIR__ . '/Dashboard/Netural/setting.php';
        });

        Route::group(['prefix' => 'admin'], function () {
            require __DIR__ . '/Dashboard/HR/HrDashboard.php';
        });


        Route::group(['prefix' => 'employees'], function () {

            require __DIR__ . '/Dashboard/Employee/Empattendance.php';
            require __DIR__ . '/Dashboard/Employee/Empkanbanboard.php';
            require __DIR__ . '/Dashboard/Employee/Applyleave.php';
            require __DIR__ . '/Dashboard/Employee/Holiday.php';
            require __DIR__ . '/Dashboard/HR/Client.php';
            require __DIR__ . '/Dashboard/HR/Attendance.php';
            require __DIR__ . '/Dashboard/HR/CompanyEmail.php';
            require __DIR__ . '/Dashboard/HR/Employment.php';
            require __DIR__ . '/Dashboard/HR/Termination.php';
            require __DIR__ . '/Dashboard/HR/Resignation.php';
            require __DIR__ . '/Dashboard/HR/Promotion.php';
            require __DIR__ . '/Dashboard/HR/Probation.php';
            require __DIR__ . '/Dashboard/HR/Handover.php';
            require __DIR__ . '/Dashboard/HR/Holiday.php';
            require __DIR__ . '/Dashboard/HR/Shift.php';
            require __DIR__ . '/Dashboard/HR/Leave.php';
            require __DIR__ . '/Dashboard/HR/Other/Branch.php';
            require __DIR__ . '/Dashboard/HR/Other/Department.php';
            require __DIR__ . '/Dashboard/HR/Other/Jobtype.php';
            require __DIR__ . '/Dashboard/HR/Other/Holidaytype.php';
            require __DIR__ . '/Dashboard/HR/Other/Bloodgroup.php';
            require __DIR__ . '/Dashboard/HR/Other/Relationship.php';
            require __DIR__ . '/Dashboard/HR/Other/Designation.php';
            require __DIR__ . '/Dashboard/HR/Other/Qualification.php';
            require __DIR__ . '/Dashboard/HR/Other/Search.php';

        });
    });
});




Route::get('/get-ip-address', function () {
    return response()->json([
        'ip' => request()->ip()
    ]);
});

Route::get('/preview-project-email/{id}', function ($id) {
    $project = Project::with(['projectHead', 'projectLead', 'client'])
        ->findOrFail($id);

    return view('dashboard.hr.email.projecttemp', compact('project'));
});




Route::get('/mail-test', function () {
    Mail::raw('test mail successfully done!', function ($message) {
        $message->to('d.dinesh@raiyaaninfotech.com')
                ->subject('Rwms');
    });

    return 'Mail sent!';
});
------------------------------------------------------------------------------------------
hr/attendance

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendances';
    protected $primaryKey = 'attendance_id';

    protected $fillable = [
        'employee_id',

        'clock_in',
        'clock_out',
        'clock_in_ip',
        'clock_out_ip',
        'attendance_loc',
        'attendance_workfrom',
        'attendance_type',

        'attendance_depname',
        'attendance_depadmin',
        'attendance_empname',
        'mark_attendance',
        'half_day_type',
        'early_clock_in_time',
        'early_clock_out_time',
        'attendancedate_no',
        'attenddaterange_from',
        'attenddaterange_to',

        'month_year',


        'delete_status'
    ];

    public function employees()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_id');
    }

    // In Attendance model

    public function branchid()
    {
        return $this->belongsTo(Branch::class, 'attendance_loc', 'branch_id');
    }
    // In Attendance.php model

    protected $casts = [
        'attendance_workfrom' => 'integer',
        'attendance_type' => 'integer',
        'half_day_type' => 'integer',
        'mark_attendance' => 'integer',
    ];
}
----------------------------------------------------------------
export/attendance 
<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, WithTitle
{
    protected $data;
    protected $startDate;
    protected $endDate;

    public function __construct($data, $startDate = null, $endDate = null)
    {
        $this->data = $data;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function title(): string
    {
        return 'Attendance Report';
    }

    public function headings(): array
    {
        $rangeText = '';
        if ($this->startDate && $this->endDate) {
            $start = Carbon::parse($this->startDate)->format('M d, Y');
            $end = Carbon::parse($this->endDate)->format('M d, Y');
            $rangeText = " ($start to $end)";
        }

        return [
            ['ATTENDANCE REPORT' . $rangeText],
            [], // Empty row for spacing
            [
                'Attendance ID',
                'Employee Name',
                'Department',
                'Date',
                'Clock In',
                'Clock Out',
                'Total Work Hrs',
                'Location',
                'Work From',
                'Status',
                'IP Address (In)',
                'IP Address (Out)',
                'Early Clock (In)',
                'Early Clock (Out)',
                'Half Day Type'
            ]
        ];
    }

    public function map($attendance): array
    {
        $attendance = (object) $attendance;

        switch ($attendance->attendance_type) {
            case 0:
                $statusText = 'Absent';
                break;
            case 1:
                $statusText = 'Present';
                break;
            case 2:
                $statusText = 'Late';
                break;
            case 3:
                $statusText = 'Half Day';
                break;
            case 4:
                $statusText = 'Holiday';
                break;
            case 5:
                $statusText = 'Leave';
                break;
            default:
                $statusText = 'Unknown';
        }
        $workFrom = ($attendance->attendance_workfrom == 1) ? 'Office' : 'Home';


        $halfDayType = '';
        if ($attendance->attendance_type == 3) {
            $halfDayType = ($attendance->half_day_type == 1) ? 'Second Half' : 'First Half';
        }


        $clockIn = !empty($attendance->clock_in) ? Carbon::parse($attendance->clock_in)->format('h:i A') : 'N/A';
        $clockOut = !empty($attendance->clock_out) ? Carbon::parse($attendance->clock_out)->format('h:i A') : 'N/A';
        $date = !empty($attendance->attendancedate_no) ? Carbon::parse($attendance->attendancedate_no)->format('d-M-Y') : 'N/A';


        $workHours = 'N/A';
        if (!empty($attendance->clock_in) && !empty($attendance->clock_out)) {
            $start = Carbon::parse($attendance->clock_in);
            $end = Carbon::parse($attendance->clock_out);
            if ($end->lessThan($start)) {
                $end->addDay();
            }
            $diff = $start->diff($end);
            $workHours = sprintf('%dh %02dm', $diff->h, $diff->i);
        }


        $earlyClockIn = $this->convertTimeToReadable($attendance->early_clock_in_time);
        $earlyClockOut = $this->convertTimeToReadable($attendance->early_clock_out_time);

        return [
            $attendance->attendance_id ?? 'N/A',
            $attendance->attendance_empname ?? 'N/A',
            $attendance->attendance_depname ?? 'N/A',
            $date,
            $clockIn,
            $clockOut,
            $workHours,
            $attendance->attendance_loc ?? 'N/A',
            $workFrom,
            $statusText,
            $attendance->clock_in_ip ?? 'N/A',
            $attendance->clock_out_ip ?? 'N/A',
            $earlyClockIn,
            $earlyClockOut,
            $halfDayType
        ];
    }

    private function convertTimeToReadable($timeString)
    {
        try {
            $timeParts = explode(':', $timeString);
            $hours = isset($timeParts[0]) ? (int)$timeParts[0] : 0;
            $minutes = isset($timeParts[1]) ? (int)$timeParts[1] : 0;

            if ($hours === 0 && $minutes === 0) {
                return '0m';
            }

            $formatted = '';
            if ($hours > 0) {
                $formatted .= $hours . " hrs ";
            }
            if ($minutes > 0) {
                $formatted .= $minutes . " min";
            }

            return trim($formatted);
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    public function styles(Worksheet $sheet)
    {

        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(10);
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getColumnDimension('H')->setWidth(25);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(20);
        $sheet->getColumnDimension('L')->setWidth(20);
        $sheet->getColumnDimension('M')->setWidth(15);
        $sheet->getColumnDimension('N')->setWidth(15);
        $sheet->getColumnDimension('O')->setWidth(12);


        $sheet->mergeCells('A1:O1');


        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2c3e50']]
        ]);


        $sheet->getStyle('A3:O3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3498db']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFFFFF']]]
        ]);

        $sheet->setAutoFilter('A3:O3');
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(3)->setRowHeight(20);
        $sheet->freezePane('A4');
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $lastRow = count($this->data) + 3;


                for ($row = 4; $row <= $lastRow; $row++) {
                    $fillColor = $row % 2 == 0 ? 'f8f9fa' : 'ffffff';
                    $event->sheet->getStyle("A{$row}:O{$row}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $fillColor]]
                    ]);


                    $statusCell = "J{$row}";
                    $statusValue = $event->sheet->getCell($statusCell)->getValue();

                    $statusColors = [
                        'Present'  => '27ae60',
                        'Absent'   => 'e74c3c',
                        'Late'     => 'f1c40f',
                        'Half Day' => '2980b9',
                        'Holiday'  => '8e44ad',
                        'Leave'    => 'e67e22',
                        'Unknown'  => '7f8c8d',
                    ];

                    if (isset($statusColors[$statusValue])) {
                        $event->sheet->getStyle($statusCell)->applyFromArray([
                            'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => $statusColors[$statusValue]]
                            ]
                        ]);
                    }


                    $workFromCell = "I{$row}";
                    $workFromValue = $event->sheet->getCell($workFromCell)->getValue();

                    $workFromColors = [
                        'Office' => '2980b9',
                        'Home'   => '95a5a6',
                    ];

                    if (isset($workFromColors[$workFromValue])) {
                        $event->sheet->getStyle($workFromCell)->applyFromArray([
                            'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => $workFromColors[$workFromValue]]
                            ]
                        ]);
                    }


                    $halfDayCell = "O{$row}";
                    $halfDayValue = $event->sheet->getCell($halfDayCell)->getValue();

                    $halfDayColors = [
                        'First Half'  => '16a085', // teal
                        'Second Half' => 'd35400', // orange
                    ];

                    if (isset($halfDayColors[$halfDayValue]) && !empty($halfDayValue)) {
                        $event->sheet->getStyle($halfDayCell)->applyFromArray([
                            'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => $halfDayColors[$halfDayValue]]
                            ]
                        ]);
                    }
                }

                // Borders
                $event->sheet->getStyle("A3:O{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'e9ecef']
                        ]
                    ]
                ]);


                $event->sheet->getStyle("D4:D{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle("E4:G{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle("I4:J{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle("O4:O{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
----------------------------------------------------------------
hr/attendance/index




<x-layout>
    @section('title', 'Mark Attendance')

    <div class="container-fluid p-3">
        <x-message />

        <div class="attendance-container">
            <!-- Header Section -->
            <div class=" " style=" background-color: #f8fafc;">

                <div class=" row justify-content-between px-3 pb-3">
                    <h4 class="pb-3 pt-2 fw-medium fs-5"><i class="bi bi-calendar2-range me-2"></i>Mark Attendance</h4>

                    <div class="col-auto">
                        <div class="row justify-content-start">
                            <div class=" col-auto">
                                <span class="form-label">Show Entries</span>
                                <select id="entriesPerPage" class=" form-select form-control">
                                    <option value="5" selected>5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="75">75</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <div class="col-auto" style=" margin-top: 1.3em;">
                                <div class="dropdown">
                                    <button class="dropdown-toggle control-select" type="button" id="employeeDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span id="employeeFilterText" class="">All Employees</span>
                                    </button>
                                    <ul class="dropdown-menu employee-dropdown p-2" aria-labelledby="employeeDropdown"
                                        style="width: 250px;">
                                        <li>
                                            <input type="text" class="form-control form-control-sm mb-2"
                                                placeholder="Search employees..." id="employeeSearch">
                                        </li>
                                        <li>
                                            <select id="employeeFilter" class="form-select form-select-sm" size="8"
                                                style="width: 100%; border: none;">
                                                <option value="" class="mb-4">All Employees</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->emp_id }}">
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-2">
                                                                <img src="{{ asset('employee_images/' . $employee->image) }}"
                                                                    alt="" class="avatar rounded-circle"
                                                                    style="width: 30px; height: 30px; object-fit: cover;">
                                                            </div>
                                                            <p class="mb-0 fw-bold">
                                                                {{ $employee->fullname }}
                                                            </p>
                                                            {{ $employee->Departmentid->dep_name ?? 'No Department' }}
                                                            </p>
                                                        </div>
                                                    </option>
                                                @endforeach
                                            </select>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-auto" style=" margin-top: 1.3em;">
                                <div class="dropdown">
                                    <button class="dropdown-toggle control-select" type="button" id="departmentDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span id="departmentFilterText">All Departments</span>
                                    </button>
                                    <ul class="dropdown-menu department-dropdown p-2"
                                        aria-labelledby="departmentDropdown" style="width: 200px;">
                                        <li>
                                            <input type="text" class="form-control form-control-sm mb-2"
                                                placeholder="Search departments..." id="departmentSearch">
                                        </li>
                                        <li>
                                            <select id="departmentFilter" class="form-select form-select-sm" size="8"
                                                style="width: 100%; border: none;">
                                                <option value="all" class=" mb-4">All Departments</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->dep_name }}">
                                                        {{ $department->dep_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-auto" style=" margin-top: 1.3em;">
                                <div class="dropdown">
                                    <button class="dropdown-toggle control-select" type="button" id="monthDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span id="monthText">{{ date('F Y') }}</span>
                                    </button>

                                    <ul class="dropdown-menu month-dropdown p-2" aria-labelledby="monthDropdown"
                                        style="width: 150px;">
                                        <li>
                                            <input type="text" id="monthPicker" class="form-control form-control-sm"
                                                placeholder="Select month">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-auto">

                        <div class="row mt-3">



                            <div class="col-auto d-flex gap-2">



                                <label>Select Date Range</label>
<input type="text" id="export_date_range" class="form-control" placeholder="Select date range">
<input type="hidden" id="export_start_date">
<input type="hidden" id="export_end_date">



<label>Select Months</label>
<select id="export_months" class="form-control" multiple>
<option value="01">Jan</option>
<option value="02">Feb</option>
<option value="03">Mar</option>
<option value="04">Apr</option>
<option value="05">May</option>
<option value="06">Jun</option>
<option value="07">Jul</option>
<option value="08">Aug</option>
<option value="09">Sep</option>
<option value="10">Oct</option>
<option value="11">Nov</option>
<option value="12">Dec</option>
</select>


                                <button class="action-button primary " id="exportAttendance">
                                    <i class="bi bi-download me-1"></i> Export
                                </button>

                                <button class="action-button primary d-none" id="importAttendance"
                                    data-bs-toggle="modal" data-bs-target="#importModal">
                                    <i class="bi bi-upload me-1"></i> Import
                                </button>


                                <button class="action-button primary" id="openBulkAttendanceModal">
                                    <i class="bi bi-plus-lg me-1 mt-1"></i> Mark Attendance
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add this modal at the bottom of your blade file -->
            <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Import Attendance</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="importForm" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="importFile" class="form-label">Select Excel File</label>
                                    <input type="file" class="form-control" id="importFile" name="file"
                                        accept=".xlsx,.xls,.csv" required>
                                </div>
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Please ensure your file matches the template format.
                                    <a href="{{ asset('templates/attendance_template.xlsx') }}" download=""
                                        class="alert-link">Download
                                        Template</a>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="confirmImport">Import</button>
                        </div>
                    </div>
                </div>
            </div>

            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

            <!-- Attendance Legend -->
            <div class="row justify-content-end px-3 py-2">
                <div class="col-auto mb-4">
                    <span class="f-w-500 mr-1">Note:</span>
                    <i class="fas fa-star text-warning" title="Holiday"></i>
                    <i class="fas fa-arrow-right text-lightest f-11 mx-1"></i> Holiday &nbsp;|&nbsp;

                    <i class="fas fa-calendar-week text-red" title="Day Off"></i>
                    <i class="fas fa-arrow-right text-lightest f-11 mx-1"></i> Day Off &nbsp;|&nbsp;

                    <i class="fas fa-check text-success" title="Present"></i>
                    <i class="fas fa-arrow-right text-lightest f-11 mx-1"></i> Present &nbsp;|&nbsp;

                    <i class="fas fa-star-half-alt text-info" title="Half Day"></i> First Half
                    <i class="fas fa-star-half-alt  text-info-emphasis" style="  transform: scaleX(-1);"
                        title="Half Day"></i>
                    Second Half
                    <i class="fas fa-arrow-right text-lightest f-11 mx-1"></i> Half Day &nbsp;|&nbsp;

                    <i class="fas fa-exclamation-circle text-warning" title="Late"></i>
                    <i class="fas fa-arrow-right text-lightest f-11 mx-1"></i> Late &nbsp;|&nbsp;

                    <i class="fas fa-times text-danger" title="Absent"></i>
                    <i class="fas fa-arrow-right text-lightest f-11 mx-1"></i> Absent &nbsp;|&nbsp;

                    <i class="fas fa-plane-departure text-danger" title="On Leave"></i>
                    <i class="fas fa-arrow-right text-lightest f-11 mx-1"></i> On Leave
                </div>
            </div>

            <!-- Attendance Display -->
            <div class="attendance-table-container">
                <div class="attendance-header">
                    <div class="employee-column fw-bold fs-5">Employee</div>
                    <div class="days-header" id="daysHeader">
                        <!-- Days will be dynamically generated here -->
                    </div>
                    <div class="total-column fw-bold">Total <div style="font-size: 13px"><i
                                class="fas fa-check text-success" title="Present"></i> | <i
                                class="fas fa-star-half-alt text-info" title="Half Day"></i> | <i
                                class="fas fa-exclamation-circle text-warning" title="Late"></i></div>
                    </div>
                </div>
                <div class="attendance-body" id="attendanceBody">
                    <!-- Employee rows will be dynamically generated here -->
                </div>
            </div>

            <!-- Pagination Controls -->
            <div class="pagination-controls">
                <button class="pagination-button" id="prevPage" disabled>
                    <i class="bi bi-chevron-left"></i>
                </button>
                <span class="page-info" id="pageInfo">Page 1 of 1</span>
                <button class="pagination-button" id="nextPage" disabled>
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
        <style>
            .viewmodelattendance {
                max-width: 900px;
            }

            /* Circle Styles */
            .time-calculation-circle {
                margin: 20px 0;
            }

            .circle-container {
                position: relative;
                width: 120px;
                height: 120px;
                margin: 0 auto;
            }

            .circle-progress {
                width: 100%;
                height: 100%;
            }

            .circle-chart {
                width: 100%;
                height: 100%;
            }

            .circle-bg {
                fill: none;
                stroke: #e9ecef;
                stroke-width: 3;
            }

            .circle-fill {
                fill: none;
                stroke: #4dabf7;
                stroke-width: 3;
                stroke-linecap: round;
                animation: circle-fill-animation 1.5s ease-in-out forwards;
            }

            .circle-text {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                text-align: center;
                font-weight: bold;
                font-size: 1.1rem;
            }

            @keyframes circle-fill-animation {
                0% {
                    stroke-dasharray: var(--percentage), 100;
                }

                100% {
                    stroke-dasharray: var(--percentage), 100;
                }


            }

            /* Activity Log Styles */
            .activity-log ul li {
                padding: 0.5rem 0;

                margin-top: 2em;
            }

            .activity-log ul li:last-child {
                border-bottom: none;
            }

            .activity-log ul li .bi {
                margin-right: 10px;
                color: #4dabf7;
            }

            /* Other existing styles... */
            .employee-info-view h4 {
                font-size: 1.25rem;
                color: #2c3e50;
            }

            .employee-info-view p {
                font-size: 0.9rem;
            }

            .divider {
                height: 1px;
                background-color: #e9ecef;
            }

            #viewClockIn,
            #viewClockOut {
                font-size: 0.95rem;
            }
        </style>
        <!-- View Attendance Modal -->
        <div class="modal fade" id="viewAttendanceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered viewmodelattendance">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Attendance Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body mt-1 px-5">
                        <!-- Employee Info -->
                        <div class="employee-info-view mb-4 mt-4">
                            <h4 class="mb-1 fw-semibold" id="viewEmployeeName"></h4>
                            <p class="text-muted mb-2" id="viewEmployeePosition"></p>
                            <p class="text-primary fw-medium" id="viewAttendanceDate"></p>
                        </div>

                        <div class="divider mb-3"></div>

                        <div class="row">
                            <div class="col-md-5" id="conclock">
                                <!-- Clock In Section -->
                                <div class="mb-4">
                                    <h6 class="fw-bold mb-3">Clock In</h6>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-clock me-3 text-primary"></i>
                                        <div id="viewClockIn"></div>
                                    </div>
                                </div>

                                <!-- Time Calculation Circle -->
                                <div class="time-calculation-circle mb-4 text-center">
                                    <div class="circle-container">
                                        <div class="circle-progress">
                                            <svg class="circle-chart" viewBox="0 0 36 36">
                                                <path class="circle-bg" d="M18 2.0845
                                      a 15.9155 15.9155 0 0 1 0 31.831
                                      a 15.9155 15.9155 0 0 1 0 -31.831" />
                                                <path class="circle-fill" stroke-dasharray="0, 100" d="M18 2.0845
                                      a 15.9155 15.9155 0 0 1 0 31.831
                                      a 15.9155 15.9155 0 0 1 0 -31.831" />
                                            </svg>
                                            <div class="circle-text">
                                                <span id="workedHours">0h 0m</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="small text-dark mt-2">Total Worked Time</p>
                                </div>

                                <!-- Clock Out Section -->
                                <div class="mb-4">
                                    <h6 class="fw-bold mb-3">Clock Out</h6>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-clock me-3 text-primary"></i>
                                        <div id="viewClockOut"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5" id="actclock">
                                <!-- Activity Log -->
                                <div class="activity-log">
                                    <h6 class="fw-bold mb-3">Activity</h6>
                                    <ul class="list-unstyled" id="activityList">
                                        <!-- Will be populated dynamically -->
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="dropdown">
                                    <button class="btn btn-sm border-0" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu" style="font-size: 13px;">
                                        <li>
                                            <a class="dropdown-item mt-1 fw-semibold" href="#" id="editAttendance">
                                                <i class="bi bi-pencil-square me-2"></i>Edit
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item  ms-1  fw-semibold text-danger" href="#"
                                                id="deleteAttendance">
                                                <i class="bi bi-trash me-2"></i>Reset
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="divider mb-3"></div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Edit Attendance Modal -->
        <div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Attendance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="holidayWarning" style="display: none;"></div>
                        <form id="editAttendanceForm">
                            @csrf
                            <input type="hidden" id="editAttendanceId" name="attendance_id">
                            <input type="hidden" id="editAttendanceEmployee" name="employee_id">
                            <input type="hidden" id="editAttendanceDate" name="attendancedate_no">

                            <!-- Employee Info -->
                            <div class="row justify-content-between">
                                <div class="employee-info-single col-md-7">
                                    <h6 id="editEmployeeName" class="mb-1"></h6>
                                    <p id="editEmployeePosition" class="text-muted small mb-2"></p>
                                    <p id="editAttendanceDateDisplay" class="fw-bold mb-5"></p>
                                </div>
                                <div class="col-md-5">
                                    <span class="p-2 shift-badge" style="font-size: 10.5px" id="editShiftBadge">Not
                                        assigned</span>
                                </div>
                            </div>

                            <!-- Clock In Section -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Clock In <span class="text-danger">*</span></label>
                                    <input type="text" id="editCheckInTime"
                                        class="form-control time-picker readonly-input" name="clock_in"
                                        placeholder="Set time" readonly style="background-color: #f8f9fa;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Clock In IP</label>
                                    <input type="text" id="editClockInIp" class="form-control readonly-input"
                                        name="clock_in_ip" readonly>
                                </div>
                            </div>

                            <!-- Clock Out Section -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Clock Out</label>
                                    <input type="text" id="editCheckOutTime" class="form-control time-picker"
                                        name="clock_out" placeholder="Set time">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Clock Out IP</label>
                                    <input type="text" id="editClockOutIp" class="form-control readonly-input"
                                        name="clock_out_ip" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Working From</label>
                                    <select class="form-select" name="attendance_workfrom" id="editWorkingFrom">
                                        <option value="home">Home</option>
                                        <option value="office">Office</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Assign Attendance</label>
                                    <select id="editAttendanceStatus" class="form-select" name="attendance_type"
                                        required>
                                        <option value="1">Present</option>
                                        <option value="0">Absent</option>
                                        <option value="2">Late</option>
                                        <option value="3">Half Day</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Half Day Options (shown only when half_day is selected) -->
                            <div class="row mb-3" id="editHalfDayOptions" style="display: none;">
                                <div class="col-12">
                                    <label class="form-label">Half Day Type</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="half_day_type"
                                            id="editFirstHalf" value="0" checked>
                                        <label class="form-check-label" for="editFirstHalf">First Half</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="half_day_type"
                                            id="editSecondHalf" value="1">
                                        <label class="form-check-label" for="editSecondHalf">Second Half</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">





                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveEditAttendance">Save</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Add Attendance Modal -->
        <!-- Add this modal structure to your existing code -->
        <div class="modal fade" id="stepAttendanceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Step 1: Choose Option -->
                    <div class="modal-step" id="step1">
                        <div class="modal-header">
                            <h5 class="modal-title">Selected Option</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="option-card" data-option="attendance">
                                        <div class="option-icon">
                                            <i class="bi bi-clock-history"></i>
                                        </div>
                                        <h6>Mark Attendance</h6>
                                        <p class="small text-muted">Record clock-in/clock-out times</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="option-card" data-option="leave">
                                        <div class="option-icon">
                                            <i class="bi bi-calendar-x"></i>
                                        </div>
                                        <h6>Assign Leave</h6>
                                        <p class="small text-muted">Assign leave to employees</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>

                    <!-- Step 2: Mark Attendance -->
                    <div class="modal-step d-none" id="step2-attendance">
                        <div class="modal-header">
                            <h5 class="modal-title">Mark Attendance</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Mark Attendance Form Content (same as your existing form) -->
                            <form id="addAttendanceForm">
                                @csrf
                                <input type="hidden" id="addAttendanceEmployee" name="employee_id">
                                <input type="hidden" id="addAttendanceDate" name="attendancedate_no">

                                <div class="row justify-content-between">
                                    <div class="employee-info-single col-md-7">
                                        <h6 id="addEmployeeName" class="mb-1"></h6>
                                        <p id="addEmployeePosition" class="text-muted small mb-2"></p>
                                        <p id="addAttendanceDateDisplay" class="fw-bold mb-5"></p>
                                    </div>
                                    <div class="col-md-5">
                                        <span class="p-2 shift-badge" style="font-size: 10.5px" id="addShiftBadge">Not
                                            assigned</span>
                                    </div>
                                </div>

                                <!-- Clock In Section -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Clock In <span class="text-danger">*</span></label>
                                        <input type="text" id="addCheckInTime"
                                            class="form-control time-picker readonly-input" name="clock_in"
                                            placeholder="Set time" readonly style="background-color: #f8f9fa;">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Clock In IP</label>
                                        <input type="text" id="addClockInIp" class="form-control readonly-input"
                                            name="clock_in_ip" readonly>
                                    </div>
                                </div>

                                <!-- Clock Out Section -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Clock Out</label>
                                        <input type="text" id="addCheckOutTime" class="form-control time-picker"
                                            name="clock_out" placeholder="Set time">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Clock Out IP</label>
                                        <input type="text" id="addClockOutIp" class="form-control readonly-input"
                                            name="clock_out_ip" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Working From</label>
                                        <select class="form-select" name="attendance_workfrom" id="addWorkingFrom">
                                            <option value="home">Home</option>
                                            <option value="office">Office</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Assign Attendance</label>
                                        <select id="addAttendanceStatus" class="form-select" name="attendance_type"
                                            required>
                                            <option value="1">Present</option>
                                            <option value="0">Absent</option>
                                            <option value="2">Late</option>
                                            <option value="3">Half Day</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Half Day Options -->
                                <div class="row mb-3" id="addHalfDayOptions" style="display: none;">
                                    <div class="col-12">
                                        <label class="form-label">Half Day Type</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="half_day_type"
                                                id="addFirstHalf" value="0" checked>
                                            <label class="form-check-label" for="addFirstHalf">First Half</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="half_day_type"
                                                id="addSecondHalf" value="1">
                                            <label class="form-check-label" for="addSecondHalf">Second Half</label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" id="backToStep1">Back</button>
                            <button type="button" class="btn btn-primary" id="saveaddAttendance">Save</button>
                        </div>
                    </div>

                    <div class="modal-step d-none" id="step2-leave">
                        <div class="modal-header">
                            <h5 class="modal-title">Assign Leave</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Assign Leave Form Content -->
                           <form id="assignLeaveFormModal" action="{{ route('leave.store') }}" method="POST">
    @csrf
    <input type="hidden" id="assignLeaveEmployee" name="employee_id">
    <input type="hidden" id="assignLeaveDate" name="date">
    <input type="hidden" name="leavedate_no" id="assignLeaveDateField">
    <input type="hidden" name="duration" id="assignLeaveDuration">
    <input type="hidden" name="status" value="1">
                                <div class="row justify-content-between">
                                    <div class="employee-info-single col-md-7">
                                        <h6 id="assignEmployeeName" class="mb-1"></h6>
                                        <p id="assignEmployeePosition" class="text-muted small mb-2"></p>
                                        <p id="assignLeaveDateDisplay" class="fw-bold mb-5"></p>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="assignLeaveType" class="form-label">Leave Type</label>
                                    <select id="assignLeaveType" class="form-select" name="leave_type_id" required>
                                        <option value="">Select Leave Type</option>
                                        <!-- Options will be populated dynamically -->
                                    </select>
                                    <small id="assignLeaveTypeInfo" class="text-danger ms-2 mt-3"
                                        style="font-size: 14px"></small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Duration</label>
                                    <div class="d-flex flex-wrap gap-3 mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="duration"
                                                id="assignFullDay" value="1" checked>
                                            <label class="form-check-label" for="assignFullDay">Full Day</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="duration"
                                                id="assignFirstHalf" value="3">
                                            <label class="form-check-label" for="assignFirstHalf">First Half</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="duration"
                                                id="assignSecondHalf" value="4">
                                            <label class="form-check-label" for="assignSecondHalf">Second Half</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="assignLeaveReason" class="form-label">Reason for absence</label>
                                    <textarea id="assignLeaveReason" class="form-control" name="reason" rows="3"
                                        required placeholder="e.g. Feeling not well"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="assignLeaveFile" class="form-label">Attachment (Optional)</label>
                                    <input type="file" id="assignLeaveFile" class="form-control" name="file">


                                    <small class="text-muted">You can upload one file (image, document, CSV,
                                        etc.)</small>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" id="backToStep1Leave">Back</button>
                            <button type="button" class="btn btn-primary" id="saveAssignLeave">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Attendance Modal -->
        <div class="modal fade" id="bulkAttendanceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg bulkmodal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Mark Attendance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="col-md-12">
                        <div class="alert text-info" type="info" icon="info-circle">
                            <i class="fa fa-info-circle"></i>
                            The existing attendance and holiday's will be overridden.
                        </div>
                    </div>
                    <div class="modal-body">

                        <form id="bulkAttendanceForm">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="dropdown">
                                        <label class="mb-3">Departments</label>
                                        <button class="dropdown-toggle control-select" type="button"
                                            id="bulkDepartmentDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="bulkDepartmentFilterText">All Departments</span>
                                        </button>
                                        <ul class="dropdown-menu department-dropdown p-2"
                                            aria-labelledby="bulkDepartmentDropdown" style="width: 300px;">
                                            <li>
                                                <input type="text" class="form-control form-control-sm mb-2"
                                                    placeholder="Search departments..." id="bulkDepartmentSearch">
                                            </li>
                                            <li class="d-flex justify-content-between px-2 mb-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    id="bulkSelectAllDepartments">Select All</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    id="bulkDeselectAllDepartments">Deselect All</button>
                                            </li>
                                            <li>
                                                <select id="bulkDepartmentFilter" class="form-select form-select-sm"
                                                    size="8" multiple>
                                                    <option value="all">All Departments</option>
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->dep_name }}"
                                                            data-admin="{{ $department->admin_name }}">
                                                            {{ $department->dep_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-md-4 align-self-center">
                                    <label for="departmentadmin" class="form-label">Department Admin</label>
                                    <input type="text" name="attendance_depadmin" id="departmentadmin"
                                        class="form-control" readonly>
                                </div>
                                <div class="col-md-4">
                                    <div class="dropdown">
                                        <label class="mb-3">Employees</label>
                                        <button class="dropdown-toggle control-select" type="button"
                                            id="bulkEmployeeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="bulkEmployeeFilterText">All Employees</span>
                                        </button>
                                        <ul class="dropdown-menu employee-dropdown p-2"
                                            aria-labelledby="bulkEmployeeDropdown" style="width: 300px;">
                                            <li>
                                                <input type="text" class="form-control form-control-sm mb-2"
                                                    placeholder="Search employees..." id="bulkEmployeeSearch">
                                            </li>
                                            <li class="d-flex justify-content-between px-2 mb-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    id="bulkSelectAllEmployees">Select All</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    id="bulkDeselectAllEmployees">Deselect All</button>
                                            </li>
                                            <li>
                                                <select id="bulkEmployeeFilter" class="form-select form-select-sm"
                                                    size="8" style="width: 100%; border: none;" multiple
                                                    name="employee_ids[]">
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->emp_id }}">
                                                            <div class="d-flex">
                                                                <div class="col-auto">
                                                                    <img src="{{ asset('employee_images/' . $employee->image) }}"
                                                                        alt="" class="avatar">
                                                                </div>
                                                                <div class="col-auto">
                                                                    <p> {{ $employee->fullname }}</p>
                                                                </div>
                                                            </div>
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label for="bulkStatus" class="form-label">Assign Attendance</label>
                                    <select id="bulkStatus" class="form-select" name="attendance_type" required>
                                        <option value="1">Present</option>
                                        <option value="0">Absent</option>
                                        <option value="2">Late</option>
                                        <option value="3">Half Day</option>
                                    </select>
                                </div>

                                <!-- Half Day Options (shown only when half_day is selected) -->
                                <div class="col-md-4" id="bulkHalfDayOptions" style="display: none;">
                                    <label class="form-label">Half Day Type</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="half_day_type"
                                            id="bulkFirstHalf" value="0" checked>
                                        <label class="form-check-label" for="bulkFirstHalf">First Half</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="half_day_type"
                                            id="bulkSecondHalf" value="1">
                                        <label class="form-check-label" for="bulkSecondHalf">Second Half</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Assign Attendance By</label>
                                    <div class="d-flex flex-wrap gap-3 mt-2">
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input rounded-radio"
                                                name="mark_attendance" id="multiple" value="1">
                                            <label class="form-check-label" for="multiple">Multiple</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input rounded-radio"
                                                name="mark_attendance" id="month" value="2">
                                            <label class="form-check-label" for="month">Month</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date Range Fields -->
                                <div class="col-md-4 d-none" id="dateRangeFields">
                                    <label for="selectedDateRange" class="form-label">Date Range</label>
                                    <p class="" id="openDateRangePicker">
                                        <input type="text" class="form-control" id="selectedDateRange" readonly
                                            placeholder="Select date range" value="24-06-2025 To 24-06-2025"
                                            width="100">
                                    </p>
                                    <input type="hidden" id="date_range_from" name="attenddaterange_from">
                                    <input type="hidden" id="date_range_to" name="attenddaterange_to">
                                    <!-- Date Range Picker Container (hidden by default) -->
                                    <div class="date-range-picker-container mt-2 d-none" id="dateRangePickerContainer">
                                        <span id="dateRangeDisplay" style="display: none;"></span>
                                        <div class="date-range-calendar">
                                            <div class="month-container">
                                                <div class="month-header" id="month1Header">
                                                    <button type="button" class="month-nav-btn" id="prevMonth">
                                                        <i class="bi bi-chevron-left"></i>
                                                    </button>
                                                    <span>Jun 2025</span>
                                                </div>
                                                <div class="weekdays">
                                                    <span>Su</span>
                                                    <span>Mo</span>
                                                    <span>Tu</span>
                                                    <span>We</span>
                                                    <span>Th</span>
                                                    <span>Fr</span>
                                                    <span>Sa</span>
                                                </div>
                                                <div class="days-grid" id="month1Days"></div>
                                            </div>

                                            <div class="month-container">
                                                <div class="month-header" id="month2Header">
                                                    <span>Jul 2025</span>
                                                    <button class="month-nav-btn" id="nextMonth"><i
                                                            class="bi bi-chevron-right"></i></button>
                                                </div>
                                                <div class="weekdays">
                                                    <span>Su</span>
                                                    <span>Mo</span>
                                                    <span>Tu</span>
                                                    <span>We</span>
                                                    <span>Th</span>
                                                    <span>Fr</span>
                                                    <span>Sa</span>
                                                </div>
                                                <div class="days-grid" id="month2Days"></div>
                                            </div>
                                        </div>

                                        <div class="date-range-footer border-0">
                                            <button type="button"
                                                class="btn btn-outline-secondary text-start btn-sm cancel-btn">Cancel</button>
                                            <button type="button"
                                                class="btn btn-sm btn-primary apply-btn">Apply</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Month Field -->
                                <div class="col-md-4 d-none" id="monthField">
                                    <label for="monthYearPicker" class="form-label">Month-Year</label>
                                    <input type="text" id="monthYearPicker" class="form-control" name="month_year"
                                        placeholder="Select month">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label">Clock In <span class="text-danger">*</span></label>
                                    <input type="text" id="checkInTime" class="form-control time-picker readonly-input"
                                        name="clock_in" placeholder="Set time" readonly
                                        style="background-color: #f8f9fa;">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Clock Out</label>
                                    <input type="text" id="checkOutTime" class="form-control time-picker"
                                        name="clock_out" placeholder="Set time">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="bulkworkingFrom">Working From</label>
                                    <select class="form-select" name="attendance_workfrom" id="bulkworkingFrom">
                                        <option value="home">Home</option>
                                        <option value="office">Office</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveBulkAttendance">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>

  document.getElementById('exportAttendance').addEventListener('click', function () {

    let employee = document.getElementById('employeeFilter').value;
    let department = document.getElementById('departmentFilter').value;

    let monthPicker = document.getElementById('monthPicker');
    let month = '';

    if (monthPicker && monthPicker._flatpickr) {
        let selectedDate = monthPicker._flatpickr.selectedDates[0];

        if (selectedDate) {
            let y = selectedDate.getFullYear();
            let m = ('0' + (selectedDate.getMonth() + 1)).slice(-2);
            month = `${y}-${m}`;
        }
    }

    // ✅ Correct date range fields
    let startDate = document.getElementById('export_start_date').value;
    let endDate = document.getElementById('export_end_date').value;

    let count = document.getElementById('entriesPerPage').value;

    let params = new URLSearchParams();

    if (employee) params.append('employee_id', employee);
    if (department && department !== 'all') params.append('department', department);
    if (month) params.append('month', month);
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);
    if (count) params.append('count', count);

    window.location.href = "{{ route('attendances.export') }}?" + params.toString();
});
        </script>
        <script>
            const initialHolidays = @json($holidays);
        </script>
        <script>
            const approvedLeaves = @json($approvedLeaves);
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {




                // Initialize date pickers
                const monthPicker = flatpickr("#monthPicker", {
                    plugins: [
                        new monthSelectPlugin({
                            shorthand: true,
                            dateFormat: "F Y",
                            altFormat: "F Y",
                            theme: "light"
                        })
                    ],
                    allowInput: true,
                    onChange: function (selectedDates, dateStr, instance) {
                        document.getElementById('monthText').textContent = dateStr;
                        currentDate = new Date(selectedDates[0]);
                        refreshAttendance();
                    }
                });
                const timePickerConfig = {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "h:i K",
                    time_24hr: false,
                    minuteIncrement: 15,
                    disableMobile: true, // Add this to prevent mobile time picker
                    time_zone: "" // Explicitly set no timezone
                };

                // Initialize time pickers for all modals
                flatpickr("#editCheckInTime", timePickerConfig);
                flatpickr("#editCheckOutTime", timePickerConfig);
                flatpickr("#addCheckInTime", timePickerConfig);
                flatpickr("#addCheckOutTime", timePickerConfig);
                flatpickr("#checkInTime", timePickerConfig);
                flatpickr("#checkOutTime", timePickerConfig);

                // Initialize month year picker for bulk modal
                const monthYearPicker = flatpickr("#monthYearPicker", {
                    plugins: [
                        new monthSelectPlugin({
                            shorthand: true,
                            dateFormat: "M Y",
                            altFormat: "F Y",
                            theme: "light"
                        })
                    ],
                    allowInput: true
                });

                // Date Range Picker Class
                class DateRangePicker {
                    constructor() {
                        this.currentDate = new Date();
                        this.selectedRange = {
                            start: new Date(),
                            end: new Date()
                        };
                        this.init();
                    }

                    init() {
                        this.render();
                        this.setupEventListeners();
                    }

                    render() {
                        const month1 = new Date(this.currentDate);
                        this.renderMonth(month1, 'month1Header', 'month1Days');

                        const month2 = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 1);
                        this.renderMonth(month2, 'month2Header', 'month2Days');

                        this.updateRangeDisplay();
                    }

                    renderMonth(date, headerId, daysId) {
                        const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                        const headerElement = document.querySelector(`#${headerId} span`);
                        headerElement.textContent = `${monthNames[date.getMonth()]} ${date.getFullYear()}`;

                        const firstDay = new Date(date.getFullYear(), date.getMonth(), 1).getDay();
                        const totalDays = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();

                        const daysGrid = document.getElementById(daysId);
                        daysGrid.innerHTML = '';

                        const prevMonthDays = new Date(date.getFullYear(), date.getMonth(), 0).getDate();
                        for (let i = 0; i < firstDay; i++) {
                            const day = document.createElement('div');
                            day.className = 'day disabled';
                            day.textContent = prevMonthDays - firstDay + i + 1;
                            daysGrid.appendChild(day);
                        }

                        const today = new Date();
                        for (let i = 1; i <= totalDays; i++) {
                            const day = document.createElement('div');
                            day.className = 'day';
                            day.dataset.date = this.formatDate(new Date(date.getFullYear(), date.getMonth(), i),
                                'dd-MM-yyyy');
                            day.textContent = i;

                            if (i === today.getDate() &&
                                date.getMonth() === today.getMonth() &&
                                date.getFullYear() === today.getFullYear()) {
                                day.classList.add('today');
                            }

                            const currentDate = new Date(date.getFullYear(), date.getMonth(), i);
                            if (this.selectedRange.start && this.selectedRange.end) {
                                if (currentDate >= this.selectedRange.start && currentDate <= this.selectedRange
                                    .end) {
                                    day.classList.add('in-range');
                                }
                                if (this.formatDate(currentDate) === this.formatDate(this.selectedRange.start)) {
                                    day.classList.add('selected');
                                }
                                if (this.formatDate(currentDate) === this.formatDate(this.selectedRange.end)) {
                                    day.classList.add('selected');
                                }
                            }

                            daysGrid.appendChild(day);
                        }

                        const daysShown = firstDay + totalDays;
                        const nextMonthDays = 42 - daysShown;
                        for (let i = 1; i <= nextMonthDays; i++) {
                            const day = document.createElement('div');
                            day.className = 'day disabled';
                            day.textContent = i;
                            daysGrid.appendChild(day);
                        }
                    }

                    setupEventListeners() {
                        document.getElementById('month1Days').addEventListener('click', (e) => this.handleDayClick(
                            e));
                        document.getElementById('month2Days').addEventListener('click', (e) => this.handleDayClick(
                            e));

                        document.getElementById('prevMonth').addEventListener('click', (e) => {
                            e.preventDefault();
                            this.currentDate.setMonth(this.currentDate.getMonth() - 1);
                            this.render();
                        });

                        document.getElementById('nextMonth').addEventListener('click', (e) => {
                            e.preventDefault();
                            this.currentDate.setMonth(this.currentDate.getMonth() + 1);
                            this.render();
                        });

                        document.querySelector('.cancel-btn').addEventListener('click', () => {
                            const today = new Date();
                            this.selectedRange = {
                                start: new Date(today),
                                end: new Date(today)
                            };
                            this.updateRangeDisplay();
                            document.getElementById('selectedDateRange').value =
                                `${this.formatDate(today, 'dd-MM-yyyy')} To ${this.formatDate(today, 'dd-MM-yyyy')}`;
                            document.getElementById('dateRangePickerContainer').classList.add('d-none');
                            this.render();
                        });

                        document.querySelector('.apply-btn').addEventListener('click', () => {
                            if (this.selectedRange.start && this.selectedRange.end) {
                                const formattedRange =
                                    `${this.formatDate(this.selectedRange.start, 'dd-MM-yyyy')} To ${this.formatDate(this.selectedRange.end, 'dd-MM-yyyy')}`;
                                this.updateRangeDisplay(formattedRange);
                                document.getElementById('selectedDateRange').value = formattedRange;
                                document.getElementById('dateRangePickerContainer').classList.add('d-none');

                                // Set the hidden input values for the form
                                document.getElementById('date_range_from').value = this.formatDate(this
                                    .selectedRange.start, 'yyyy-MM-dd');
                                document.getElementById('date_range_to').value = this.formatDate(this
                                    .selectedRange.end, 'yyyy-MM-dd');
                            }
                        });

                        document.getElementById('openDateRangePicker').addEventListener('click', () => {
                            const picker = document.getElementById('dateRangePickerContainer');
                            picker.classList.toggle('d-none');
                        });
                    }

                    handleDayClick(e) {
                        if (e.target.classList.contains('day') && !e.target.classList.contains('disabled')) {
                            const dateStr = e.target.dataset.date;
                            const [day, month, year] = dateStr.split('-');
                            const date = new Date(year, month - 1, day);

                            if (!this.selectedRange.start || this.selectedRange.end) {
                                this.selectedRange = {
                                    start: date,
                                    end: null
                                };
                            } else if (date < this.selectedRange.start) {
                                this.selectedRange = {
                                    start: date,
                                    end: this.selectedRange.start
                                };
                            } else {
                                this.selectedRange.end = date;
                            }

                            this.render();
                        }
                    }

                    updateRangeDisplay(text = null) {
                        if (!text) {
                            if (this.selectedRange.start && this.selectedRange.end) {
                                text =
                                    `${this.formatDate(this.selectedRange.start, 'dd-MM-yyyy')} To ${this.formatDate(this.selectedRange.end, 'dd-MM-yyyy')}`;
                            } else if (this.selectedRange.start) {
                                text =
                                    `${this.formatDate(this.selectedRange.start, 'dd-MM-yyyy')} To ${this.formatDate(this.selectedRange.start, 'dd-MM-yyyy')}`;
                            } else {
                                text = 'Select date range';
                            }
                        }
                        document.getElementById('dateRangeDisplay').textContent = text;
                    }

                    formatDate(date, format = 'dd-MM-yyyy') {
                        if (!date) return '';

                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const year = date.getFullYear();
                        const monthName = date.toLocaleString('default', {
                            month: 'short'
                        });

                        return format
                            .replace('dd', day)
                            .replace('MM', month)
                            .replace('MMM', monthName)
                            .replace('yyyy', year);
                    }
                }

                // Initialize date range picker
                const dateRangePicker = new DateRangePicker();

                // Current date tracking
                let currentDate = new Date();
                let attendances = [];

                let employees = @json($employees);
                const shifts = @json($shifts ?? []);
                let holidays = initialHolidays || [];
                let currentPage = 1;
                let entriesPerPage = 5;

                // DOM elements
                const prevPageBtn = document.getElementById('prevPage');
                const nextPageBtn = document.getElementById('nextPage');
                const pageInfo = document.getElementById('pageInfo');
                const employeeFilter = document.getElementById('employeeFilter');
                const departmentFilter = document.getElementById('departmentFilter');
                const entriesPerPageSelect = document.getElementById('entriesPerPage');
                const viewAttendanceModal = new bootstrap.Modal(document.getElementById('viewAttendanceModal'));
                const editAttendanceModal = new bootstrap.Modal(document.getElementById('editAttendanceModal'));

                const bulkAttendanceModal = new bootstrap.Modal(document.getElementById('bulkAttendanceModal'));
                const exportBtn = document.getElementById('exportAttendance');




                const stepModal = bootstrap.Modal.getOrCreateInstance(document.getElementById("stepAttendanceModal"));


                let selectedOption = '';
                let selectedEmployee = null;
                let selectedDate = '';
                // Open the step modal when clicking on a day cell
                function openStepModal(employee, dateString, isEdit = false) {
                    selectedEmployee = employee;
                    selectedDate = dateString;
                    window.isEditMode = isEdit; // Store whether we're in edit mode

                    // Reset the modal to step 1
                    document.getElementById('step1').classList.remove('d-none');
                    document.getElementById('step2-attendance').classList.add('d-none');
                    document.getElementById('step2-leave').classList.add('d-none');



                    // Show the modal
                    stepModal.show();
                }
                document.querySelectorAll('.option-card').forEach(card => {
                    card.addEventListener('click', function () {
                        // Remove selected class from all options
                        document.querySelectorAll('.option-card').forEach(c => {
                            c.classList.remove('selected');
                        });

                        // Add selected class to clicked option
                        this.classList.add('selected');

                        selectedOption = this.dataset.option;

                        // Proceed to step 2 after a short delay for visual feedback
                        setTimeout(() => {
                            proceedToStep2();
                        }, 300);
                    });
                });

                function proceedToStep2() {
                    // Hide step 1
                    document.getElementById('step1').classList.add('d-none');

                    // Show the appropriate step 2 based on selection
                    if (selectedOption === 'attendance') {

                        // Initialize the mark attendance form
                        initializeMarkAttendanceForm(selectedEmployee, selectedDate);

                        document.getElementById('step2-attendance').classList.remove('d-none');
                    } else if (selectedOption === 'leave') {
                        // Initialize the assign leave form
                        initializeAssignLeaveForm(selectedEmployee, selectedDate);
                        document.getElementById('step2-leave').classList.remove('d-none');
                    }
                }

                // Back button handlers
                document.getElementById('backToStep1').addEventListener('click', function () {
                    document.getElementById('step2-attendance').classList.add('d-none');
                    document.getElementById('step1').classList.remove('d-none');
                });

                document.getElementById('backToStep1Leave').addEventListener('click', function () {
                    document.getElementById('step2-leave').classList.add('d-none');
                    document.getElementById('step1').classList.remove('d-none');
                });












                function initializeMarkAttendanceForm(employee, dateString) {
                    // Format date for display
                    const parsedDate = new Date(dateString);
                    const formattedDate = formatDisplayDate(parsedDate);

                    // Set employee info
                    document.getElementById('addEmployeeName').textContent = employee.fullname;
                    document.getElementById('addEmployeePosition').textContent = employee.dep_name ||
                        'No Position';
                    document.getElementById('addAttendanceDateDisplay').textContent = `Date: ${formattedDate}`;

                    // Set hidden fields
                    document.getElementById('addAttendanceEmployee').value = employee.emp_id;
                    document.getElementById('addAttendanceDate').value = dateString;
                    // Check if this date is a holiday for this employee
                    // Check if this date is a holiday for this employee
                    const isHoliday = isEmployeeHoliday(employee, dateString);

                    const holidayWarning = document.getElementById('addHolidayWarning');

                    //                 if (Array.isArray(isHoliday) && isHoliday.length > 0) {
                    //                     const holidayList = isHoliday.map(h => h.occasion).join(', ');
                    //                     // holidayWarning.style.display = 'block';
                    //                     holidayWarning.innerHTML = `
                    //     <div class="alert alert-warning d-flex align-items-center mb-3" style="font-size:12px;" role="alert">
                    //         <div>
                    //             <strong><i class="fas fa-exclamation-triangle me-2"></i>Overwriting Holiday!</strong>
                    //             This date is added as a holiday (${holidayList}).
                    //         </div>
                    //     </div>
                    // `;
                    //                 }

                    // Set shift badge
                    let shiftName = 'Not assigned';
                    let badgeClass = 'bg-secondary';
                    let shiftFromTime = '09:00'; // Default shift time
                    let shiftToTime = '18:00'; // Default shift time

                    if (shifts[employee.emp_id] && shifts[employee.emp_id][dateString]) {
                        const shiftType = shifts[employee.emp_id][dateString].shift_type;
                        shiftFromTime = shifts[employee.emp_id][dateString].shift_from_time || '09:00';
                        shiftToTime = shifts[employee.emp_id][dateString].shift_to_time || '18:00';

                        switch (shiftType) {
                            case 0:
                                shiftName = 'General Shift(09:00AM-18:00PM)';
                                badgeClass = 'bg-primary';
                                break;
                            case 2:
                                shiftName = 'Night Shift(10:00PM-06:00AM)';
                                badgeClass = 'bg-dark';
                                break;
                            case 1:
                                shiftName = 'Morning Shift(07:00AM-17:00PM)';
                                badgeClass = 'bg-warning text-secondary';
                                break;
                            case 3:
                                shiftName = 'Day Off';
                                badgeClass = 'bg-info';
                                break;
                            default:
                                shiftName = shiftType;
                                badgeClass = 'bg-secondary';
                        }
                    }

                    const shiftBadge = document.getElementById('addShiftBadge');
                    shiftBadge.textContent = shiftName;
                    shiftBadge.className = `badge p-2 shift-badge ${badgeClass}`;

                    // Clear form fields
                    document.getElementById('addCheckInTime')._flatpickr.clear();
                    document.getElementById('addCheckOutTime')._flatpickr.clear();
                    document.getElementById('addWorkingFrom').value = 'office';
                    document.getElementById('addAttendanceStatus').value = '1'; // default to present
                    document.getElementById('addHalfDayOptions').style.display = 'none';

                    // Set default clock in/out times based on shift
                    const [fromHour, fromMin] = shiftFromTime.split(':').map(Number);
                    const [toHour, toMin] = shiftToTime.split(':').map(Number);

                    // Convert to 12-hour format
                    const fromAmPm = fromHour >= 12 ? 'PM' : 'AM';
                    const fromTwelveHour = fromHour % 12 || 12;
                    const fromTimeStr = `${fromTwelveHour}:${fromMin.toString().padStart(2, '0')} ${fromAmPm}`;

                    const toAmPm = toHour >= 12 ? 'PM' : 'AM';
                    const toTwelveHour = toHour % 12 || 12;
                    const toTimeStr = `${toTwelveHour}:${toMin.toString().padStart(2, '0')} ${toAmPm}`;

                    document.getElementById('addCheckInTime')._flatpickr.setDate(fromTimeStr, true, 'h:i K');
                    document.getElementById('addCheckOutTime')._flatpickr.setDate(toTimeStr, true, 'h:i K');

                    // Add event listener for half day type changes
                    document.getElementById('addAttendanceStatus').addEventListener('change', function () {
                        if (this.value === '3') { // half_day
                            const halfDayType = document.querySelector('input[name="half_day_type"]:checked')
                                .value;
                            updateHalfDayTimes(halfDayType, shiftFromTime, shiftToTime, 'add');
                        } else if (this.value === '1' || this.value === '2') { // present or late
                            // Reset to normal times
                            document.getElementById('addCheckInTime')._flatpickr.setDate(fromTimeStr, true,
                                'h:i K');
                            document.getElementById('addCheckOutTime')._flatpickr.setDate(toTimeStr, true,
                                'h:i K');
                        } else if (this.value === '0') { // absent
                            document.getElementById('addCheckInTime')._flatpickr.clear();
                            document.getElementById('addCheckOutTime')._flatpickr.clear();
                        }
                    });

                    // Add event listener for half day type radio buttons
                    document.querySelectorAll('input[name="half_day_type"]').forEach(radio => {
                        radio.addEventListener('change', function () {
                            if (document.getElementById('addAttendanceStatus').value ===
                                '3') { // half_day
                                updateHalfDayTimes(this.value, shiftFromTime, shiftToTime, 'add');
                            }
                        });
                    });

                    // Get IP address
                    fetch('/get-ip-address')
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('addClockInIp').value = data.ip;
                            document.getElementById('addClockOutIp').value = data.ip;
                        })
                        .catch(() => {
                            document.getElementById('addClockInIp').value = 'Not available';
                            document.getElementById('addClockOutIp').value = 'Not available';
                        });

                    // Show add modal
                    stepModal.show();
                }

                // Initialize assign leave form

                function initializeAssignLeaveForm(employee, dateString) {
                    // Format date for display
                    const parsedDate = new Date(dateString);
                    const formattedDate = formatDisplayDate(parsedDate);

                    // Set employee info
                    document.getElementById('assignEmployeeName').textContent = employee.fullname;
                    document.getElementById('assignEmployeePosition').textContent = employee.dep_name || 'No Position';
                    document.getElementById('assignLeaveDateDisplay').textContent = `Date: ${formattedDate}`;

                    // Set hidden fields - CORRECT PARAMETER NAMES
                    document.getElementById('assignLeaveEmployee').value = employee.emp_id;
                    document.getElementById('assignLeaveDate').value =
                        dateString; // This should be 'date' not 'leavedate_no'


                          document.getElementById('assignLeaveDateField').value = dateString;

                    // Show loading state
                    const leaveTypeSelect = document.getElementById('assignLeaveType');
                    leaveTypeSelect.innerHTML = '<option value="">Loading leave types...</option>';
                    leaveTypeSelect.disabled = true;

                    // Load leave types for this employee - FIXED ENDPOINT
                    fetch(`/dashboard/employees/leave/types/${employee.emp_id}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            leaveTypeSelect.innerHTML = '<option value="">Select Leave Type</option>';
                            leaveTypeSelect.disabled = false;

                            if (data.length === 0) {
                                const option = document.createElement('option');
                                option.value = '';
                                option.textContent = 'No leave types available for this employee';
                                leaveTypeSelect.appendChild(option);
                                document.getElementById('assignLeaveTypeInfo').textContent = '';
                            } else {
                                data.forEach(leaveType => {
                                    const option = document.createElement('option');
                                    option.value = leaveType.id;
                                    option.textContent = leaveType.text;
                                    option.dataset.days = leaveType.leave_days;
                                    option.dataset.remaining = leaveType.remaining_days;
                                    option.dataset.start = leaveType.start_date;
                                    option.dataset.end = leaveType.end_date;
                                    leaveTypeSelect.appendChild(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching leave types:', error);
                            showToast('Error loading leave types. Please try again.', 'error');

                            leaveTypeSelect.innerHTML =
                                '<option value="" disabled>Error loading leave types</option>';
                            leaveTypeSelect.disabled = true;
                        });
                }
                // Save add attendance handler
                document.getElementById('saveaddAttendance').addEventListener('click', function () {
                    const clockIn = document.getElementById('addCheckInTime').value;
                    const clockOut = document.getElementById('addCheckOutTime').value;
                    const attendanceType = document.getElementById('addAttendanceStatus').value;

                    // Validate for absent status
                    if (attendanceType === '0' && (clockIn || clockOut)) {
                        showToast('Clock in/out times must be empty for absent status', 'error');
                        return;
                    }

                    // Validate for other statuses
                    if (attendanceType !== '0' && !clockIn) {
                        showToast('Clock In time is required', 'error');
                        return;
                    }

                    const formData = new FormData(document.getElementById('addAttendanceForm'));

                    // Convert string values to integers for database
                    formData.set('attendance_type', attendanceType);
                    formData.set('attendance_workfrom', document.getElementById('addWorkingFrom').value ===
                        'office' ? '1' : '0');

                    if (attendanceType === '3') { // half_day
                        formData.set('half_day_type', document.querySelector(
                            'input[name="half_day_type"]:checked').value === '1' ? '1' : '0');
                    }

                    // Show loading state
                    const saveBtn = document.getElementById('saveaddAttendance');
                    saveBtn.disabled = true;
                    saveBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';

                    fetch('attendances/create', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                fetchAttendances();
                                stepModal.hide();
                                showToast(data.message, 'success');
                            } else {
                                showToast(data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('An error occurred while saving the attendance', 'error');
                        })
                        .finally(() => {
                            saveBtn.disabled = false;
                            saveBtn.innerHTML = 'Save';
                        });
                });

                function formatDateForDisplay(dateString) {
                    const options = {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    };
                    return new Date(dateString).toLocaleDateString(undefined, options);
                }






// Update leave type info display for step modal
document.getElementById('assignLeaveType').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const infoElement = document.getElementById('assignLeaveTypeInfo');
    const selectedDate = document.getElementById('assignLeaveDate').value;

    if (selectedOption && selectedOption.value) {
        const days = selectedOption.dataset.days;
        const remaining = selectedOption.dataset.remaining;
        const startDate = selectedOption.dataset.start;
        const endDate = selectedOption.dataset.end;

        // Format dates for display
        const formattedStartDate = formatDateForDisplay(startDate);
        const formattedEndDate = formatDateForDisplay(endDate);

        // Check if selected date is within the valid range
        const selectedDateObj = new Date(selectedDate);
        const startDateObj = new Date(startDate);
        const endDateObj = new Date(endDate);

        let dateValidationMessage = '';
        let dateValid = true;

        if (selectedDateObj < startDateObj) {
            dateValidationMessage = `Leave not applicable for this date. Only applicable from ${formattedStartDate}`;
            dateValid = false;
        } else if (selectedDateObj > endDateObj) {
            dateValidationMessage = `Leave not applicable for this date. Only applicable until ${formattedEndDate}`;
            dateValid = false;
        }

        infoElement.textContent =
            `Available: ${remaining}/${days} days | Valid from ${formattedStartDate} to ${formattedEndDate}`;

        if (!dateValid) {
            infoElement.innerHTML += `<br><span class="text-danger" style="font-size:12px;">
                <i class="fas fa-exclamation-circle me-1"></i>${dateValidationMessage}
            </span>`;
        }

        // Show warning if no remaining days
        if (parseInt(remaining) <= 0) {
            infoElement.innerHTML += `<br><span class="text-danger" style="font-size:12px;">
                <i class="fas fa-exclamation-circle me-1"></i>Cannot assign leave, no remaining days
            </span>`;
            infoElement.style.color = 'red';
        } else {
            infoElement.style.color = '#6c757d';
        }
    } else {
        infoElement.textContent = '';
        infoElement.style.color = '#6c757d';
    }
});










document.getElementById('saveAssignLeave').addEventListener('click', function() {
    const form = document.getElementById('assignLeaveFormModal');
    const formData = new FormData(form);

    const singleDate = document.getElementById('assignLeaveDate').value;
    const leaveTypeSelect = document.getElementById('assignLeaveType');
    const selectedOption = leaveTypeSelect.options[leaveTypeSelect.selectedIndex];

    // Validate if leave type is selected
    if (!selectedOption || !selectedOption.value) {
        showToast('Please select a leave type', 'error');
        return;
    }

    // Get leave type date range
    const startDate = selectedOption.dataset.start;
    const endDate = selectedOption.dataset.end;
    const selectedDateObj = new Date(singleDate);
    const startDateObj = new Date(startDate);
    const endDateObj = new Date(endDate);

    // Validate date range
    if (selectedDateObj < startDateObj) {
        showToast(`Leave not applicable for this date. Only applicable from ${formatDateForDisplay(startDate)}`, 'error');
        return;
    }

    if (selectedDateObj > endDateObj) {
        showToast(`Leave not applicable for this date. Only applicable until ${formatDateForDisplay(endDate)}`, 'error');
        return;
    }

    // Validate remaining days
    const remaining = parseInt(selectedOption.dataset.remaining);
    if (remaining <= 0) {
        showToast('Cannot assign leave, no remaining days available', 'error');
        return;
    }

    // Continue with form submission if all validations pass
    formData.set('leavedate_no', singleDate);
    formData.set('duration', document.getElementById('assignLeaveDuration').value);

    if (formData.has('available_dates')) {
        formData.delete('available_dates');
    }

    formData.set('leave_type_id', leaveTypeSelect.value);

    const reasonTextarea = document.getElementById('assignLeaveReason');
    formData.set('reason', reasonTextarea.value);

    const employeeId = document.getElementById('assignLeaveEmployee').value;
    formData.set('employee_id', employeeId);

    const fileInput = document.getElementById('assignLeaveFile');
    if (fileInput.files.length) {
        formData.append('file', fileInput.files[0]);
    }

    const saveBtn = this;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Saving...';

    fetch('/dashboard/employees/leave/store', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            stepModal.hide();
            fetchAttendances();
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        showToast('An error occurred while saving leave', 'error');
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = 'Save';
    });
});













// In your JavaScript, add this event listener
document.querySelectorAll('input[name="duration"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('assignLeaveDuration').value = this.value;
    });
});

// Set default value on initialization
document.getElementById('assignLeaveDuration').value = '1';

                // Initialize the attendance tracker
                initAttendanceTracker();

                function initAttendanceTracker() {
                    employees = employees.map(employee => {
                        // Make sure Departmentid exists and has dep_name
                        const departmentName = (employee.departmentid && employee.departmentid.dep_name) ?
                            employee.departmentid.dep_name :
                            'No Department';

                        return {
                            ...employee,
                            dep_name: departmentName, // Add this line to ensure dep_name is always available
                            image: employee.image ? '/employee_images/' + employee.image :
                                '/images/admin_default.jpg'
                        };
                    });

                    fetchHolidays();
                    fetchAttendances();
                    setupEventListeners();
                }

                function setupEventListeners() {
                    // Filter controls
                    employeeFilter.addEventListener('change', function () {
                        const selectedOption = this.options[this.selectedIndex];
                        let displayText = "All Employees";

                        if (this.value) {
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = selectedOption.innerHTML;
                            const nameElement = tempDiv.querySelector('.fw-bold');
                            displayText = nameElement ? nameElement.textContent : selectedOption.text;
                        }

                        document.getElementById('employeeFilterText').textContent = displayText;
                        currentPage = 1;
                        refreshAttendance();
                    });

                    departmentFilter.addEventListener('change', function () {
                        const selectedOption = this.options[this.selectedIndex];
                        let displayText = "All Departments";

                        if (this.value !== "all") {
                            displayText = selectedOption.textContent.trim();
                        }

                        document.getElementById('departmentFilterText').textContent = displayText;
                        currentPage = 1;
                        refreshAttendance();
                    });

                    // Entries per page
                    entriesPerPageSelect.addEventListener('change', function () {
                        entriesPerPage = parseInt(this.value);
                        currentPage = 1;
                        refreshAttendance();
                    });

                    // Pagination controls
                    prevPageBtn.addEventListener('click', goToPrevPage);
                    nextPageBtn.addEventListener('click', goToNextPage);

                    // Export button
                    exportBtn.addEventListener('click', exportAttendance);

                    // Edit button in view modal
                    // Replace the existing edit button click handler
                    document.getElementById('editAttendance').addEventListener('click', function (e) {
                        e.preventDefault();
                        viewAttendanceModal.hide();

                        editAttendanceModal.show();
                    });

                    // Save edit attendance
                    document.getElementById('saveEditAttendance').addEventListener('click', saveEditAttendance);

                    // Save add attendance


                    // Delete attendance
                    document.getElementById('deleteAttendance').addEventListener('click', confirmDeleteAttendance);

                    // Save bulk attendance
                    document.getElementById('saveBulkAttendance').addEventListener('click', saveBulkAttendance);

                    // Bulk modal select/deselect all functionality
                    document.getElementById('bulkSelectAllEmployees')?.addEventListener('click', function () {
                        const options = document.querySelectorAll('#bulkEmployeeFilter option');
                        options.forEach(option => {
                            option.selected = true;
                        });
                        updateBulkEmployeeFilterText();
                    });

                    document.getElementById('bulkDeselectAllEmployees')?.addEventListener('click', function () {
                        const options = document.querySelectorAll('#bulkEmployeeFilter option');
                        options.forEach(option => {
                            option.selected = false;
                        });
                        updateBulkEmployeeFilterText();
                    });

                    document.getElementById('bulkSelectAllDepartments')?.addEventListener('click', function () {
                        const options = document.querySelectorAll('#bulkDepartmentFilter option');
                        options.forEach(option => {
                            option.selected = true;
                        });
                        updateBulkDepartmentFilterText();
                    });

                    document.getElementById('bulkDeselectAllDepartments')?.addEventListener('click', function () {
                        const options = document.querySelectorAll('#bulkDepartmentFilter option');
                        options.forEach(option => {
                            option.selected = false;
                        });
                        updateBulkDepartmentFilterText();
                    });

                    // Update bulk employee filter text when selection changes
                    document.getElementById('bulkEmployeeFilter')?.addEventListener('change',
                        updateBulkEmployeeFilterText);

                    // Update bulk department filter text when selection changes
                    document.getElementById('bulkDepartmentFilter')?.addEventListener('change',
                        updateBulkDepartmentFilterText);

                    // Department filter in bulk modal - filter employees based on selected departments
                    document.getElementById('bulkDepartmentFilter')?.addEventListener('change', function () {
                        const selectedDepartments = Array.from(this.selectedOptions).map(opt => opt.value);
                        const employeeSelect = document.getElementById('bulkEmployeeFilter');

                        if (!employeeSelect) return;

                        Array.from(employeeSelect.options).forEach(option => {
                            option.selected = false;
                        });

                        if (selectedDepartments.length === 0 || selectedDepartments.includes('all')) {
                            Array.from(employeeSelect.options).forEach(option => {
                                option.style.display = '';
                            });
                        } else {
                            Array.from(employeeSelect.options).forEach(option => {
                                const employeeId = option.value;
                                if (!employeeId) return;

                                const employee = employees.find(e => e.emp_id == employeeId);
                                // Use Departmentid.dep_name for filtering
                                const employeeDept = employee.departmentid ? employee.departmentid
                                    .dep_name : 'No Department';

                                if (employee && selectedDepartments.includes(employeeDept)) {
                                    option.style.display = '';
                                } else {
                                    option.style.display = 'none';
                                }
                            });
                        }

                        updateBulkEmployeeFilterText();
                    });







                    // Search functionality for bulk employee dropdown
                    document.getElementById('bulkEmployeeSearch')?.addEventListener('input', function () {
                        const searchTerm = this.value.toLowerCase();
                        const options = document.querySelectorAll('#bulkEmployeeFilter option');

                        for (let i = 0; i < options.length; i++) {
                            const option = options[i];
                            if (option.style.display === 'none') continue; // Skip already hidden options

                            const text = option.text.toLowerCase();
                            option.style.display = text.includes(searchTerm) ? '' : 'none';
                        }
                    });

                    // Search functionality for bulk department dropdown
                    document.getElementById('bulkDepartmentSearch')?.addEventListener('input', function () {
                        const searchTerm = this.value.toLowerCase();
                        const options = document.querySelectorAll('#bulkDepartmentFilter option');

                        for (let i = 0; i < options.length; i++) {
                            const option = options[i];
                            const text = option.text.toLowerCase();
                            option.style.display = text.includes(searchTerm) ? '' : 'none';
                        }
                    });

                    // Search functionality for employee dropdown
                    document.getElementById('employeeSearch').addEventListener('input', function () {
                        const searchTerm = this.value.toLowerCase();
                        const options = employeeFilter.options;

                        for (let i = 0; i < options.length; i++) {
                            const option = options[i];
                            const text = option.text.toLowerCase();
                            option.style.display = text.includes(searchTerm) ? '' : 'none';
                        }
                    });

                    // Search functionality for department dropdown
                    document.getElementById('departmentSearch').addEventListener('input', function () {
                        const searchTerm = this.value.toLowerCase();
                        const options = departmentFilter.options;

                        for (let i = 0; i < options.length; i++) {
                            const option = options[i];
                            const text = option.text.toLowerCase();
                            option.style.display = text.includes(searchTerm) ? '' : 'none';
                        }
                    });

                    // Bulk attendance assignment type change
                    document.querySelectorAll('input[name="mark_attendance"]').forEach(radio => {
                        radio.addEventListener('change', function () {
                            document.getElementById('dateRangeFields').classList.add('d-none');
                            document.getElementById('monthField').classList.add('d-none');

                            if (this.value === '1') { // multiple
                                document.getElementById('dateRangeFields').classList.remove('d-none');
                            } else if (this.value === '2') { // month
                                document.getElementById('monthField').classList.remove('d-none');
                            }
                        });
                    });

                    // Department admin update
                    document.getElementById('bulkDepartmentFilter')?.addEventListener('change', function () {
                        const selectedOptions = Array.from(this.selectedOptions);
                        const adminInput = document.getElementById('departmentadmin');

                        if (selectedOptions.length > 0) {
                            // Find the first selected option that isn't "all"
                            const firstDeptOption = selectedOptions.find(opt => opt.value !== 'all');

                            if (firstDeptOption) {
                                adminInput.value = firstDeptOption.dataset.admin;
                            } else {
                                // Only "All Departments" is selected - clear or set a default message
                                adminInput.value = '';
                            }
                        } else {
                            // If nothing is selected, clear the field
                            adminInput.value = '';
                        }
                    });

                    // Open bulk attendance modal
                    document.getElementById('openBulkAttendanceModal').addEventListener('click', function () {
                        document.getElementById('bulkAttendanceForm').reset();
                        monthYearPicker.clear();
                        // Set default clock in time to shift start time
                        let shiftFromTime = '09:00'; // Default shift time
                        let shiftToTime = '18:00'; // Default shift time

                        const [fromHour, fromMin] = shiftFromTime.split(':').map(Number);
                        const fromAmPm = fromHour >= 12 ? 'PM' : 'AM';
                        const fromTwelveHour = fromHour % 12 || 12;
                        const fromTimeStr =
                            `${fromTwelveHour}:${fromMin.toString().padStart(2, '0')} ${fromAmPm}`;

                        // Set default clock in time to shift start time
                        document.getElementById('checkInTime')._flatpickr.setDate(fromTimeStr, true, 'h:i K');

                        // Set default clock out time to shift end time
                        const [toHour, toMin] = shiftToTime.split(':').map(Number);
                        const toAmPm = toHour >= 12 ? 'PM' : 'AM';
                        const toTwelveHour = toHour % 12 || 12;
                        const toTimeStr = `${toTwelveHour}:${toMin.toString().padStart(2, '0')} ${toAmPm}`;
                        document.getElementById('checkOutTime')._flatpickr.setDate(toTimeStr, true, 'h:i K');

                        // Show date range by default
                        document.getElementById('dateRangeFields').classList.remove('d-none');
                        document.getElementById('monthField').classList.add('d-none');

                        // Set radio button state
                        document.getElementById('multiple').checked = true;
                        document.getElementById('month').checked = false;

                        // Reset date range picker
                        const today = new Date();
                        dateRangePicker.selectedRange = {
                            start: new Date(today),
                            end: new Date(today)
                        };
                        dateRangePicker.updateRangeDisplay();
                        document.getElementById('selectedDateRange').value =
                            `${dateRangePicker.formatDate(today, 'dd-MM-yyyy')} To ${dateRangePicker.formatDate(today, 'dd-MM-yyyy')}`;
                        document.getElementById('date_range_from').value = dateRangePicker.formatDate(today,
                            'yyyy-MM-dd');
                        document.getElementById('date_range_to').value = dateRangePicker.formatDate(today,
                            'yyyy-MM-dd');

                        // Reset department and employee selections
                        document.querySelectorAll('#bulkDepartmentFilter option').forEach(option => {
                            option.selected = false;
                        });
                        document.querySelectorAll('#bulkEmployeeFilter option').forEach(option => {
                            option.selected = false;
                        });
                        updateBulkDepartmentFilterText();
                        updateBulkEmployeeFilterText();

                        bulkAttendanceModal.show();
                    });

                    // Show/hide half day options based on attendance type selection
                    document.getElementById('editAttendanceStatus').addEventListener('change', function () {
                        const halfDayOptions = document.getElementById('editHalfDayOptions');
                        if (this.value === '3') { // half_day
                            halfDayOptions.style.display = 'block';
                        } else {
                            halfDayOptions.style.display = 'none';
                        }

                        // Handle absent case
                        if (this.value === '0') { // absent
                            document.getElementById('editCheckInTime')._flatpickr.clear();
                            document.getElementById('editCheckOutTime')._flatpickr.clear();
                            document.getElementById('editCheckInTime').disabled = true;
                            document.getElementById('editCheckOutTime').disabled = true;
                        } else {
                            document.getElementById('editCheckInTime').disabled = false;
                            document.getElementById('editCheckOutTime').disabled = false;
                        }

                        // If status is changed to late or absent, update time fields accordingly
                        const employeeId = document.getElementById('editAttendanceEmployee').value;
                        const dateString = document.getElementById('editAttendanceDate').value;
                        updateTimeFieldsBasedOnShift(this.value, employeeId, dateString, 'edit');
                    });
                    document.getElementById('addAttendanceStatus').addEventListener('change', function () {
                        const halfDayOptions = document.getElementById('addHalfDayOptions');
                        if (this.value === '3') { // half_day
                            halfDayOptions.style.display = 'block';
                        } else {
                            halfDayOptions.style.display = 'none';
                        }

                        // Handle absent case
                        if (this.value === '0') { // absent
                            document.getElementById('addCheckInTime')._flatpickr.clear();
                            document.getElementById('addCheckOutTime')._flatpickr.clear();
                            document.getElementById('addCheckInTime').disabled = true;
                            document.getElementById('addCheckOutTime').disabled = true;
                        } else {
                            document.getElementById('addCheckInTime').disabled = false;
                            document.getElementById('addCheckOutTime').disabled = false;
                        }

                        // If status is changed to late or absent, update time fields accordingly
                        const employeeId = document.getElementById('addAttendanceEmployee').value;
                        const dateString = document.getElementById('addAttendanceDate').value;
                        updateTimeFieldsBasedOnShift(this.value, employeeId, dateString, 'add');
                    });


                    document.getElementById('bulkStatus').addEventListener('change', function () {
                        const halfDayOptions = document.getElementById('bulkHalfDayOptions');
                        const checkInTime = document.getElementById('checkInTime');
                        const checkOutTime = document.getElementById('checkOutTime');

                        // Get selected employees (if any) to determine their shifts
                        const selectedEmployees = Array.from(document.querySelectorAll(
                            '#bulkEmployeeFilter option:checked')).map(opt => opt.value);

                        // Default shift times (will be overridden if we have specific employee shifts)
                        let shiftFromTime = '09:00'; // Default general shift start
                        let shiftToTime = '18:00'; // Default general shift end

                        // Try to get shift times from first selected employee (if any)
                        if (selectedEmployees.length > 0) {
                            const firstEmployeeId = selectedEmployees[0];
                            // Get the date range or month from the form
                            const dateRangeFrom = document.getElementById('date_range_from')?.value;
                            const monthYear = document.getElementById('monthYearPicker')?.value;

                            if (dateRangeFrom) {
                                // For date range, use the first date to get shift
                                const shiftInfo = shifts[firstEmployeeId] && shifts[firstEmployeeId][
                                    dateRangeFrom
                                ];
                                if (shiftInfo) {
                                    shiftFromTime = shiftInfo.shift_from_time || '09:00';
                                    shiftToTime = shiftInfo.shift_to_time || '18:00';
                                }
                            } else if (monthYear) {
                                // For month selection, use the 1st of the month to get shift
                                const firstOfMonth = monthYear + '-01';
                                const shiftInfo = shifts[firstEmployeeId] && shifts[firstEmployeeId][
                                    firstOfMonth
                                ];
                                if (shiftInfo) {
                                    shiftFromTime = shiftInfo.shift_from_time || '09:00';
                                    shiftToTime = shiftInfo.shift_to_time || '18:00';
                                }
                            }
                        }

                        // Handle half day case
                        if (this.value === '3') { // half_day
                            halfDayOptions.style.display = 'block';
                            updateBulkHalfDayTimes('0'); // Default to first half
                        } else {
                            halfDayOptions.style.display = 'none';
                        }

                        // Handle absent case
                        if (this.value === '0') { // absent
                            checkInTime._flatpickr.clear();
                            checkOutTime._flatpickr.clear();
                            checkInTime.disabled = true;
                            checkOutTime.disabled = true;
                        } else {
                            checkInTime.disabled = false;
                            checkOutTime.disabled = false;

                            // Set default times based on shift if they're empty
                            if (!checkInTime.value) {
                                // Convert shift start time to 12-hour format
                                const [fromHour, fromMin] = shiftFromTime.split(':').map(Number);
                                const fromAmPm = fromHour >= 12 ? 'PM' : 'AM';
                                const fromTwelveHour = fromHour % 12 || 12;
                                const fromTimeStr =
                                    `${fromTwelveHour}:${fromMin.toString().padStart(2, '0')} ${fromAmPm}`;

                                checkInTime._flatpickr.setDate(fromTimeStr, true, 'h:i K');
                            }

                            if (!checkOutTime.value) {
                                // Convert shift end time to 12-hour format
                                const [toHour, toMin] = shiftToTime.split(':').map(Number);
                                const toAmPm = toHour >= 12 ? 'PM' : 'AM';
                                const toTwelveHour = toHour % 12 || 12;
                                const toTimeStr =
                                    `${toTwelveHour}:${toMin.toString().padStart(2, '0')} ${toAmPm}`;

                                checkOutTime._flatpickr.setDate(toTimeStr, true, 'h:i K');
                            }
                        }


                    });
                    // For add modal


                    // For edit modal
                    document.getElementById('editCheckInTime').addEventListener('change', function () {
                        checkForLateAttendance('edit');
                    });

                    // For bulk modal
                    document.querySelectorAll('input[name="half_day_type"]').forEach(radio => {
                        radio.addEventListener('change', function () {
                            if (document.getElementById('bulkStatus').value === '3') { // half_day
                                updateBulkHalfDayTimes(this.value);
                            }
                        });
                    });
                }

                function updateTimeFieldsBasedOnShift(status, employeeId, dateString, modalType) {
                    const prefix = modalType === 'add' ? 'add' : (modalType === 'edit' ? 'edit' : '');
                    const clockInField = document.getElementById(`${prefix}CheckInTime`)._flatpickr;
                    const clockOutField = document.getElementById(`${prefix}CheckOutTime`)._flatpickr;

                    // Get shift info for this employee and date
                    const shift = shifts[employeeId] && shifts[employeeId][dateString];
                    if (!shift) return;

                    const shiftFromTime = shift.shift_from_time || '09:00';
                    const shiftToTime = shift.shift_to_time || '18:00';

                    if (status === '0') { // Absent
                        clockInField.clear();
                        clockOutField.clear();
                        return;
                    }

                    if (status === '3') { // Half day
                        const halfDayType = document.querySelector(
                            `#${prefix}HalfDayOptions input[name="half_day_type"]:checked`)?.value || '0';
                        updateHalfDayTimes(halfDayType, shiftFromTime, shiftToTime, modalType);
                        return;
                    }

                    // For present/late status, set to shift times
                    const [fromHour, fromMin] = shiftFromTime.split(':').map(Number);
                    const [toHour, toMin] = shiftToTime.split(':').map(Number);

                    // Convert to 12-hour format
                    const fromAmPm = fromHour >= 12 ? 'PM' : 'AM';
                    const fromTwelveHour = fromHour % 12 || 12;
                    const fromTimeStr = `${fromTwelveHour}:${fromMin.toString().padStart(2, '0')} ${fromAmPm}`;

                    const toAmPm = toHour >= 12 ? 'PM' : 'AM';
                    const toTwelveHour = toHour % 12 || 12;
                    const toTimeStr = `${toTwelveHour}:${toMin.toString().padStart(2, '0')} ${toAmPm}`;

                    clockInField.setDate(fromTimeStr, true, 'h:i K');
                    clockOutField.setDate(toTimeStr, true, 'h:i K');
                }

                function updateHalfDayTimes(halfDayType, shiftFromTime, shiftToTime, modalType = 'bulk') {
                    const prefix = modalType === 'add' ? 'add' : (modalType === 'edit' ? 'edit' : '');
                    const clockInField = document.getElementById(`${prefix}CheckInTime`)._flatpickr;
                    const clockOutField = document.getElementById(`${prefix}CheckOutTime`)._flatpickr;

                    const [fromHour, fromMin] = shiftFromTime.split(':').map(Number);
                    const [toHour, toMin] = shiftToTime.split(':').map(Number);

                    if (halfDayType === '0') { // first_half
                        // First half - shift start to mid point (12:30 PM for general shift)
                        let midHour, midMin;

                        if (fromHour <= 12 && toHour >= 12) {
                            // Day shift - split at noon
                            midHour = 12;
                            midMin = 30;
                        } else if (fromHour >= 12 && toHour <= 12) {
                            // Overnight shift - split at midnight
                            midHour = 0;
                            midMin = 0;
                        } else {
                            // Calculate mid point
                            midHour = Math.floor((fromHour + toHour) / 2);
                            midMin = Math.floor((fromMin + toMin) / 2);
                        }

                        // Set clock in to shift start
                        const startAmPm = fromHour >= 12 ? 'PM' : 'AM';
                        const startTwelveHour = fromHour % 12 || 12;
                        const startTimeStr =
                            `${startTwelveHour}:${fromMin.toString().padStart(2, '0')} ${startAmPm}`;
                        clockInField.setDate(startTimeStr, true, 'h:i K');

                        // Set clock out to mid point
                        const midAmPm = midHour >= 12 ? 'PM' : 'AM';
                        const midTwelveHour = midHour % 12 || 12;
                        const midTimeStr =
                            `${midTwelveHour}:${midMin.toString().padStart(2, '0')} ${midAmPm}`;
                        clockOutField.setDate(midTimeStr, true, 'h:i K');
                    } else { // second_half
                        // Second half - mid point to shift end
                        let midHour, midMin;

                        if (fromHour <= 12 && toHour >= 12) {
                            // Day shift - split at noon
                            midHour = 12;
                            midMin = 30;
                        } else if (fromHour >= 12 && toHour <= 12) {
                            // Overnight shift - split at midnight
                            midHour = 0;
                            midMin = 0;
                        } else {
                            // Calculate mid point
                            midHour = Math.floor((fromHour + toHour) / 2);
                            midMin = Math.floor((fromMin + toMin) / 2);
                        }

                        // Set clock in to mid point
                        const midAmPm = midHour >= 12 ? 'PM' : 'AM';
                        const midTwelveHour = midHour % 12 || 12;
                        const midTimeStr =
                            `${midTwelveHour}:${midMin.toString().padStart(2, '0')} ${midAmPm}`;
                        clockInField.setDate(midTimeStr, true, 'h:i K');

                        // Set clock out to shift end
                        const endAmPm = toHour >= 12 ? 'PM' : 'AM';
                        const endTwelveHour = toHour % 12 || 12;
                        const endTimeStr = `${endTwelveHour}:${toMin.toString().padStart(2, '0')} ${endAmPm}`;
                        clockOutField.setDate(endTimeStr, true, 'h:i K');
                    }
                }

                function checkForLateAttendance(modalType) {
                    const prefix = modalType === 'add' ? 'add' : 'edit';
                    const clockInTime = document.getElementById(`${prefix}CheckInTime`).value;
                    const employeeId = document.getElementById(`${prefix}AttendanceEmployee`).value;
                    const dateString = document.getElementById(`${prefix}AttendanceDate`).value;
                    const attendanceStatus = document.getElementById(`${prefix}AttendanceStatus`);
                    const halfDayOptions = document.getElementById(`${prefix}HalfDayOptions`);

                    if (!clockInTime || !employeeId || !dateString) return;

                    // Get shift info for this employee and date
                    const shift = shifts[employeeId] && shifts[employeeId][dateString];
                    if (!shift) return;

                    const shiftStartTime = shift.shift_from_time; // e.g. "09:00"
                    const clockIn24 = convertTo24Hour(clockInTime);

                    // Only check for late if clock-in time is after shift start time
                    if (compareTimes(clockIn24, shiftStartTime) > 0) {
                        // Don't auto-change status if it's already manually set to present (1) or half day (3)
                        if (attendanceStatus.value !== '3') {
                            attendanceStatus.value = '2'; // late
                            showToast('Employee clocked in after shift start time. Status set to Late', 'warning');
                        }
                    }

                    // Always show half day options if half_day is selected
                    if (attendanceStatus.value === '3') { // half_day
                        halfDayOptions.style.display = 'block';
                    } else {
                        halfDayOptions.style.display = 'none';
                    }
                }





                function updateBulkHalfDayTimes(halfDayType) {
                    // Get shift times (assuming these are set somewhere)
                    const shiftFromTime = '09:00'; // Default shift time
                    const shiftToTime = '18:00'; // Default shift time

                    const [fromHour, fromMin] = shiftFromTime.split(':').map(Number);
                    const [toHour, toMin] = shiftToTime.split(':').map(Number);

                    if (halfDayType === '0') { // first_half
                        // First half - shift start to mid point (12:30 PM)
                        const midHour = 12;
                        const midMin = 30;

                        const startAmPm = fromHour >= 12 ? 'PM' : 'AM';
                        const startTwelveHour = fromHour % 12 || 12;
                        const startTimeStr =
                            `${startTwelveHour}:${fromMin.toString().padStart(2, '0')} ${startAmPm}`;

                        const midAmPm = midHour >= 12 ? 'PM' : 'AM';
                        const midTwelveHour = midHour % 12 || 12;
                        const midTimeStr =
                            `${midTwelveHour}:${midMin.toString().padStart(2, '0')} ${midAmPm}`;

                        document.getElementById('checkInTime')._flatpickr.setDate(startTimeStr, true, 'h:i K');
                        document.getElementById('checkOutTime')._flatpickr.setDate(midTimeStr, true, 'h:i K');
                    } else { // second_half
                        // Second half - mid point to shift end
                        const midHour = 12;
                        const midMin = 30;

                        const midAmPm = midHour >= 12 ? 'PM' : 'AM';
                        const midTwelveHour = midHour % 12 || 12;
                        const midTimeStr =
                            `${midTwelveHour}:${midMin.toString().padStart(2, '0')} ${midAmPm}`;

                        const endAmPm = toHour >= 12 ? 'PM' : 'AM';
                        const endTwelveHour = toHour % 12 || 12;
                        const endTimeStr = `${endTwelveHour}:${toMin.toString().padStart(2, '0')} ${endAmPm}`;

                        document.getElementById('checkInTime')._flatpickr.setDate(midTimeStr, true, 'h:i K');
                        document.getElementById('checkOutTime')._flatpickr.setDate(endTimeStr, true, 'h:i K');
                    }
                }

                function convertTo24Hour(time12h) {
                    const [time, modifier] = time12h.split(' ');
                    let [hours, minutes] = time.split(':');

                    if (hours === '12') hours = '00';
                    if (modifier === 'PM') hours = parseInt(hours, 10) + 12;

                    return `${hours}:${minutes}`;
                }

                function compareTimes(time1, time2) {
                    const [h1, m1] = time1.split(':').map(Number);
                    const [h2, m2] = time2.split(':').map(Number);

                    if (h1 !== h2) return h1 - h2;
                    return m1 - m2;
                }

                function goToPrevPage() {
                    if (currentPage > 1) {
                        currentPage--;
                        refreshAttendance();
                    }
                }

                function goToNextPage() {
                    const {
                        totalPages
                    } = filterEmployees();
                    if (currentPage < totalPages) {
                        currentPage++;
                        refreshAttendance();
                    }
                }

                function fetchAttendances() {
                    // Fetch attendances from server
                    fetch('attendances/get')
                        .then(response => response.json())
                        .then(data => {
                            attendances = data;
                            refreshAttendance();
                        })
                        .catch(error => {
                            console.error('Error fetching attendances:', error);
                            attendances = [];
                            refreshAttendance();
                        });
                }

           function fetchHolidays() {
    // Don't pass date range - get ALL holidays
    fetch(`/dashboard/employees/holiday/list`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            console.log('Received ALL holidays data:', data); // Debug log

            holidays = data.map(holiday => ({
                date: holiday.date,
                occasion: holiday.name || holiday.occasion,
                type: holiday.type_name || holiday.type,
                departments: holiday.holiday_department ?
                    holiday.holiday_department.split(',').map(s => s.trim()) : []
            }));

            console.log('Total holidays processed:', holidays.length); // Debug log
            console.log('Processed holidays:', holidays); // Debug log
            refreshAttendance();
        })
        .catch(error => {
            console.error('Error fetching holidays:', error);
            holidays = [];
            refreshAttendance();
        });
}
                function refreshAttendance() {
                    renderAttendanceView(currentDate);
                    updatePaginationControls();
                }

                function updatePaginationControls() {
                    const {
                        allEmployees,
                        totalPages
                    } = filterEmployees();
                    const pageInfo = document.getElementById('pageInfo');

                    prevPageBtn.disabled = currentPage <= 1;
                    nextPageBtn.disabled = currentPage >= totalPages || totalPages === 0;
                    pageInfo.textContent = `Page ${currentPage} of ${totalPages} (${allEmployees.length} employees)`;
                }

                function filterEmployees() {
                    let filtered = [...employees];

                    const department = departmentFilter.value;
                    if (department && department !== 'all') {
                        filtered = filtered.filter(employee => employee.dep_name === department);
                    }

                    const employeeId = employeeFilter.value;
                    if (employeeId) {
                        filtered = filtered.filter(employee => employee.emp_id == employeeId);
                    }

                    const startIndex = (currentPage - 1) * entriesPerPage;
                    const endIndex = startIndex + entriesPerPage;
                    const paginatedEmployees = filtered.slice(startIndex, endIndex);

                    return {
                        allEmployees: filtered,
                        paginatedEmployees: paginatedEmployees,
                        totalPages: Math.ceil(filtered.length / entriesPerPage)
                    };
                }

                function renderAttendanceView(date) {
                    const year = date.getFullYear();
                    const month = date.getMonth();
                    const daysInMonth = new Date(year, month + 1, 0).getDate();

                    // Render days header
                    let daysHeader = '';
                    for (let day = 1; day <= daysInMonth; day++) {
                        const currentDate = new Date(year, month, day);
                        const dayName = currentDate.toLocaleDateString('en-US', {
                            weekday: 'short'
                        });
                        const dateString = formatDate(currentDate);
                        const isWeekend = currentDate.getDay() === 0 || currentDate.getDay() === 6;

                        // Check if any employee has a shift on this date
                        let anyEmployeeHasShift = false;
                        for (const empId in shifts) {
                            if (shifts[empId][dateString]) {
                                anyEmployeeHasShift = true;
                                break;
                            }
                        }

                        // Only show holiday if there's at least one shift for this date
                        const isHoliday = anyEmployeeHasShift && holidays.some(holiday => holiday.date === dateString);

                        daysHeader += `
            <div class="day-header ${isWeekend ? 'weekend' : ''} ${isHoliday ? 'holiday' : ''}">
                <div class="day-name">${dayName}</div>
                <div class="day-number">${day}</div>
                ${isHoliday ? '<div class="holiday-indicator"></div>' : ''}
            </div>
        `;
                    }
                    document.getElementById('daysHeader').innerHTML = daysHeader;

                    // Rest of the function remains the same...
                    const {
                        paginatedEmployees: filteredEmployees
                    } = filterEmployees();
                    let attendanceBody = '';

                    filteredEmployees.forEach(employee => {
                        attendanceBody += `<div class="employee-row" data-employee-id="${employee.emp_id}">`;

                        // Employee info column
                        attendanceBody += `
            <div class="employee-info">
                <div class="d-flex">
                    <div class="me-2">
                        <img src="${employee.image}"
                             class="avatar-img rounded-circle"
                             style="width: 36px; height: 36px; object-fit: cover;">
                    </div>
                    <div>
                        <div class="fw-bold">${employee.fullname}</div>
                        <div class="small text-muted">${employee.dep_name}</div>
                    </div>
                </div>
            </div>
        `;

                        // Attendance days
                        let presentCount = 0;
                        let absentCount = 0;
                        let lateCount = 0;
                        let halfDayCount = 0;
                        let leaveCount = 0;
                        let holidayCount = 0;
                        let dayOffCount = 0;

                        for (let day = 1; day <= daysInMonth; day++) {
                            const currentDate = new Date(year, month, day);
                            const dateString = formatDate(currentDate);


                            const isWeekend = currentDate.getDay() === 0 || currentDate.getDay() === 6;

                            const dayAttendance = attendances.find(att =>
                                att.employee_id == employee.emp_id && att.attendancedate_no === dateString
                            );

                            // Check if shift is assigned for this date
                            const hasShift = shifts[employee.emp_id] && shifts[employee.emp_id][dateString];
                            const isDayOff = hasShift && shifts[employee.emp_id][dateString].shift_type === 3;

                            let status = '';
                            let statusClass = '';
                            let statusAbbr = '';
                            let title = '';
                            const holidayList = shifts[employee.emp_id] && shifts[employee.emp_id][dateString] ?
                                isEmployeeHoliday(employee, dateString) : [];

                            const isHoliday = holidayList.length > 0;

                            const showHoliday = isHoliday && (!dayAttendance || dayAttendance
                                .attendance_type === 4);
                            // Check if this is an approved leave for this employee
                            const leave = approvedLeaves[employee.emp_id] && approvedLeaves[employee.emp_id][
                                dateString
                            ];
                            console.log(leave);

                            if (leave) {
                                // Get leave type name
                                const leaveType = leave.leavetype ?
                                    getLeavetypeNameText(leave.leavetype.leavetype_name_id) :
                                    'Absent';



                                // Create tooltip content

                                if (leave.select_duration == 3 || leave.select_duration == 4) {
                                    title =
                                        `${leaveType}\nReason: ${leave.reason_forleave || 'No reason provided'}\nDuration : ${leave.select_duration == 3 ? 'First Half' : 'Second Half'}`;

                                    if (leave.select_duration == 3) {
                                        statusAbbr =
                                            '<i class="fas fa-star-half-alt text-danger" ></i>';
                                    } else {
                                        statusAbbr =
                                            '<i class="fas fa-star-half-alt  text-danger" style="transform: scaleX(-1);" ></i>';
                                    }




                                } else {
                                    title =
                                        `${leaveType}\nReason: ${leave.reason_forleave || 'No reason provided'}\nDuration : ${leave.select_duration == 1 ? 'Full Day' : 'Full Day'}`;
                                    statusAbbr = '<i class="fas fa-plane-departure text-danger"></i>';
                                }

                                statusClass = 'leave';
                            } else if (showHoliday) {
                                status = 'holiday';
                                statusClass = 'holiday';
                                const holidayOccasions = holidayList.map(h => h.occasion || h.name).join(', ');
                                const holidayTypes = holidayList.map(h => h.type || h.type_name).join(', ');

                                statusAbbr = `<i class="fas fa-star text-warning"></i>`;
                                title = `Holiday: ${holidayOccasions}\nType: ${holidayTypes}`;

                                holidayCount++;
                            } else if (isDayOff) {
                                status = 'day_off';
                                statusClass = 'day-off';
                                statusAbbr = '<i class="fas fa-calendar-week text-red" title="Day Off"></i>';
                                title = 'Day Off';
                                dayOffCount++;
                            } else if (dayAttendance) {
                                status = dayAttendance.attendance_type;
                                statusClass = dayAttendance.attendance_type;
                                title = getAttendanceTitle(dayAttendance.attendance_type);

                                switch (dayAttendance.attendance_type.toString()) {
                                    case '1': // present
                                        statusAbbr =
                                            '<i class="fas fa-check text-success" title="Present"></i>';
                                        presentCount++;
                                        break;
                                    case '0': // absent
                                        statusAbbr = '<i class="fas fa-times text-danger" title="Absent"></i>';
                                        absentCount++;
                                        break;
                                    case '2': // late
                                        statusAbbr =
                                            '<i class="fas fa-exclamation-circle text-warning" title="Late"></i>';
                                        lateCount++;
                                        break;
                                    case '3': // half_day

                                        if (dayAttendance.half_day_type == 0) {
                                            statusAbbr =
                                                '<i class="fas fa-star-half-alt text-info" title="Half Day"></i>';
                                        } else {
                                            statusAbbr =
                                                '<i class="fas fa-star-half-alt  text-info-emphasis" style="transform: scaleX(-1);" title="Half Day"></i>';
                                        }
                                        halfDayCount++;
                                        break;
                                    case '4': // holiday - should only show if explicitly marked as holiday
                                        statusAbbr = '<i class="fas fa-star text-warning" title="Holiday"></i>';
                                        holidayCount++;
                                        break;

                                }
                            } else {
                                // Show plus icon if shift is assigned, minus if not
                                if (hasShift) {
                                    statusAbbr =
                                        '<i class="bi bi-plus-circle pluseadd" title="Mark Attendance"></i>';
                                } else {
                                    statusAbbr =
                                        '<i class="bi bi-dash-circle minusadd" title="No Shift Assigned" style="cursor: not-allowed;"></i>';
                                }
                                title = hasShift ? 'Mark Attendance' : 'No Shift Assigned';
                            }


                            attendanceBody += `
                <div class="day-cell ${statusClass}"
                     data-date="${dateString}"
                     data-has-shift="${hasShift ? 'true' : 'false'}"
                     title="${title}"
                    onclick="handleAttendanceCellClick(this, '${employee.emp_id}', '${dateString}', ${dayAttendance ? true : false}, event)">
                    ${statusAbbr}
                </div>
            `;
                        }

                        // Total column
                        attendanceBody += `
            <div class="total-cell text-nowrap">
                <span class="fw-medium" style="font-size:13px;">${presentCount + halfDayCount + lateCount} / ${new Date(year, month + 1, 0).getDate()}</span>
            </div>
        `;

                        attendanceBody += `</div>`;
                    });

                    document.getElementById('attendanceBody').innerHTML = attendanceBody;
                }

                function getLeavetypeNameText(id) {
                    const types = {
                        1: 'Privilege Leave (PL)',
                        2: 'Casual Leave (CL)',
                        3: 'Sick Leave (SL)',
                        4: 'Maternity Leave (ML)',
                        5: 'Compensatory Off (Comp-off)',
                        6: 'Marriage Leave',
                        7: 'Paternity Leave',
                        8: 'Bereavement Leave',
                        9: 'UnPaid Leave (UL)'
                    };
                    return types[id] || 'Absent';
                }

                function isEmployeeHoliday(employee, dateString) {
                    // Find all holidays on this date
                    const matchedHolidays = holidays.filter(h => h.date === dateString);

                    if (matchedHolidays.length === 0) return [];

                    const relevantHolidays = matchedHolidays.filter(holiday => {
                        // Get employee's department, designation and job type
                        const empDep = employee.cur_department?.toString() || '';
                        const empDesig = employee.cur_designation?.toString() || '';
                        const empType = employee.jobtype?.toString() || '';

                        // Check if holiday has any department, designation or job type assignments
                        const hasDepartmentAssignments = holiday.departments && holiday.departments.length > 0;

                        // If no specific assignments, skip this holiday
                        if (!hasDepartmentAssignments) {
                            return false;
                        }

                        // Check if employee matches any of the assignments
                        const deptMatch = hasDepartmentAssignments && holiday.departments.includes(empDep);

                        return deptMatch;
                    });

                    return relevantHolidays;
                }

                function getAttendanceTitle(attendanceType) {
                    switch (attendanceType.toString()) {
                        case '0':
                            return 'Absent';
                        case '1':
                            return 'Present';
                        case '2':
                            return 'Late';
                        case '3':
                            return 'Half Day';
                        default:
                            return 'Unknown';
                    }
                }

                window.handleAttendanceCellClick = function (cell, employeeId, dateString, hasAttendance) {
                    const hasShift = cell.dataset.hasShift === 'true';
                    const isDayOff = hasShift && shifts[employeeId] && shifts[employeeId][dateString] && shifts[
                        employeeId][dateString].shift_type === 3;

                    // Check if this is a leave day by checking the statusClass
                    if (cell.classList.contains('leave')) {
                        event.stopPropagation();
                        return;
                    }

                    if (!hasShift || isDayOff) {
                        showToast(isDayOff ? "Day off Modifications are not permitted." :
                            'No shift assigned for this date', 'error');
                        return;
                    }

                    const employee = employees.find(e => e.emp_id == employeeId);

                    if (hasAttendance) {
                        // For existing attendance, show the view modal (existing functionality)
                        const attendance = attendances.find(att => att.employee_id == employeeId && att
                            .attendancedate_no === dateString);
                        showViewAttendanceModal(employee, attendance);
                    } else {
                        // For new attendance, show the step modal
                        openStepModal(employee, dateString);
                    }
                };

                function showViewAttendanceModal(employee, attendance) {
                    // Set employee info
                    document.getElementById('viewEmployeeName').textContent = employee.fullname;
                    document.getElementById('viewEmployeePosition').textContent = employee.dep_name ||
                        'No Department';

                    // Format and set date
                    const date = new Date(attendance.attendancedate_no);
                    const options = {
                        weekday: 'long',
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    };
                    const formattedDate = date.toLocaleDateString('en-US', options).replace(/\//g, '-');
                    document.getElementById('viewAttendanceDate').textContent = formatDateToDayMonthYear(formattedDate);

                    // Set clock in/out times
                    const clockInTime = attendance.clock_in ? formatTime(attendance.clock_in) : 'Not recorded';
                    const clockOutTime = attendance.clock_out ? formatTime(attendance.clock_out) : 'Not recorded';

                    // Handle different attendance types
                    if (attendance.attendance_type === 0) { // absent
                        // Special handling for absent status
                        document.getElementById('conclock').style.display = 'none';

                        // Build activity log for absent status
                        const activityList = document.getElementById('activityList');
                        activityList.innerHTML = `
        <li class="d-flex align-items-start">
            <span class="me-2"></span>
            <div class="row">
                <div class="col-auto">
                    <span class="fw-medium">${formatDateToDayMonthYear(formattedDate)}</span><br>

                </div>
                <div class="col-auto">
                    <strong class="fw-medium mt-2 mb-2">Status</strong><br>
                    <span style="font-size:12px;"><i class="fas fa-times text-danger me-1" style="font-size:12px;"></i>Absent</span>
                </div>
            </div>
        </li>
        `;
                    } else {
                        document.getElementById('conclock').style.display = '';

                        // For non-absent statuses
                        document.getElementById('viewClockIn').textContent = clockInTime;
                        document.getElementById('viewClockOut').textContent = clockOutTime;

                        // Calculate and display worked time if both clock in/out exist
                        if (attendance.clock_in && attendance.clock_out) {
                            const workedTime = calculateWorkedTime(attendance.clock_in, attendance.clock_out);
                            document.getElementById('workedHours').textContent = workedTime.formatted;
                        } else {
                            document.getElementById('workedHours').textContent = '0h 0m';
                        }

                        // Set working location
                        const location = attendance.attendance_workfrom === '1' ? 'Office' : 'Home';

                        // Get appropriate icon based on attendance type
                        const icon = getAttendanceIcon(attendance.attendance_type);
                        const statusText = getAttendanceTitle(attendance.attendance_type);

                        // Add half-day type if applicable with color coding
                        let statusDetails = statusText;
                        let statusColorClass = '';
                        if (attendance.attendance_type === 3) { // half_day
                            if (attendance.half_day_type === 1) { // second half
                                statusDetails =
                                    `${statusText} <span class="badge bg-dark text-white p-1">Second Half</span>`;
                            } else { // first half
                                statusDetails =
                                    `${statusText} <span class="badge bg-info  p-1 text-dark">First Half</span>`;
                            }
                        }

                        // Build activity log
                        const activityList = document.getElementById('activityList');
                        activityList.innerHTML = '';
                        // <span class="fw-medium">${formatDateToDayMonthYear(formattedDate)} ${clockInTime}</span>
                        if (attendance.clock_in) {
                            activityList.innerHTML += `
            <li class="d-flex align-items-start">
                <span class="me-2"></span>
                <div class="row">
                    <div class="col-md-8">
                        <strong class="fw-medium">Clock In</strong><br>
                        <i class="bi bi-alarm-fill text-muted me-1" style="font-size:12px;"></i>
                        <span class="fw-medium">${date.toLocaleDateString('en-US', { weekday: 'long' })} ${clockInTime}</span>




                    </div>
                    <div class="col-md-4">
                        <strong class="fw-medium mt-2">Status</strong><br>
                        ${icon}
                        <span style="font-size:12px;">${statusDetails}</span>
                    </div>
                </div>
            </li>
            `;
                        }

                        if (attendance.clock_out) {
                            activityList.innerHTML += `
            <li class="d-flex align-items-start">
                <span class="me-2"></span>
                <div>
                    <strong class="fw-medium">Clock Out</strong><br>
                    <i class="bi bi-alarm-fill text-muted me-1" style="font-size:12px;"></i>
                    <span class="fw-medium">${date.toLocaleDateString('en-US', { weekday: 'long' })} ${clockOutTime}</span><br>

                </div>
            </li>
            `;
                        }
                    }

                    // Set delete button attribute
                    document.getElementById('deleteAttendance').dataset.attendanceId = attendance.attendance_id;

                    // Set up edit button click handler
                    document.getElementById('editAttendance').onclick = function (e) {
                        e.preventDefault();
                        viewAttendanceModal.hide();
                        showEditAttendanceModal(employee, attendance);
                    };

                    // Show modal
                    viewAttendanceModal.show();
                }

                function getAttendanceIcon(type) {
                    switch (type.toString()) {
                        case '1': // present
                            return '<i class="fas fa-check text-success me-1" style="font-size:11px;" title="Present"></i>';
                        case '2': // late
                            return '<i class="fas fa-exclamation-circle text-warning me-1" style="font-size:11px;" title="Late"></i>';
                        case '3': // half_day
                            return '<i class="fas fa-star-half-alt text-info me-1"style="font-size:11px;" title="Half Day"></i>';
                        case '0': // absent
                            return '<i class="fas fa-times text-danger me-1"style="font-size:11px;" title="Absent"></i>';
                        case 'leave':
                            return '<i class="fas fa-plane-departure text-secondary me-1"style="font-size:11px;" title="On Leave"></i>';
                        case 'holiday':
                            return '<i class="fas fa-star text-warning me-1" style="font-size:11px;"title="Holiday"></i>';
                        case 'day_off':
                            return '<i class="fas fa-calendar-week text-dark me-1"style="font-size:11px;" title="Day Off"></i>';
                        default:
                            return '<i class="fas fa-question-circle text-muted me-1"style="font-size:11px;" title="Unknown"></i>';
                    }
                }

                function formatTime(timeString) {
                    if (!timeString) return '';
                    const [hours, minutes] = timeString.split(':');
                    const h = parseInt(hours);
                    const ampm = h >= 12 ? 'PM' : 'AM';
                    const twelveHour = h % 12 || 12;
                    return `${twelveHour}:${minutes.padStart(2, '0')} ${ampm}`;
                }

                function calculateWorkedTime(clockIn, clockOut) {
                    const [inHours, inMinutes] = clockIn.split(':').map(Number);
                    const [outHours, outMinutes] = clockOut.split(':').map(Number);

                    // Convert to total minutes
                    const inTotal = inHours * 60 + inMinutes;
                    const outTotal = outHours * 60 + outMinutes;

                    // Calculate difference
                    let diffMinutes = outTotal - inTotal;
                    if (diffMinutes < 0) {
                        diffMinutes += 24 * 60; // Handle overnight
                    }

                    // Convert to hours and minutes
                    const hours = Math.floor(diffMinutes / 60);
                    const minutes = diffMinutes % 60;

                    return {
                        hours: hours,
                        minutes: minutes,
                        formatted: `${hours}h ${minutes}m`
                    };
                }

                function showEditAttendanceModal(employee, attendance) {
                    // Set employee info
                    document.getElementById('editEmployeeName').textContent = employee.fullname;
                    document.getElementById('editEmployeePosition').textContent = employee.dep_name ||
                        'No Position';

                    // Format and set date
                    const date = new Date(attendance.attendancedate_no);
                    const formattedDate = formatDisplayDate(date);
                    document.getElementById('editAttendanceDateDisplay').textContent = `Date: ${formattedDate}`;

                    // Set hidden fields
                    document.getElementById('editAttendanceId').value = attendance.attendance_id;
                    document.getElementById('editAttendanceEmployee').value = employee.emp_id;
                    document.getElementById('editAttendanceDate').value = attendance.attendancedate_no;



                    const wasHoliday = holidays.some(holiday =>
                        holiday.date === attendance.attendancedate_no &&
                        isEmployeeHoliday(employee, attendance.attendancedate_no)
                    );

                    // Show holiday warning if this was originally a holiday AND we're changing from holiday status
                    const holidayWarning = document.getElementById('holidayWarning');
                    if (wasHoliday && attendance.attendance_type == 4) {
                        holidayWarning.style.display = 'block';
                        holidayWarning.innerHTML = `
            <div class="alert alert-warning d-flex align-items-center mb-3"style="font-size:12px;" role="alert">
                <div>
                    <strong> <i class="fas fa-exclamation-triangle me-2"></i> Overwriting Holiday!</strong>
                    This date was originally marked as a holiday.
                </div>
            </div>
        `;
                    } else {
                        holidayWarning.style.display = 'none';
                    }


                    // Set shift badge
                    let shiftName = 'Not assigned';
                    let badgeClass = 'bg-secondary';
                    let shiftFromTime = '09:00'; // Default shift time
                    let shiftToTime = '18:00'; // Default shift time

                    if (shifts[employee.emp_id] && shifts[employee.emp_id][attendance.attendancedate_no]) {
                        const shift = shifts[employee.emp_id][attendance.attendancedate_no];
                        const shiftType = shift.shift_type;
                        shiftFromTime = shift.shift_from_time || '09:00';
                        shiftToTime = shift.shift_to_time || '18:00';

                        switch (shiftType) {
                            case 0:
                                shiftName = 'General Shift(09:00AM-18:00PM)';
                                badgeClass = 'bg-primary';
                                break;
                            case 2:
                                shiftName = 'Night Shift(10:00PM-06:00AM)';
                                badgeClass = 'bg-dark';
                                break;
                            case 1:
                                shiftName = 'Morning Shift(07:00AM-17:00PM)';
                                badgeClass = 'bg-warning text-secondary';
                                break;
                            case 3:
                                shiftName = 'Day Off';
                                badgeClass = 'bg-info';
                                break;
                            default:
                                shiftName = shiftType;
                                badgeClass = 'bg-secondary';
                        }
                    }

                    const shiftBadge = document.getElementById('editShiftBadge');
                    shiftBadge.textContent = shiftName;
                    shiftBadge.className = `badge p-2 shift-badge ${badgeClass}`;

                    // Set form fields
                    const editCheckInTime = document.getElementById('editCheckInTime')._flatpickr;
                    const editCheckOutTime = document.getElementById('editCheckOutTime')._flatpickr;

                    if (attendance.clock_in) {
                        // Convert 24-hour time to 12-hour format for display
                        const [hours, minutes] = attendance.clock_in.split(':');
                        const h = parseInt(hours);
                        const ampm = h >= 12 ? 'PM' : 'AM';
                        const twelveHour = h % 12 || 12;
                        const displayTime = `${twelveHour}:${minutes} ${ampm}`;
                        editCheckInTime.setDate(displayTime, true, 'h:i K');
                    } else {
                        // Set to shift start time if no clock-in exists
                        const [hours, minutes] = shiftFromTime.split(':').map(Number);
                        const h = hours;
                        const ampm = h >= 12 ? 'PM' : 'AM';
                        const twelveHour = h % 12 || 12;
                        const displayTime = `${twelveHour}:${minutes.toString().padStart(2, '0')} ${ampm}`;
                        editCheckInTime.setDate(displayTime, true, 'h:i K');
                    }

                    if (attendance.clock_out) {
                        // Convert 24-hour time to 12-hour format for display
                        const [hours, minutes] = attendance.clock_out.split(':');
                        const h = parseInt(hours);
                        const ampm = h >= 12 ? 'PM' : 'AM';
                        const twelveHour = h % 12 || 12;
                        const displayTime = `${twelveHour}:${minutes} ${ampm}`;
                        editCheckOutTime.setDate(displayTime, true, 'h:i K');
                    } else {
                        // Set to shift end time if no clock-out exists
                        const [hours, minutes] = shiftToTime.split(':').map(Number);
                        const h = hours;
                        const ampm = h >= 12 ? 'PM' : 'AM';
                        const twelveHour = h % 12 || 12;
                        const displayTime = `${twelveHour}:${minutes.toString().padStart(2, '0')} ${ampm}`;
                        editCheckOutTime.setDate(displayTime, true, 'h:i K');
                    }

                    document.getElementById('editClockInIp').value = attendance.clock_in_ip || 'Not available';
                    document.getElementById('editClockOutIp').value = attendance.clock_out_ip || 'Not available';
                    document.getElementById('editWorkingFrom').value = attendance.attendance_workfrom === '1' ?
                        'office' : 'home';
                    document.getElementById('editAttendanceStatus').value = attendance.attendance_type;

                    // Set half day type if applicable
                    if (attendance.attendance_type === 3) { // half_day
                        document.getElementById('editHalfDayOptions').style.display = 'block';
                        if (attendance.half_day_type === 1) { // second_half
                            document.getElementById('editSecondHalf').checked = true;
                        } else {
                            document.getElementById('editFirstHalf').checked = true;
                        }
                    } else {
                        document.getElementById('editHalfDayOptions').style.display = 'none';
                    }

                    // Enable/disable fields based on attendance type
                    if (attendance.attendance_type === '0') { // absent
                        document.getElementById('editCheckInTime').disabled = true;
                        document.getElementById('editCheckOutTime').disabled = true;
                    } else {
                        document.getElementById('editCheckInTime').disabled = false;
                        document.getElementById('editCheckOutTime').disabled = false;
                    }

                    // Add event listener for attendance status change
                    document.getElementById('editAttendanceStatus').addEventListener('change', function () {
                        const attendanceType = this.value;
                        const editCheckInTime = document.getElementById('editCheckInTime')._flatpickr;
                        const editCheckOutTime = document.getElementById('editCheckOutTime')._flatpickr;

                        // Get shift info
                        const shift = shifts[employee.emp_id] && shifts[employee.emp_id][attendance
                            .attendancedate_no
                        ];
                        const shiftFromTime = shift ? shift.shift_from_time : '09:00';
                        const shiftToTime = shift ? shift.shift_to_time : '18:00';

                        if (attendanceType === '0') { // absent
                            // Clear times for absent status
                            editCheckInTime.clear();
                            editCheckOutTime.clear();
                            document.getElementById('editCheckInTime').disabled = true;
                            document.getElementById('editCheckOutTime').disabled = true;
                            document.getElementById('editHalfDayOptions').style.display = 'none';
                        } else {
                            document.getElementById('editCheckInTime').disabled = false;
                            document.getElementById('editCheckOutTime').disabled = false;

                            if (attendanceType === '1' || attendanceType === '2') { // present or late
                                document.getElementById('editHalfDayOptions').style.display = 'none';

                                // For present/late, use original times if available, otherwise set defaults
                                if (attendance.clock_in) {
                                    const [hours, minutes] = attendance.clock_in.split(':');
                                    const h = parseInt(hours);
                                    const ampm = h >= 12 ? 'PM' : 'AM';
                                    const twelveHour = h % 12 || 12;
                                    const displayTime = `${twelveHour}:${minutes} ${ampm}`;
                                    editCheckInTime.setDate(displayTime, true, 'h:i K');
                                } else {
                                    // Set to shift start time if no clock-in exists
                                    const [hours, minutes] = shiftFromTime.split(':').map(Number);
                                    const h = hours;
                                    const ampm = h >= 12 ? 'PM' : 'AM';
                                    const twelveHour = h % 12 || 12;
                                    const displayTime =
                                        `${twelveHour}:${minutes.toString().padStart(2, '0')} ${ampm}`;
                                    editCheckInTime.setDate(displayTime, true, 'h:i K');
                                }

                                if (attendance.clock_out) {
                                    const [hours, minutes] = attendance.clock_out.split(':');
                                    const h = parseInt(hours);
                                    const ampm = h >= 12 ? 'PM' : 'AM';
                                    const twelveHour = h % 12 || 12;
                                    const displayTime = `${twelveHour}:${minutes} ${ampm}`;
                                    editCheckOutTime.setDate(displayTime, true, 'h:i K');
                                } else {
                                    // Set to shift end time if no clock-out exists
                                    const [hours, minutes] = shiftToTime.split(':').map(Number);
                                    const h = hours;
                                    const ampm = h >= 12 ? 'PM' : 'AM';
                                    const twelveHour = h % 12 || 12;
                                    const displayTime =
                                        `${twelveHour}:${minutes.toString().padStart(2, '0')} ${ampm}`;
                                    editCheckOutTime.setDate(displayTime, true, 'h:i K');
                                }
                            } else if (attendanceType === '3') { // half_day
                                document.getElementById('editHalfDayOptions').style.display = 'block';
                                // Handle half day times
                                const halfDayType = document.querySelector(
                                    'input[name="half_day_type"]:checked')?.value || '0';
                                updateHalfDayTimes(halfDayType, shiftFromTime, shiftToTime, 'edit');
                            }
                        }
                    });

                    // Add event listener for half day type radio buttons
                    document.querySelectorAll('input[name="half_day_type"]').forEach(radio => {
                        radio.addEventListener('change', function () {
                            if (document.getElementById('editAttendanceStatus').value ===
                                '3') { // half_day
                                const shift = shifts[employee.emp_id] && shifts[employee.emp_id][
                                    attendance.attendancedate_no
                                ];
                                const shiftFromTime = shift ? shift.shift_from_time : '09:00';
                                const shiftToTime = shift ? shift.shift_to_time : '18:00';
                                updateHalfDayTimes(this.value, shiftFromTime, shiftToTime, 'edit');
                            }
                        });
                    });

                    // Show edit modal
                    editAttendanceModal.show();
                }


                // Format date for display
                function formatDisplayDate(date) {
                    const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                    const dayName = days[date.getDay()];

                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();

                    // Change from month-day-year to day-month-year
                    return `${day}-${month}-${year} (${dayName})`;
                }

                function formatDateToDayMonthYear(dateString) {
                    // Split the date string into parts (assuming format like "Monday, 07-14-2025")
                    const [weekday, datePart] = dateString.split(',');

                    if (!datePart) return dateString; // Return original if format doesn't match

                    // Split the date part into month, day, year
                    const [month, day, year] = datePart.trim().split('-');

                    // Reformat to day-month-year
                    return `${weekday}, ${day}-${month}-${year}`;
                }

                function saveEditAttendance() {
                    const clockIn = document.getElementById('editCheckInTime').value;
                    const clockOut = document.getElementById('editCheckOutTime').value;
                    const attendanceType = document.getElementById('editAttendanceStatus').value;
                    const attendanceId = document.getElementById('editAttendanceId').value;
                    const dateString = document.getElementById('editAttendanceDate').value;

                    // For absent status, clear any clock in/out times
                    if (attendanceType === '0') { // absent
                        document.getElementById('editCheckInTime')._flatpickr.clear();
                        document.getElementById('editCheckOutTime')._flatpickr.clear();
                    }

                    // Validate for absent status
                    if (attendanceType === '0' && (clockIn || clockOut)) {
                        showToast('Clock in/out times must be empty for absent status', 'error');
                        return;
                    }

                    // Validate for other statuses
                    if (attendanceType !== '0' && !clockIn) {
                        showToast('Clock In time is required', 'error');
                        return;
                    }

                    const formData = new FormData(document.getElementById('editAttendanceForm'));
                    formData.append('_method', 'PUT');

                    // Add the original date to check for holiday
                    formData.append('original_date', dateString);

                    // Convert string values to integers for database
                    formData.set('attendance_type', attendanceType);
                    formData.set('attendance_workfrom', document.getElementById('editWorkingFrom').value === 'office' ?
                        '1' : '0');

                    if (attendanceType === '3') { // half_day
                        formData.set('half_day_type', document.querySelector('input[name="half_day_type"]:checked')
                            .value === '1' ? '1' : '0');
                    }

                    // Show loading state
                    const saveBtn = document.getElementById('saveEditAttendance');
                    saveBtn.disabled = true;
                    saveBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';

                    fetch('attendances/update', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                fetchAttendances();
                                editAttendanceModal.hide();
                                showToast(data.message, 'success');

                                // If the status was changed from holiday, refresh holidays
                                if (data.holiday_removed) {
                                    fetchHolidays();
                                }
                            } else {
                                showToast(data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('An error occurred while saving the attendance', 'error');
                        })
                        .finally(() => {
                            saveBtn.disabled = false;
                            saveBtn.innerHTML = 'Save';
                        });
                }


                function confirmDeleteAttendance() {
                    const attendanceId = this.dataset.attendanceId;

                    Swal.fire({
                        title: 'Delete Attendance?',
                        text: "Are you sure you want to delete this attendance record?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteAttendance(attendanceId);
                        }
                    });
                }

                function deleteAttendance(attendanceId) {
                    fetch('attendances/delete/' + attendanceId, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Accept': 'application/json',
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                fetchAttendances();
                                viewAttendanceModal.hide();
                                showToast(data.message, 'success');
                            } else {
                                showToast(data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('An error occurred while deleting the attendance', 'error');
                        });
                }

                function updateBulkEmployeeFilterText() {
                    const selectedOptions = Array.from(document.querySelectorAll('#bulkEmployeeFilter option:checked'));
                    const filterText = document.getElementById('bulkEmployeeFilterText');

                    if (selectedOptions.length === 0) {
                        filterText.textContent = 'All Employees';
                    } else if (selectedOptions.length === 1) {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = selectedOptions[0].innerHTML;
                        const nameElement = tempDiv.querySelector('p');
                        filterText.textContent = nameElement ? nameElement.textContent : selectedOptions[0].text;
                    } else {
                        filterText.textContent = `${selectedOptions.length} employees selected`;
                    }
                }

                function updateBulkDepartmentFilterText() {
                    const selectedOptions = Array.from(document.querySelectorAll(
                        '#bulkDepartmentFilter option:checked'));
                    const filterText = document.getElementById('bulkDepartmentFilterText');

                    if (selectedOptions.length === 0 || (selectedOptions.length === 1 && selectedOptions[0].value ===
                        'all')) {
                        filterText.textContent = 'All Departments';
                    } else if (selectedOptions.length === 1) {
                        filterText.textContent = selectedOptions[0].textContent;
                    } else {
                        filterText.textContent = `${selectedOptions.length} departments selected`;
                    }
                }

                function formatDate(date) {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                }


                function exportAttendance() {
                    // Get current filters
                    const department = departmentFilter.value;
                    const employeeId = employeeFilter.value;
                    const attendanceType = 'all';

                    // Get date range from your date picker if available
                    const startDate = ''; // Get from your date range picker
                    const endDate = ''; // Get from your date range picker

                    // Build query parameters
                    let queryParams = '';

                    if (startDate && endDate) {
                        queryParams += `start_date=${startDate}&end_date=${endDate}`;
                    } else {
                        // Default to current month if no date range selected
                        const year = currentDate.getFullYear();
                        const month = currentDate.getMonth() + 1;
                        queryParams += `year=${year}&month=${month}`;
                    }

                    if (department && department !== 'all') {
                        queryParams += `&department=${encodeURIComponent(department)}`;
                    }

                    if (employeeId) {
                        queryParams += `&employee_id=${employeeId}`;
                    }

                    if (attendanceType && attendanceType !== 'all') {
                        queryParams += `&attendance_type=${attendanceType}`;
                    }

                    // Trigger download
                    window.location.href = `attendances/export?${queryParams}`;
                }

                function saveBulkAttendance() {
                    const clockIn = document.getElementById('checkInTime').value;
                    const clockOut = document.getElementById('checkOutTime').value;
                    const attendanceType = document.getElementById('bulkStatus').value;
                    const employeeIds = Array.from(document.querySelectorAll('#bulkEmployeeFilter option:checked')).map(
                        opt => opt.value);
                    const markAttendanceType = document.querySelector('input[name="mark_attendance"]:checked').value;

                    // For absent status, clear any clock in/out times
                    if (attendanceType === '0') { // absent
                        document.getElementById('checkInTime')._flatpickr.clear();
                        document.getElementById('checkOutTime')._flatpickr.clear();
                    }

                    // Validate for absent status
                    if (attendanceType === '0' && (clockIn || clockOut)) {
                        showToast('Clock in/out times must be empty for absent status', 'error');
                        return;
                    }

                    // Validate for other statuses
                    if (attendanceType !== '0') {
                        if (!clockIn) {
                            showToast('Clock In time is required', 'error');
                            return;
                        }
                    }

                    // Validate employee selection
                    if (employeeIds.length === 0) {
                        showToast('Please select at least one employee', 'error');
                        return;
                    }

                    // Validate date range/month selection
                    let dateRangeValid = true;
                    if (markAttendanceType === '1') { // multiple
                        const fromDate = document.getElementById('date_range_from').value;
                        const toDate = document.getElementById('date_range_to').value;
                        if (!fromDate || !toDate) {
                            showToast('Please select a valid date range', 'error');
                            dateRangeValid = false;
                        }
                    } else if (markAttendanceType === '2') { // month
                        const monthYear = document.getElementById('monthYearPicker').value;
                        if (!monthYear) {
                            showToast('Please select a month and year', 'error');
                            dateRangeValid = false;
                        }
                    }

                    if (!dateRangeValid) return;

                    const formData = new FormData(document.getElementById('bulkAttendanceForm'));

                    // Convert string values to integers for database
                    formData.set('attendance_type', attendanceType);
                    formData.set('attendance_workfrom', document.getElementById('bulkworkingFrom').value === 'office' ?
                        '1' : '0');
                    formData.set('mark_attendance', markAttendanceType);

                    if (attendanceType === '3') { // half_day
                        formData.set('half_day_type', document.querySelector('input[name="half_day_type"]:checked')
                            .value === '1' ? '1' : '0');
                    }

                    // Show loading state
                    const saveBtn = document.getElementById('saveBulkAttendance');
                    saveBtn.disabled = true;
                    saveBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';

                    fetch('attendances/bulk-create', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                fetchAttendances();
                                bulkAttendanceModal.hide();

                                let message = data.message;
                                if (data.holidays_removed > 0) {
                                    message += ` (${data.holidays_removed} holidays were removed)`;
                                }

                                showToast(message, 'success');

                                // Refresh holidays if any were removed
                                if (data.holidays_removed > 0) {
                                    fetchHolidays();
                                }
                            } else {
                                showToast(data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('An error occurred while saving bulk attendance', 'error');
                        })
                        .finally(() => {
                            saveBtn.disabled = false;
                            saveBtn.innerHTML = 'Save';
                        });
                }

                function showToast(message, type = 'success') {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });

                    Toast.fire({
                        icon: type,
                        title: message
                    });
                }

                document.getElementById('confirmImport').addEventListener('click', async function () {
                    const importBtn = this;
                    const importForm = document.getElementById('importForm');
                    const fileInput = document.getElementById('importFile');

                    // Validate file
                    if (!fileInput.files.length) {
                        showToast('Please select a file to import', 'error');
                        return;
                    }

                    // Set loading state
                    importBtn.disabled = true;
                    importBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Importing...';

                    try {
                        const formData = new FormData(importForm);
                        const response = await fetch('attendances/import', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                                'Accept': 'application/json',
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            // Handle server-side validation errors
                            const errorMsg = data.message ||
                                (data.errors ? Object.values(data.errors).join('<br>') : 'Import failed');
                            throw new Error(errorMsg);
                        }

                        showToast(data.message || 'Attendance imported successfully', 'success');
                        fetchAttendances();
                        bootstrap.Modal.getInstance(document.getElementById('importModal')).hide();
                        importForm.reset();

                    } catch (error) {
                        console.error('Import error:', error);
                        showToast(error.message ||
                            'Error importing file. Please check the file format and try again.',
                            'error');
                    } finally {
                        importBtn.disabled = false;
                        importBtn.innerHTML = 'Import';
                    }
                });
            });
        </script>
    </div>

    <!-- Include Flatpickr CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Include the month select plugin -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/plugins/monthSelect/index.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/plugins/monthSelect/style.css">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        .attendance-container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        }

        /* Header Styles */
        .planner-header {
            padding: 1.5rem;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
        }

        .planner-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
            margin-right: auto;
        }

        .planner-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
        }

        .control-group {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .control-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #495057;
        }

        .control-select {
            padding: 0.5rem 1rem;
            border: 1px solid #ced4da;
            border-radius: 8px;
            background-color: white;
            cursor: pointer;
        }

        .control-select:focus {
            border-color: #4dabf7;
            box-shadow: 0 0 0 3px rgba(77, 171, 247, 0.2);
            outline: none;
        }

        /* Buttons */
        .action-button {
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9375rem;
            display: flex;
            align-items: center;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .action-button.primary {
            background-color: #4dabf7;
            color: white;
        }

        .action-button.primary:hover {
            background-color: #339af0;
        }

        /* Attendance Table */
        .attendance-table-container {
            background-color: #fff;
            overflow-x: auto;
        }

        .attendance-header {
            display: flex;
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .employee-column {
            width: 250px;
            min-width: 250px;
            padding: 12px 20px;
            position: sticky;
            left: 0;
            z-index: 11;
            background-color: #f8f9fa;
            border-right: 1px solid #e9ecef;
        }

        .days-header {
            display: flex;
        }

        .day-header {
            width: 40px;
            min-width: 40px;
            padding: 8px 5px;
            text-align: center;
            border-right: 1px solid #e9ecef;
            font-weight: 500;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .day-header.weekend {
            background-color: #f1f3f5;
        }

        .day-name {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .day-number {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .total-column {
            width: 100px;
            min-width: 100px;
            padding: 12px;
            text-align: center;
            border-right: 1px solid #e9ecef;
            background-color: #f8f9fa;
        }

        .attendance-body {
            display: flex;
            flex-direction: column;
        }

        .employee-row {
            display: flex;
            border-bottom: 1px solid #e9ecef;
            min-height: 60px;
        }

        .employee-info {
            width: 250px;
            min-width: 250px;
            padding: 12px 20px;
            position: sticky;
            left: 0;
            z-index: 5;
            background-color: #fff;
            border-right: 1px solid #e9ecef;
            display: flex;
            align-items: center;
        }

        .day-cell {
            width: 40px;
            min-width: 40px;
            padding: 8px;
            border-right: 1px solid #e9ecef;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .day-cell:hover {
            background-color: #f8f9fa !important;
        }

        .day-cell i {
            font-size: 16px;
            display: block;
            width: 100%;
        }

        /* Present - Green */
        .day-cell.present i {
            color: #155724;
        }

        /* Absent - Red */
        .day-cell.absent i {
            color: #721c24;
        }

        /* Late - Yellow */
        .day-cell.late i {
            color: #856404;
        }

        /* Half Day - Blue */
        .day-cell.half-day i {
            color: #004085;
        }

        /* Leave - Gray */
        .day-cell.leave i {
            color: #383d41;
        }

        /* Holiday - Dark Green */
        .day-cell.holiday i {
            color: #0f5132;
        }

        /* Day Off - Light Gray */
        .day-cell.day-off i {
            color: #383d41;
        }

        .total-cell {
            width: 100px;
            min-width: 100px;
            padding: 12px;
            text-align: center;
            border-right: 1px solid #e9ecef;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 2px;
        }

        .pluseadd {
            color: rgba(0, 0, 0, 0.774)
        }

        .minusadd {
            color: rgba(0, 0, 0, 0.164)
        }

        .total-cell .small {
            font-size: 0.75rem;
            color: #495057;
        }

        /* Avatar */
        .avatar-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Dropdown Styling */
        .dropdown-toggle.control-select {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            text-align: left;
            padding: 0.5rem 1rem;
            background-color: white;
            border: 1px solid #ced4da;
            border-radius: 8px;
        }

        .dropdown-menu {
            padding: 0;
            border-radius: 8px;
            border: 1px solid #ced4da;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .dropdown-menu select {
            outline: none;
            box-shadow: none;
        }

        .dropdown-menu select:focus {
            border-color: transparent;
        }

        .dropdown-menu li:first-child {
            padding: 0.5rem;
            border-bottom: 1px solid #f1f3f5;
        }

        /* Pagination */
        .pagination-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            padding: 1rem;
            border-top: 1px solid #e9ecef;
            background-color: #f8f9fa;
        }

        .pagination-button {
            background: none;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .pagination-button:hover:not(:disabled) {
            background-color: #f1f3f5;
        }

        .pagination-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .page-info {
            font-size: 0.875rem;
            color: #495057;
        }

        /* Modal */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-bottom: 1px solid #e9ecef;
            padding: 1.25rem 1.5rem;
        }

        .modal-title {
            font-weight: 600;
            color: #2c3e50;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .bulkmodal {
            max-width: 1200px;
        }

        .modal-footer {
            border-top: 1px solid #e9ecef;
            padding: 1rem 1.5rem;
        }

        /* Responsive */
        @media (max-width: 992px) {

            .employee-column,
            .employee-info {
                width: 200px;
                min-width: 200px;
            }
        }

        @media (max-width: 768px) {
            .planner-controls {
                flex-direction: column;
                align-items: stretch;
                gap: 0.75rem;
            }

            .control-group {
                width: 100%;
            }

            .control-select {
                width: 100%;
            }

            .employee-column,
            .employee-info {
                width: 160px;
                min-width: 160px;
            }

            .day-header,
            .day-cell {
                width: 36px;
                min-width: 36px;
            }
        }

        @media (max-width: 576px) {

            .employee-column,
            .employee-info {
                width: 140px;
                min-width: 140px;
                padding: 8px 12px;
                font-size: 0.875rem;
            }

            .day-header,
            .day-cell {
                width: 32px;
                min-width: 32px;
                padding: 4px;
            }

            .day-name {
                font-size: 0.65rem;
            }

            .day-number {
                font-size: 0.75rem;
            }

            .total-column,
            .total-cell {
                width: 80px;
                min-width: 80px;
                padding: 8px;
            }
        }

        .flatpickr-calendar {
            width: 14em;
        }

        .time-picker {
            background-color: white;
            cursor: pointer;
        }

        .flatpickr-time input {
            color: #495057;
            font-weight: 500;
        }

        .flatpickr-time .numInputWrapper span.arrowUp:after {
            border-bottom-color: #4dabf7;
        }

        .flatpickr-time .numInputWrapper span.arrowDown:after {
            border-top-color: #4dabf7;
        }

        .flatpickr-time .flatpickr-am-pm {
            color: #4dabf7;
            font-weight: bold;
        }

        .flatpickr-time input:hover,
        .flatpickr-time .flatpickr-am-pm:hover,
        .flatpickr-time input:focus,
        .flatpickr-time .flatpickr-am-pm:focus {
            background: rgba(77, 171, 247, 0.1);
        }

        /* Date Range Picker Styles */
        .date-range-picker-container {
            width: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            position: absolute;
            z-index: 1050;
        }

        .date-range-calendar {
            display: flex;
            padding: 16px;
            background-color: #fff;
        }

        .month-container {
            width: 50%;
            padding: 0 8px;
        }

        .month-header {
            text-align: center;
            font-weight: 500;
            margin-bottom: 12px;
            color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .month-nav-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #495057;
            font-size: 0.9rem;
            padding: 2px 5px;
            border-radius: 4px;
        }

        .month-nav-btn:hover {
            background-color: #f1f3f5;
        }

        .weekdays {
            display: flex;
            margin-bottom: 8px;
        }

        .weekdays span {
            width: 14.28%;
            text-align: center;
            font-size: 12px;
            color: #666;
            font-weight: normal;
        }

        .days-grid {
            display: flex;
            flex-wrap: wrap;
        }

        .day {
            width: 14.28%;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
            border-radius: 4px;
            margin: 2px 0;
        }

        .day:hover {
            background-color: #f5f5f5;
        }

        .day.selected {
            color: #fff;
            background-color: #4285f4 !important;
        }

        .day.in-range {
            background-color: #e3f2fd;
        }

        .day.disabled {
            color: #ccc;
            cursor: not-allowed;
        }

        .day.today {
            font-weight: bold;
            color: #4285f4;
        }

        .date-range-footer {
            display: flex;
            justify-content: flex-end;
            padding: 12px 16px;
            border-top: 1px solid #e0e0e0;
            gap: 8px;
        }

        /* Error validation styling */
        .dropdown.error-validation .dropdown-toggle {
            border-color: #dc3545 !important;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .fade-out .error-message {
            opacity: 0;
        }

        .fade-out .dropdown-toggle {
            border-color: #ced4da !important;
            box-shadow: none !important;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        /* View modal styles */
        .attendance-details .form-control-plaintext {
            padding: 0.375rem 0;
            margin-bottom: 0;
            line-height: 1.5;
            background-color: transparent;
            border: none;
            border-bottom: 1px solid #e9ecef;
            min-height: 38px;
        }

        .shift-badge {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .readonly-input {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }

        /* Add to your existing styles */
        #holidayWarning {
            margin-bottom: 1rem;
        }

        #holidayWarning .alert {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 0;
        }

        #holidayWarning .fa-exclamation-triangle {
            font-size: 1.25rem;
        }
    </style>
    <style>
        .option-card {
            padding: 20px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            height: 100%;
        }

        .option-card:hover {
            border-color: #4dabf7;
            background-color: #f8f9fa;
            transform: translateY(-2px);
        }

        .option-card.selected {
            border-color: #4dabf7;
            background-color: #e7f5ff;
        }

        .option-icon {
            font-size: 2rem;
            color: #4dabf7;
            margin-bottom: 10px;
        }

        .modal-step {
            transition: opacity 0.3s ease;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            font-weight: bold;
        }

        .step.active {
            background-color: #4dabf7;
            color: white;
        }
    </style>

    <script>
              flatpickr("#export_date_range", {
mode: "range",
dateFormat: "Y-m-d",
showMonths: 2,

onClose: function(selectedDates){

if(selectedDates.length === 2){

let start = selectedDates[0].toISOString().slice(0,10);
let end = selectedDates[1].toISOString().slice(0,10);

document.getElementById('export_start_date').value = start;
document.getElementById('export_end_date').value = end;

}

}

});
    </script>
</x-layout>

