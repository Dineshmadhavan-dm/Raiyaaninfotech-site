<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MarkAbsentAttendance extends Command
{
    protected $signature = 'attendance:mark-absent';
    protected $description = 'Mark absent attendance for employees without attendance records at end of day';

    public function handle()
    {
        $today = Carbon::now('Asia/Kolkata')->format('Y-m-d');
        $this->info("🔄 Processing auto-absent for: {$today}");

        $employees = Employee::where('delete_status', 1)->get();

        $absentCount = 0;
        $skippedCount = 0;

        foreach ($employees as $employee) {
            try {
                // 1. Already marked?
                if (Attendance::where('employee_id', $employee->emp_id)
                    ->where('attendancedate_no', $today)
                    ->exists()
                ) {
                    $skippedCount++;
                    continue;
                }

                // 2. Holiday?
                if ($this->isHoliday($employee, $today)) {
                    $skippedCount++;
                    continue;
                }

                // 3. Approved Leave?
                if ($this->hasApprovedLeave($employee, $today)) {
                    $skippedCount++;
                    continue;
                }

                // 4. Working shift? (skip if day off or no shift)
                if (!$this->hasWorkingShift($employee, $today)) {
                    $skippedCount++;
                    continue;
                }

              $serverIp = $this->getServerIp();
$location = $this->getLocationFromIp($serverIp);

Attendance::create([
    'employee_id'          => $employee->emp_id,
    'attendancedate_no'    => $today,
    'attendance_type'      => 0,
    'clock_in'             => null,
    'clock_out'            => null,
    'clock_in_ip'          => $serverIp,
    'clock_out_ip'         => $serverIp,

    'attendance_loc'       => $location, // ✅ City / fallback text
    'attendance_workfrom'  => 0,
    'attendance_empname'   => $employee->emp_id,
    'attendance_depname'   => optional($employee->Departmentid)->dep_id,
    'attendance_depadmin'  => $this->getDepartmentAdminId(optional($employee->Departmentid)->dep_id),
    'created_at'           => now(),
    'updated_at'           => now(),
]);


                // 6. Also create leave record
                $this->createLeaveForAbsence($employee, $today);

                $absentCount++;
            } catch (\Throwable $e) {
                Log::error("❌ Error processing employee {$employee->emp_id}: " . $e->getMessage());
                $skippedCount++;
            }
        }

        $this->info("✅ Completed: {$absentCount} absent, {$skippedCount} skipped");
        Log::info("Auto-absent: {$absentCount} created, {$skippedCount} skipped for {$today}");
    }

   private function getServerIp()
{
    $ip = getHostByName(getHostName());

    return filter_var($ip, FILTER_VALIDATE_IP)
        ? $ip
        : '127.0.0.1';
}

    private function isHoliday($employee, $date)
    {
        return Holiday::where('holiday_date', $date)
            ->where('delete_status', 1)
            ->where(function ($q) use ($employee) {
                $depId = optional($employee->Departmentid)->dep_id;
                $q->where('holiday_department', 'LIKE', "%{$depId}%")
                    ->orWhereNull('holiday_department')
                    ->orWhere('holiday_department', '');
            })
            ->exists();
    }

    private function hasApprovedLeave($employee, $date)
    {
        return Leave::where('employee_id', $employee->emp_id)
            ->whereDate('leavedate_no', $date)
            ->where('leave_status', 1) // Approved
            ->where('delete_status', 1)
            ->exists();
    }

    private function hasWorkingShift($employee, $date)
    {
        $shift = Shift::where('employee_id', $employee->emp_id)
            ->where('date_no', $date)
            ->first();

        return $shift && $shift->shift_type != 3; // 3 = Day Off
    }

    private function getDepartmentAdminId($departmentId)
    {
        $admin = User::role('Admin')
            ->whereHas('employee', fn($q) => $q->where('cur_department', $departmentId))
            ->first();

        return $admin?->id ?? User::role('Super admin')->value('id');
    }

    private function createLeaveForAbsence($employee, $date)
    {
        if (Leave::where('employee_id', $employee->emp_id)
            ->whereDate('leavedate_no', $date)
            ->exists()
        ) {
            return;
        }

        Leave::create([
            'employee_id'    => $employee->emp_id,
            'member'         =>  $employee->emp_id,
            'leave_type_id'  => 0,
            'select_duration' => 1, // Full day
            'leave_status'   => 2, // Pending
            'reason_forleave' => 'Absent marked',
            'leavedate_no'   => $date,
            'delete_status'  => 1,
        ]);
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
        return 'System Auto Absent';
    }

    try {
        $response = file_get_contents("http://ip-api.com/json/{$ip}");
        $data = json_decode($response, true);

        if ($data && $data['status'] === 'success') {
            return $data['city'] ?? 'System Auto Absent';
        }
    } catch (\Throwable $e) {
        Log::warning('IP location lookup failed: ' . $e->getMessage());
    }

    return 'System Auto Absent';
}

}
