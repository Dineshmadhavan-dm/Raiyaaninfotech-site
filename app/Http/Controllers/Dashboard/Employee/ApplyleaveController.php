<?php

namespace App\Http\Controllers\Dashboard\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\Leavetype;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplyleaveController extends Controller
{
    public function empleaveindex()
    {
        $employee = Auth::user()->employee;
        return view('dashboard.employee.applyleave.index', compact('employee'));
    }

    public function getLeaveTypes($employeeId)
    {
        $employee = Employee::find($employeeId);

        if (!$employee) {
            return response()->json([]);
        }

        $leaveTypes = Leavetype::where('delete_status', 1)
            ->where('employee_name_id', $employee->emp_id)
            ->get()
            ->map(function ($leaveType) use ($employee) {
                $usedDays = Leave::where('employee_id', $employee->emp_id)
                    ->where('leave_type_id', $leaveType->leavetype_id)
                    ->where('leave_status', '!=', 3) // Not rejected
                    ->whereBetween('leavedate_no', [$leaveType->leave_start_from, $leaveType->leave_end_to])
                    ->count();

                $remainingDays = $leaveType->leave_days - $usedDays;

                return [
                    'id' => $leaveType->leavetype_id,
                    'text' => $leaveType->leavetype_name_text .
                        " [{$remainingDays}/{$leaveType->leave_days} days remaining] " .
                        "[" . date('M', strtotime($leaveType->leave_start_from)) . " - " .
                        date('M', strtotime($leaveType->leave_end_to)) . "]",
                    'leave_days' => $leaveType->leave_days,
                    'start_date' => $leaveType->leave_start_from,
                    'end_date' => $leaveType->leave_end_to,
                    'remaining_days' => $remainingDays
                ];
            });

        return response()->json($leaveTypes);
    }

