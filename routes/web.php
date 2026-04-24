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
        require __DIR__ . '/Dashboard/Employee/Accessories.php';
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


            require __DIR__ . '/Dashboard/HR/Inventory.php';
            require __DIR__ . '/Dashboard/HR/InventoryCategory.php';
            require __DIR__ . '/Dashboard/HR/InventoryAssignment.php';
            require __DIR__ . '/Dashboard/HR/InventoryMaintenance.php';
            require __DIR__ . '/Dashboard/HR/InventoryHistory.php';
            require __DIR__ . '/Dashboard/HR/InventoryReport.php';

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

