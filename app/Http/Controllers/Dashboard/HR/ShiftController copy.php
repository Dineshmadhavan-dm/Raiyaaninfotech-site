<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\Department;
use App\Models\Holiday;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;

class ShiftController extends Controller
{


public function __construct()
{
    // Only apply middleware to shiftindex method
    $this->middleware(function ($request, $next) {
        $user = auth()->user();

        // Check user category - only allow 1 (Super Admin) and 3 (Admin)
        if ($user->categorie == 2) { // Employee
            return redirect()->route('emphome')->with('error', 'Access denied.');
        }

        return $next($request);
    })->only(['shiftindex']); // Only apply to shiftindex
}


public function upcomingHolidays()
{
    return Shift::where('shift_type', 4)
        ->where('date_no', '>=', now()->format('Y-m-d'))
        ->orderBy('date_no', 'asc')
        ->select('date_no as date', 'occasion')
        ->distinct()
        ->get();
}

    public function shiftindex()
    {
        $employees = Employee::where('delete_status', 1)
            ->with('Departmentid')
            ->get();

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

        return view('dashboard.hr.shiftplanner.index', compact(
            'employees',
            'departments',
            'superAdminName'
        ));
    }

    public function getShifts()
    {
        $shifts = Shift::where('delete_status', 1)->get();
        return response()->json($shifts);
    }

    private function getShiftTimes($shiftType)
    {
        switch ($shiftType) {
            case 'gs':
                return ['09:00:00', '18:00:00'];
            case 'ns':
                return ['22:00:00', '06:00:00'];
            case 'ms':
                return ['07:00:00', '17:00:00'];
            case 'dayoff':
                return [null, null];
            default:
                return [null, null];
        }
    }

    private function shiftTypeStringToInt($type)
    {
        return match ($type) {
            'gs' => 0,
            'ms' => 1,
            'ns' => 2,
            'dayoff' => 3,
             'holiday' => 4, // ✅ ADD THIS
            default => null
        };
    }

