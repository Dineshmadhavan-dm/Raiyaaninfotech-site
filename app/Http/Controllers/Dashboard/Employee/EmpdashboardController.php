<?php

namespace App\Http\Controllers\Dashboard\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  use App\Models\Leave;
use App\Models\Holiday;
use App\Models\Task;
use App\Models\Chattask;
use Illuminate\Support\Facades\Auth;


class EmpdashboardController extends Controller
{


public function emphome()
{
    $employee = Auth::user()->employee;
    $empId = Auth::user()->employeerole_id;

    // ✅ Leave Count (Approved + Pending)
    $leaveCount = Leave::where('employee_id', $empId)
        ->whereIn('leave_status', [1, 2])
        ->count();

    // ✅ Holiday Count (Current Month)
    $holidayCount = Holiday::where('delete_status', 1)
        ->count();

    // ✅ Task Count
    $taskCount = Task::where('delete_status', 1)
        ->where(function ($query) use ($empId) {
            $query->where('task_assignedto', 'LIKE', '%"' . $empId . '"%')
                ->orWhere('task_assignedto', 'LIKE', "%{$empId}%");
        })
        ->count();

    // ✅ Email / Notification Count
    $emailCount = Chattask::where('receiver_id', $empId)
        ->where('is_read', 0)
        ->where('delete_status', 1)
        ->count();

    return view('dashboard.employee.empdashboard.index', compact(
        'leaveCount',
        'holidayCount',
        'taskCount',
        'emailCount'
    ));
}
}
