<?php

namespace App\Http\Controllers\Dashboard\Employee;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpholidayController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;
        $department = $employee->department;
        $departments = Department::where('delete_status', 1)->get();

        return view('dashboard.employee.holiday.index', compact('department', 'departments'));
    }

    public function getHolidays(Request $request)
    {
        $employee = Auth::user()->employee;
        $employeeDepartmentId = $employee->cur_department;

        $start = $request->input('start');
        $end = $request->input('end');

        try {
            $query = Holiday::with(['holidayType' => function ($query) {
                $query->select('holidaytype_id', 'holidaytype_name');
            }])
                ->whereBetween('holiday_date', [$start, $end])
                ->where('delete_status', 1);

            // Filter holidays for employee's department or all-department holidays
            $query->where(function ($q) use ($employeeDepartmentId) {
                $q->where('holiday_department', 'like', '%' . $employeeDepartmentId . '%')
                    ->orWhere('holiday_department', 'all')
                    ->orWhereNull('holiday_department');
            });

            $holidays = $query->get()
                ->map(function ($holiday) {
                    return [
                        'id' => $holiday->holiday_id,
                        'name' => $holiday->occasion,
                        'date' => $holiday->holiday_date,
                        'type' => $holiday->holidayType ? $holiday->holidayType->holidaytype_name : 'Unknown',
                        'type_id' => $holiday->holidaytype,
                        'type_name' => $holiday->holidayType ? $holiday->holidayType->holidaytype_name : null,
                        'holiday_department' => $holiday->holiday_department,
                    ];
                })
                ->toArray();

            return response()->json($holidays ?: []);
        } catch (\Exception $e) {
            \Log::error('Error fetching holidays for employee: ' . $e->getMessage());
            return response()->json([]);
        }
    }
}