    private function assignShiftStringToInt($type)
    {
        return match ($type) {
            'date' => 0,
            'multiple' => 1,
            'month' => 2,
            default => null
        };
    }

public function create(Request $request)
{
    $request->validate([
        'employee_id' => 'required|exists:employees,emp_id',
        'date_no' => 'required|date_format:Y-m-d',
        'shift_type' => 'required',
    ]);

    try {
        DB::beginTransaction();

        $date = $request->date_no;
        $swapDate = $request->swap_date;

        if ($swapDate) {

            $currentShift = Shift::where('employee_id', $request->employee_id)
                ->where('date_no', $date)
                ->first();

            $swapShift = Shift::where('employee_id', $request->employee_id)
                ->where('date_no', $swapDate)
                ->first();

            if (!$currentShift) {
                $currentShift = Shift::create([
                    'employee_id' => $request->employee_id,
                    'date_no' => $date,
                ]);
            }

            if (!$swapShift) {
                $swapShift = Shift::create([
                    'employee_id' => $request->employee_id,
                    'date_no' => $swapDate,
                ]);
            }

            $currentData = [
                'shift_type' => $currentShift->shift_type,
                'shift_from_time' => $currentShift->shift_from_time,
                'shift_to_time' => $currentShift->shift_to_time,
                'holiday_type' => $currentShift->holiday_type,
                'occasion' => $currentShift->occasion,
            ];

            $swapData = [
                'shift_type' => $swapShift->shift_type,
                'shift_from_time' => $swapShift->shift_from_time,
                'shift_to_time' => $swapShift->shift_to_time,
                'holiday_type' => $swapShift->holiday_type,
                'occasion' => $swapShift->occasion,
            ];

            $currentShift->update($swapData);
            $swapShift->update($currentData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Shift swapped successfully'
            ]);
        }

        $dateObj = new DateTime($date);
        $isSunday = $dateObj->format('w') == 0;

        $employee = Employee::findOrFail($request->employee_id);

        $holiday = DB::table('holidays')
            ->leftJoin('holidaytypes', 'holidays.holidaytype', '=', 'holidaytypes.holidaytype_id')
            ->where('holidays.holiday_date', $date)
            ->where('holidays.holiday_department', $employee->cur_department)
            ->select(
                'holidays.*',
                'holidaytypes.holidaytype_name as holiday_type_name'
            )
            ->first();

        if ($holiday) {
            $finalShiftType = 'holiday';
            $shiftFromTime = null;
            $shiftToTime = null;
        } else {
            $finalShiftType = $request->shift_type;
            list($shiftFromTime, $shiftToTime) = $this->getShiftTimes($finalShiftType);
        }

        if ($finalShiftType === 'dayoff') {
            $shiftTypeInt = 3;
        } elseif ($finalShiftType === 'holiday') {
            $shiftTypeInt = 4;
        } else {
            $shiftTypeInt = $this->shiftTypeStringToInt($finalShiftType);
        }

        $holidayType = null;
        $occasion = null;

        if ($finalShiftType === 'holiday' && $holiday) {
            $holidayType = $holiday->holiday_type_name;
            $occasion = $holiday->occasion;
        }

        $notes = $request->notes;

        $existingShift = Shift::where('employee_id', $request->employee_id)
            ->where('date_no', $date)
            ->first();

        if ($existingShift) {
            $existingShift->update([
                'shift_type' => $shiftTypeInt,
                'shift_from_time' => $shiftFromTime,
                'shift_to_time' => $shiftToTime,
                'notes' => $notes,
                'holiday_type' => $holidayType,
                'occasion' => $occasion,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Shift updated successfully',
                'shift' => $existingShift,
            ]);
        }

        $shift = Shift::create([
            'employee_id' => $request->employee_id,
            'date_no' => $date,
            'shift_type' => $shiftTypeInt,
            'shift_from_time' => $shiftFromTime,
            'shift_to_time' => $shiftToTime,
            'notes' => $notes,
            'holiday_type' => $holidayType,
            'occasion' => $occasion,
            'is_auto_dayoff' => $isSunday
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Shift created successfully',
            'shift' => $shift,
            'is_sunday' => $isSunday
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Error processing shift: ' . $e->getMessage(),
            'error_details' => $e->getTraceAsString()
        ], 500);
    }
}

    public function bulkCreate(Request $request)
    {
        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,emp_id',
            'shift_type' => 'required',
            'assign_shift' => 'required|in:date,multiple,month',
        ]);

        try {
            DB::beginTransaction();
            $employeeIds = $request->employee_ids;
            $shiftTypeStr = $request->shift_type;
            $shiftTypeInt = $this->shiftTypeStringToInt($shiftTypeStr);
            list($shiftFromTime, $shiftToTime) = $this->getShiftTimes($shiftTypeStr);

            $assignShift = $this->assignShiftStringToInt($request->assign_shift);
            $notes = $request->notes;
            $departmentAdmin = $request->department_admin;

            // Get Sundays from request if provided
            $sundays = [];
            if ($request->has('sundays')) {
                $sundays = json_decode($request->sundays, true);
            }

            $dates = [];

            if ($assignShift === 0) {
                $request->validate(['date_no' => 'required|date']);
                $dates[] = Carbon::createFromFormat('d-m-Y', $request->date_no)->format('Y-m-d');
            } elseif ($assignShift === 1) {
                $request->validate([
                    'date_range_from' => 'required|date',
                    'date_range_to' => 'required|date|after_or_equal:date_range_from'
                ]);

                $startDate = new \DateTime($request->date_range_from);
                $endDate = new \DateTime($request->date_range_to);

                while ($startDate <= $endDate) {
                    $dates[] = $startDate->format('Y-m-d');
                    $startDate->modify('+1 day');
                }
            } elseif ($assignShift === 2) {
                $request->validate(['month_year' => 'required']);

                $monthYear = $request->month_year;
                $month = date('m', strtotime($monthYear));
                $year = date('Y', strtotime($monthYear));

                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $dates[] = sprintf('%s-%s-%s', $year, $month, str_pad($day, 2, '0', STR_PAD_LEFT));
                }
            }

            $createdShifts = [];
            $employees = Employee::with('Departmentid')->whereIn('emp_id', $employeeIds)->get();

            foreach ($employees as $employee) {
                foreach ($dates as $date) {
                    $dateString = is_string($date) ? $date : $date->format('Y-m-d');

                    // Check if this date is a Sunday
                    $isSunday = in_array($dateString, $sundays);

  $holiday = DB::table('holidays')
    ->leftJoin('holidaytypes', 'holidays.holidaytype', '=', 'holidaytypes.holidaytype_id')
    ->where('holidays.holiday_date', $dateString)
    ->where('holidays.holiday_department', $employee->cur_department)
    ->select(
        'holidays.*',
        'holidaytypes.holidaytype_name as holiday_type_name'
    )
    ->first();
if ($holiday) {
    $finalShiftType = 'holiday';
    $finalShiftFromTime = null;
    $finalShiftToTime = null;
   // ✅ KEEP ORIGINAL NOTES
$notes = $request->notes;
} else {
    $finalShiftType = $isSunday ? 'dayoff' : $shiftTypeStr;
    list($finalShiftFromTime, $finalShiftToTime) = $this->getShiftTimes($finalShiftType);
}

$finalShiftTypeInt = $this->shiftTypeStringToInt($finalShiftType);
                    // Check if shift already exists
                    $existingShift = Shift::where('employee_id', $employee->emp_id)
                        ->where('date_no', $dateString)
                        ->first();

                    if ($existingShift) {
                        // Update existing shift
                        $existingShift->update([
                            'shift_type' => $finalShiftTypeInt,
                            'shift_from_time' => $finalShiftFromTime,
                            'shift_to_time' => $finalShiftToTime,
                            'notes' => $notes,

                              // ✅ ADD THIS (you missed here)
    'holiday_type' => $holiday ? $holiday->holiday_type_name : null,
    'occasion' => $holiday ? $holiday->occasion : null,

                        ]);
                        $createdShifts[] = $existingShift;
                    } else {
                        // Create new shift
                        $shift = new Shift();
                        $shift->employee_id = $employee->emp_id;
                        $shift->department_name = $employee->cur_department;
                        $shift->department_admin = $this->getDepartmentAdminId($employee->cur_department);
                        $shift->employee_name = $employee->emp_id;
                        $shift->shift_type = $finalShiftTypeInt;
                        $shift->shift_from_time = $finalShiftFromTime;
                        $shift->shift_to_time = $finalShiftToTime;
                        $shift->assign_shift = $assignShift;
                        $shift->date_no = $dateString;
$shift->holiday_type = $holiday ? $holiday->holiday_type_name : null;
$shift->occasion = $holiday ? $holiday->occasion : null;

                        if ($assignShift === 1) {
                            $shift->date_range_from = $request->date_range_from;
                            $shift->date_range_to = $request->date_range_to;
                        } elseif ($assignShift === 2) {
                            $shift->month_year = $request->month_year;
                        }

                        $shift->notes = $notes;
                        $shift->save();
                        $createdShifts[] = $shift;
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($createdShifts) . ' shifts created/updated successfully',
                'shifts' => $createdShifts
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating bulk shifts: ' . $e->getMessage()
            ]);
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

public function update(Request $request)
{
    $request->validate([
        'shift_type' => 'required',
    ]);

    try {
        DB::beginTransaction();

        $date = $request->date_no;
        $swapDate = $request->swap_date;

        if ($swapDate) {

            $currentShift = Shift::where('employee_id', $request->employee_id)
                ->where('date_no', $date)
                ->first();

            $swapShift = Shift::where('employee_id', $request->employee_id)
                ->where('date_no', $swapDate)
                ->first();

            if (!$currentShift) {
                $currentShift = Shift::create([
                    'employee_id' => $request->employee_id,
                    'date_no' => $date,
                ]);
            }

            if (!$swapShift) {
                $swapShift = Shift::create([
                    'employee_id' => $request->employee_id,
                    'date_no' => $swapDate,
                ]);
            }

            $currentData = [
                'shift_type' => $currentShift->shift_type,
                'shift_from_time' => $currentShift->shift_from_time,
                'shift_to_time' => $currentShift->shift_to_time,
                'holiday_type' => $currentShift->holiday_type,
                'occasion' => $currentShift->occasion,
            ];

            $swapData = [
                'shift_type' => $swapShift->shift_type,
                'shift_from_time' => $swapShift->shift_from_time,
                'shift_to_time' => $swapShift->shift_to_time,
                'holiday_type' => $swapShift->holiday_type,
                'occasion' => $swapShift->occasion,
            ];

            $currentShift->update($swapData);
            $swapShift->update($currentData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Shift swapped successfully'
            ]);
        }

        $shift = Shift::findOrFail($request->shift_id);

        $employee = Employee::findOrFail($request->employee_id);

        $holiday = DB::table('holidays')
            ->leftJoin('holidaytypes', 'holidays.holidaytype', '=', 'holidaytypes.holidaytype_id')
            ->where('holidays.holiday_date', $date)
            ->where('holidays.holiday_department', $employee->cur_department)
            ->select(
                'holidays.*',
                'holidaytypes.holidaytype_name as holiday_type_name'
            )
            ->first();

        if ($holiday) {
            $finalShiftType = 'holiday';
            $shiftFromTime = null;
            $shiftToTime = null;
        } else {
            $finalShiftType = $request->shift_type;
            list($shiftFromTime, $shiftToTime) = $this->getShiftTimes($finalShiftType);
        }

        if ($finalShiftType === 'dayoff') {
            $shiftTypeInt = 3;
        } elseif ($finalShiftType === 'holiday') {
            $shiftTypeInt = 4;
        } else {
            $shiftTypeInt = $this->shiftTypeStringToInt($finalShiftType);
        }

        $holidayType = null;
        $occasion = null;

        if ($finalShiftType === 'holiday' && $holiday) {
            $holidayType = $holiday->holiday_type_name;
            $occasion = $holiday->occasion;
        }

        $notes = $request->notes;

        if ($request->employee_id) {
            $existingShift = Shift::where('employee_id', $request->employee_id)
                ->where('date_no', $date)
                ->where('shift_id', '!=', $request->shift_id)
                ->first();

            if ($existingShift) {
                return response()->json([
                    'success' => false,
                    'message' => 'Another shift already exists for this employee on the selected date'
                ]);
            }
        }

        $shift->update([
            'shift_type' => $shiftTypeInt,
            'shift_from_time' => $shiftFromTime,
            'shift_to_time' => $shiftToTime,
            'notes' => $notes,
            'holiday_type' => $holidayType,
            'occasion' => $occasion,
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Shift updated successfully',
            'shift' => $shift,
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error updating shift: ' . $e->getMessage()
        ]);
    }
}
    public function delete($id)
    {
        try {
            $shift = Shift::findOrFail($id);
            $shift->delete();

            return response()->json([
                'success' => true,
                'message' => 'Shift deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting shift: ' . $e->getMessage()
            ]);
        }
    }

    public function checkShift(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,emp_id',
            'date' => 'required|date'
        ]);

        $exists = Shift::where('employee_id', $request->employee_id)
            ->where('date_no', $request->date)
            ->exists();

        return response()->json([
            'exists' => $exists,
            'formatted_date' => Carbon::parse($request->date)->format('d-M-Y (l)')
        ]);
    }
}