    public function getLeaveData(Request $request)
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return response()->json([
                'success' => true,
                'employee_leaves' => []
            ]);
        }

        $employeeLeavesQuery = Leave::with(['employees' => function ($query) {
            $query->with('Departmentid');
        }, 'leavetype'])
            ->where('employee_id', $employee->emp_id);


        if ($request->has('dateRange') && $request->dateRange) {
            $employeeLeavesQuery->where(function ($q) use ($request) {
                $q->whereBetween('leavedate_no', [$request->dateRange['from'], $request->dateRange['to']])
                    ->orWhereBetween('leavedaterange_from', [$request->dateRange['from'], $request->dateRange['to']])
                    ->orWhereBetween('leavedaterange_to', [$request->dateRange['from'], $request->dateRange['to']]);
            });
        }

        if ($request->has('status') && $request->status) {
            $employeeLeavesQuery->where('leave_status', $request->status);
        }

        $employeeLeaves = $employeeLeavesQuery->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'employee_leaves' => $this->processLeaves($employeeLeaves)
        ]);
    }

    private function processLeaves($leaves)
    {
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

    public function checkDuplicateLeaves(Request $request)
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return response()->json([
                'hasDuplicates' => false,
                'duplicateDates' => []
            ]);
        }

        $dates = $request->input('dates', []);
        $duplicateDates = [];

        foreach ($dates as $date) {
            $existingLeave = Leave::where('employee_id', $employee->emp_id)
                ->where(function ($query) use ($date) {
                    $query->where('leavedate_no', $date)
                        ->orWhere(function ($q) use ($date) {
                            $q->where('leavedaterange_from', '<=', $date)
                                ->where('leavedaterange_to', '>=', $date);
                        });
                })
                ->whereIn('leave_status', [1, 2])
                ->first();

            if ($existingLeave) {
                $duplicateDates[] = $date;
            }
        }

        return response()->json([
            'hasDuplicates' => !empty($duplicateDates),
            'duplicateDates' => $duplicateDates
        ]);
    }

    public function store(Request $request)
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        $request->validate([
            'leave_type_id' => 'required|exists:leavetypes,leavetype_id',
            'duration' => 'required|in:1,2,3,4',
            'reason' => 'required|string',
            'available_dates' => 'nullable|json',
        ]);

        $dates = $request->has('available_dates') ? json_decode($request->available_dates, true) : [];
        if (!is_array($dates)) $dates = [];


        $datesToProcess = [];
        $datesToOverwrite = [];

        foreach ($dates as $date) {
            $existingLeave = Leave::where('employee_id', $employee->emp_id)
                ->where(function ($query) use ($date) {
                    $query->where('leavedate_no', $date)
                        ->orWhere(function ($q) use ($date) {
                            $q->where('leavedaterange_from', '<=', $date)
                                ->where('leavedaterange_to', '>=', $date);
                        });
                })
                ->whereIn('leave_status', [1, 2])
                ->first();

            if ($existingLeave) {
                $datesToOverwrite[] = $date;
            } else {
                $datesToProcess[] = $date;
            }
        }


        $filteredDatesToProcess = [];
        $filteredDatesToOverwrite = [];

        foreach ($datesToProcess as $date) {
            if (!$this->isDayOffShift($date, $employee->emp_id)) {
                $filteredDatesToProcess[] = $date;
            }
        }

        foreach ($datesToOverwrite as $date) {
            if (!$this->isDayOffShift($date, $employee->emp_id)) {
                $filteredDatesToOverwrite[] = $date;
            }
        }


        $allFilteredDates = array_merge($filteredDatesToProcess, $filteredDatesToOverwrite);

        if (empty($allFilteredDates)) {
            return response()->json([
                'success' => false,
                'message' => 'All selected dates are day-offs. Please select different dates.'
            ]);
        }


        if (!empty($filteredDatesToOverwrite)) {
            $this->deleteExistingLeavesForDates($employee->emp_id, $filteredDatesToOverwrite);
        }

        $leaveType = Leavetype::find($request->leave_type_id);


        if ($leaveType) {
            $usedDays = Leave::where('employee_id', $employee->emp_id)
                ->where('leave_type_id', $leaveType->leavetype_id)
                ->where('leave_status', '!=', 3)
                ->whereBetween('leavedate_no', [$leaveType->leave_start_from, $leaveType->leave_end_to])
                ->count();

            $remainingDays = $leaveType->leave_days - $usedDays;

            if ($request->duration == 2 && count($allFilteredDates) > $remainingDays) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot assign leave - requested " . count($allFilteredDates) . " days exceeds remaining {$remainingDays} days"
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

            foreach ($allFilteredDates as $date) {
                Leave::create([
                    'employee_id' => $employee->emp_id,
                    'member' => $employee->emp_id,
                    'leave_type_id' => $request->leave_type_id,
                    'select_duration' => $request->duration,
                    'leave_status' => 2, // pending
                    'reason_forleave' => $request->reason,
                    'leave_file' => $fileData ? json_encode($fileData) : null,
                    'leavedaterange_from' => $request->date_range_from ?? $date,
                    'leavedaterange_to' => $request->date_range_to ?? $date,
                    'leavedate_no' => $date,
                ]);
            }

            $message = 'Leave applied successfully';
            if (!empty($filteredDatesToOverwrite)) {
                $message .= '. ' . count($filteredDatesToOverwrite) . ' existing leave(s) were overwritten.';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error applying leave: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $employee = Auth::user()->employee;

        $leave = Leave::with(['employees.Departmentid', 'leavetype'])
            ->where('leave_id', $id)
            ->where('employee_id', $employee->emp_id)
            ->first();

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

    public function update(Request $request, $id)
    {
        $employee = Auth::user()->employee;
        $leave = Leave::where('leave_id', $id)
            ->where('employee_id', $employee->emp_id)
            ->first();

        if (!$leave) {
            return response()->json([
                'success' => false,
                'message' => 'Leave not found'
            ], 404);
        }

        $request->validate([
            'leave_type_id' => 'required|exists:leavetypes,leavetype_id',
            'duration' => 'required|in:1,2,3,4',
            'reason' => 'required|string',
        ]);


        $dates = [];
        if ($request->duration == 2) {
            $start = new \DateTime($request->date_range_from);
            $end = new \DateTime($request->date_range_to);
            while ($start <= $end) {
                $dates[] = $start->format('Y-m-d');
                $start->modify('+1 day');
            }
        } else {
            $dates[] = $request->date;
        }


        $filteredDates = [];
        foreach ($dates as $date) {
            if (!$this->isDayOffShift($date, $employee->emp_id)) {
                $filteredDates[] = $date;
            }
        }

        if (empty($filteredDates)) {
            return response()->json([
                'success' => false,
                'message' => 'All selected dates are day-offs. Please select different dates.'
            ]);
        }


        $this->deleteExistingLeavesForDates($employee->emp_id, $filteredDates, $id);

        try {

            $fileData = null;
            if ($leave->leave_file) {
                $fileData = json_decode($leave->leave_file, true);
            }


            if ($request->has('delete_file') && $request->delete_file && $fileData && file_exists(public_path($fileData['path']))) {
                unlink(public_path($fileData['path']));
                $fileData = null;
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                if ($file->isValid()) {

                    if ($fileData && file_exists(public_path($fileData['path']))) {
                        unlink(public_path($fileData['path']));
                    }

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

            return response()->json([
                'success' => true,
                'message' => 'Leave updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating leave: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $employee = Auth::user()->employee;

        $leave = Leave::with(['employees.Departmentid', 'leavetype'])
            ->where('leave_id', $id)
            ->where('employee_id', $employee->emp_id)
            ->first();

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

    public function destroy($id)
    {
        $employee = Auth::user()->employee;

        $leave = Leave::where('leave_id', $id)
            ->where('employee_id', $employee->emp_id)
            ->first();

        if (!$leave) {
            return response()->json([
                'success' => false,
                'message' => 'Leave not found'
            ], 404);
        }

        try {

            Attendance::where('employee_id', $leave->employee_id)
                ->whereDate('attendancedate_no', $leave->leavedate_no)
                ->where('attendance_type', 5)
                ->delete();


            if ($leave->leave_file) {
                $files = json_decode($leave->leave_file, true);
                if ($files && isset($files['path']) && file_exists(public_path($files['path']))) {
                    unlink(public_path($files['path']));
                }
            }

            $leave->delete();

            return response()->json([
                'success' => true,
                'message' => 'Leave deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting leave: ' . $e->getMessage()
            ], 500);
        }
    }


    public function checkHoliday(Request $request)
    {
        $employee = Auth::user()->employee;
        $date = $request->input('date');

        if (!$date) {
            return response()->json(['is_holiday' => false]);
        }

        $dateObj = Carbon::parse($date);
        $reasons = [];
        $holidayNames = [];


        if ($dateObj->isSunday()) {
            $reasons[] = 'Sunday';
            $holidayNames[] = 'Sunday';
        }

        if ($employee) {
            $employeeDepartmentId = $employee->cur_department;


            $holiday = Holiday::where('holiday_date', $date)
                ->where('delete_status', 1)
                ->where(function ($query) use ($employeeDepartmentId) {
                    $query->whereNull('holiday_department')
                        ->orWhere('holiday_department', '')
                        ->orWhereRaw("FIND_IN_SET(?, holiday_department)", [$employeeDepartmentId]);
                })
                ->first();

            if ($holiday) {
                $reasons[] = 'Holiday';
                $holidayNames[] = $holiday->occasion;
            }


            $shift = Shift::where('employee_id', $employee->emp_id)
                ->where('date_no', $date)
                ->first();

            if ($shift && $shift->shift_type == 3) {
                $reasons[] = 'Day Off';
                $holidayNames[] = 'Day Off';
            }
        }

        return response()->json([
            'is_holiday' => !empty($reasons),
            'reasons' => $reasons,
            'holiday_names' => $holidayNames
        ]);
    }

    public function getEmployeeHolidays(Request $request)
    {
        $employee = Auth::user()->employee;

        $start = $request->input('start');
        $end = $request->input('end');

        if (!$employee) {
            return response()->json([]);
        }

        $employeeDepartmentId = $employee->cur_department;
        $allUnavailableDates = [];


        $query = Holiday::where('delete_status', 1);

        if ($start && $end) {
            $query->whereBetween('holiday_date', [$start, $end]);
        } else {
            $query->whereBetween('holiday_date', [now(), now()->addDays(30)]);
        }

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
                    'type' => $holiday->holidayType ? $holiday->holidayType->holidaytype_name : 'Unknown',
                    'reason' => 'Holiday'
                ];
            });

        // Get day-offs
        if ($start && $end) {
            $dayoffs = Shift::where('employee_id', $employee->emp_id)
                ->whereBetween('date_no', [$start, $end])
                ->where('shift_type', 3)
                ->get()
                ->map(function ($shift) {
                    return [
                        'id' => 'dayoff-' . $shift->id,
                        'name' => 'Day Off',
                        'date' => $shift->date_no,
                        'type' => 'Day Off',
                        'reason' => 'Day Off'
                    ];
                });

            $allUnavailableDates = $holidays->merge($dayoffs);
        } else {
            $allUnavailableDates = $holidays;
        }


        $startDate = $start ? Carbon::parse($start) : now();
        $endDate = $end ? Carbon::parse($end) : now()->addDays(30);

        while ($startDate <= $endDate) {
            if ($startDate->isSunday()) {
                $allUnavailableDates->push([
                    'id' => 'sunday-' . $startDate->format('Y-m-d'),
                    'name' => 'Sunday',
                    'date' => $startDate->format('Y-m-d'),
                    'type' => 'Sunday',
                    'reason' => 'Sunday'
                ]);
            }
            $startDate->addDay();
        }

        return response()->json($allUnavailableDates->values());
    }

    public function checkShift(Request $request)
    {
        $employee = Auth::user()->employee;

        $date = $request->input('date');

        if (!$employee) {
            return response()->json(['exists' => false]);
        }

        $shift = Shift::where('employee_id', $employee->emp_id)
            ->where('date_no', $date)
            ->first();

        $dateObj = Carbon::parse($date);
        $formattedDate = $dateObj->format('d-m-Y (l)');

        return response()->json([
            'exists' => (bool)$shift,
            'formatted_date' => $formattedDate
        ]);
    }

    public function checkDayOff(Request $request)
    {
        $employee = Auth::user()->employee;

        $date = $request->input('date');

        if (!$employee) {
            return response()->json(['is_dayoff' => false]);
        }

        $shift = Shift::where('employee_id', $employee->emp_id)
            ->where('date_no', $date)
            ->first();

        return response()->json([
            'is_dayoff' => $shift && $shift->shift_type == 3, // 3 = dayoff
            'date' => $date
        ]);
    }

    public function checkDayoffsInRange(Request $request)
    {
        $employee = Auth::user()->employee;

        $start = $request->input('start');
        $end = $request->input('end');

        if (!$employee) {
            return response()->json([]);
        }

        $dayoffs = [];
        $startDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);

        while ($startDate <= $endDate) {
            $date = $startDate->format('Y-m-d');
            $shift = Shift::where('employee_id', $employee->emp_id)
                ->where('date_no', $date)
                ->first();

            if ($shift && $shift->shift_type == 3) {
                $dayoffs[] = [
                    'date' => $date,
                    'reason' => 'Day Off'
                ];
            }

            $startDate->addDay();
        }

        return response()->json($dayoffs);
    }


    public function checkUnavailableDates(Request $request)
    {
        $employee = Auth::user()->employee;
        $start = $request->input('start');
        $end = $request->input('end');

        if (!$employee || !$start || !$end) {
            return response()->json([]);
        }

        $unavailableDates = [];
        $startDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);

        while ($startDate <= $endDate) {
            $date = $startDate->format('Y-m-d');
            $reasons = [];


            if ($startDate->isSunday()) {
                $reasons[] = 'Sunday';
            }


            $holiday = Holiday::where('holiday_date', $date)
                ->where('delete_status', 1)
                ->where(function ($query) use ($employee) {
                    $query->whereNull('holiday_department')
                        ->orWhere('holiday_department', '')
                        ->orWhereRaw("FIND_IN_SET(?, holiday_department)", [$employee->cur_department]);
                })
                ->first();

            if ($holiday) {
                $reasons[] = 'Holiday: ' . $holiday->occasion;
            }


            $shift = Shift::where('employee_id', $employee->emp_id)
                ->where('date_no', $date)
                ->first();

            if ($shift && $shift->shift_type == 3) {
                $reasons[] = 'Day Off';
            }

            if (!empty($reasons)) {
                $unavailableDates[] = [
                    'date' => $date,
                    'reasons' => $reasons
                ];
            }

            $startDate->addDay();
        }

        return response()->json($unavailableDates);
    }

    private function isDayOffShift($date, $employeeId)
    {
        $shift = Shift::where('employee_id', $employeeId)
            ->where('date_no', $date)
            ->first();

        return $shift && $shift->shift_type == 3; // 3 = dayoff
    }

    private function deleteExistingLeavesForDates($employeeId, $dates, $excludeLeaveId = null)
    {
        $query = Leave::where('employee_id', $employeeId)
            ->where(function ($q) use ($dates) {
                foreach ($dates as $date) {
                    $q->orWhere('leavedate_no', $date)
                        ->orWhere(function ($query) use ($date) {
                            $query->where('leavedaterange_from', '<=', $date)
                                ->where('leavedaterange_to', '>=', $date);
                        });
                }
            })
            ->whereIn('leave_status', [1, 2]); // Approved or pending

        if ($excludeLeaveId) {
            $query->where('leave_id', '!=', $excludeLeaveId);
        }

        $existingLeaves = $query->get();

        foreach ($existingLeaves as $leave) {

            Attendance::where('employee_id', $leave->employee_id)
                ->whereDate('attendancedate_no', $leave->leavedate_no)
                ->where('attendance_type', 5)
                ->delete();


            if ($leave->leave_file) {
                $files = json_decode($leave->leave_file, true);
                if ($files && isset($files['path']) && file_exists(public_path($files['path']))) {
                    unlink(public_path($files['path']));
                }
            }

            $leave->delete();
        }

        return count($existingLeaves);
    }



    public function getLeaveDates($id)
    {
        $employee = Auth::user()->employee;

        $leave = Leave::with(['employees.Departmentid'])
            ->where('leave_id', $id)
            ->where('employee_id', $employee->emp_id)
            ->first();

        if (!$leave) {
            return response()->json([
                'success' => false,
                'message' => 'Leave not found'
            ], 404);
        }


        if ($leave->select_duration == 2) {
            $dates = [];
            $startDate = Carbon::parse($leave->leavedaterange_from);
            $endDate = Carbon::parse($leave->leavedaterange_to);


            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                $dateStr = $date->format('Y-m-d');


                $isHoliday = $this->isDateUnavailable($dateStr, $leave->employee_id);
                $isSunday = $date->isSunday();
                $isDayOff = $this->isDayOffShift($dateStr, $leave->employee_id);

                $dateType = 'working-day';
                $holidayName = null;
                $holidayType = null;

                if ($isHoliday && !$isSunday && !$isDayOff) {
                    $dateType = 'holiday';

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

            $date = Carbon::parse($leave->leavedate_no);
            $dateStr = $date->format('Y-m-d');


            $isHoliday = $this->isDateUnavailable($dateStr, $leave->employee_id);
            $isSunday = $date->isSunday();
            $isDayOff = $this->isDayOffShift($dateStr, $leave->employee_id);

            $dateType = 'working-day';
            $holidayName = null;
            $holidayType = null;

            if ($isHoliday && !$isSunday && !$isDayOff) {
                $dateType = 'holiday';

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


    private function isDateUnavailable($date, $employeeId)
    {
        $dateObj = \Carbon\Carbon::parse($date);


        if ($dateObj->isSunday()) {
            return true;
        }


        if ($employeeId && $this->isDayOffShift($date, $employeeId)) {
            return true;
        }


        $employee = Employee::find($employeeId);
        if (!$employee) {
            return false;
        }

        $employeeDepartmentId = $employee->cur_department;


        return Holiday::where('holiday_date', $date)
            ->where('delete_status', 1)
            ->where(function ($query) use ($employeeDepartmentId) {
                $query->whereNull('holiday_department')
                    ->orWhere('holiday_department', '')
                    ->orWhereRaw("FIND_IN_SET(?, holiday_department)", [$employeeDepartmentId]);
            })
            ->exists();
    }
}
