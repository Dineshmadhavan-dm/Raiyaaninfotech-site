<?php

namespace App\Http\Controllers\Dashboard\HR;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Jobtype;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HolidayController extends Controller
{


    public function index()
    {
        $departments = Department::where('delete_status', 1)->get();

        return view('dashboard.hr.holiday.index', compact('departments'));
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'holiday_date' => 'required|date',
            'holiday_occasion' => 'required|string|max:255',
            'holiday_type' => 'required|exists:holidaytypes,holidaytype_id',
            'holiday_department' => 'required|array',
            'holiday_department.*' => 'exists:departments,dep_id',

        ]);



        try {
            DB::beginTransaction();

            $employees = Employee::where('delete_status', 1)
                ->when(!empty($validated['holiday_department']), function ($query) use ($validated) {
                    $query->whereIn('cur_department', $validated['holiday_department']);
                })

                ->get();

            $holiday = Holiday::create([
                'holiday_date' => $validated['holiday_date'],
                'occasion' => $validated['holiday_occasion'],
                'holidaytype' => $validated['holiday_type'],
                'holiday_department' => implode(',', array_map('intval', $validated['holiday_department'])),

                'delete_status' => 1
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Holiday added successfully',
                'holiday' => $holiday
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error adding holiday: ' . $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'holiday_date' => 'nullable|date',
            'holiday_occasion' => 'nullable|string|max:255',
            'holiday_type' => 'required|exists:holidaytypes,holidaytype_id',
            'holiday_department' => 'nullable|array',
            'holiday_department.*' => 'exists:departments,dep_id',

        ]);


        try {
            DB::beginTransaction();

            $holiday = Holiday::findOrFail($id);

            if (isset($validated['holiday_date'])) {
                Attendance::where('attendancedate_no', $holiday->holiday_date)->delete();
            }

            $updates = [
                'holiday_date' => $validated['holiday_date'] ?? $holiday->holiday_date,
                'occasion' => $validated['holiday_occasion'] ?? $holiday->occasion,
                'holidaytype' => $validated['holiday_type'] ?? $holiday->holidaytype,
                'holiday_department' => isset($validated['holiday_department']) ?
                    implode(',', array_map('intval', $validated['holiday_department'])) :
                    $holiday->holiday_department,

            ];

            $holiday->update($updates);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Holiday updated successfully',
                'holiday' => $holiday
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating holiday: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeMultiple(Request $request)
    {
        $validated = $request->validate([
            'multi_holiday_date' => 'required|array',
            'multi_holiday_date.*' => 'required|date',
            'multi_holiday_occasion' => 'required|array',
            'multi_holiday_occasion.*' => 'required|string|max:255',
            'multi_holiday_type' => 'required|array',
            'multi_holiday_type.*' => 'required|exists:holidaytypes,holidaytype_id',
            'holiday_department' => 'required|array',
            'holiday_department.*' => 'exists:departments,dep_id',

        ]);

        try {
            DB::beginTransaction();

            $employees = Employee::where('delete_status', 1)
                ->when(!empty($validated['holiday_department']), function ($query) use ($validated) {
                    $query->whereIn('cur_department', $validated['holiday_department']);
                })


                ->get();

            $holidays = [];
            foreach ($validated['multi_holiday_date'] as $index => $date) {


                $holiday = Holiday::create([
                    'holiday_date' => $date,
                    'occasion' => $validated['multi_holiday_occasion'][$index],
                    'holidaytype' => $validated['multi_holiday_type'][$index],
                    'holiday_department' => implode(',', array_map('intval', $validated['holiday_department'])),

                    'delete_status' => 1
                ]);

                $holidays[] = $holiday;
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Holidays added successfully',
                'holidays' => $holidays
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error adding holidays: ' . $e->getMessage()
            ], 500);
        }
    }
public function getHolidays(Request $request)
{
    $start = $request->input('start');
    $end = $request->input('end');
    $department = $request->input('department', 'all');

    try {
        $query = Holiday::with(['holidayType' => function ($query) {
            $query->select('holidaytype_id', 'holidaytype_name');
        }])
            ->where('delete_status', 1);

        // Apply date range filter if provided
        if ($start && $end) {
            $query->whereBetween('holiday_date', [$start, $end]);
        } else {
            // If no date range, get holidays for current year
            $currentYear = now()->year;
            $query->whereYear('holiday_date', $currentYear);
        }

        // Apply department filter
        if ($department !== 'all') {
            $query->where(function($q) use ($department) {
                $q->where('holiday_department', 'like', "%$department%")
                  ->orWhereNull('holiday_department')
                  ->orWhere('holiday_department', '')
                  ->orWhere('holiday_department', 'all');
            });
        }

        $holidays = $query->get()
            ->map(function ($holiday) {
                return [
                    'id' => $holiday->holiday_id,
                    'name' => $holiday->occasion,
                    'date' => $holiday->holiday_date,
                    'type' => $holiday->holidaytype,
                    'type_id' => $holiday->holidaytype,
                    'type_name' => $holiday->holidayType ? $holiday->holidayType->holidaytype_name : 'Unknown',
                    'department' => $holiday->holiday_department,
                    'holiday_department' => $holiday->holiday_department,
                ];
            })
            ->toArray();

        return response()->json($holidays ?: []);
    } catch (\Exception $e) {
        \Log::error('Error fetching holidays: ' . $e->getMessage());
        return response()->json([]);
    }
}

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $holiday = Holiday::findOrFail($id);

            Attendance::where('attendancedate_no', $holiday->holiday_date)->delete();

            $holiday->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Holiday deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting holiday: ' . $e->getMessage()
            ], 500);
        }
    }
}
