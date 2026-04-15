<?php

use App\Http\Controllers\Dashboard\HR\EmploymentController;
use App\Models\Employee;
use Illuminate\Support\Facades\Route;


Route::post('/employees/import', [EmploymentController::class, 'import'])->name('employee.import');
Route::get('list', [EmploymentController::class, 'emplist'])->name('emplist');
Route::get('create', [EmploymentController::class, 'empcreate'])->name('empcreate');
Route::get('show/{id}', [EmploymentController::class, 'empshow'])->name('empshow');
Route::post('create', [EmploymentController::class, 'empcreatepost'])->name('empcreatepost');
Route::get('edit/{id}', [EmploymentController::class, 'empedit'])->name('empedit');
Route::put('update/{id}', [EmploymentController::class, 'empupdate'])->name('empupdate');
Route::delete('delete-single', [EmploymentController::class, 'empdestroy'])->name('empdestroy');
Route::delete('bulk-delete', [EmploymentController::class, 'bulkDelete'])->name('empbulkdelete');
Route::post('/employees/save-columns', [EmploymentController::class, 'saveColumns'])->name('save.columns');
Route::get('editcolumn', [EmploymentController::class, 'employeditcolumn'])->name('employeditcolumn');
Route::post('/employees/reset-columns', [EmploymentController::class, 'resetColumns'])->name('reset.columns');


Route::get('leave/summary/{id}', [EmploymentController::class, 'getLeaveSummary']);
Route::get('leave/recent/{id}', [EmploymentController::class, 'getRecentLeaves']);
Route::get('leave/all/{id}', [EmploymentController::class, 'getAllLeaves']);


Route::get('confirmedemp', [EmploymentController::class, 'confirmedemp'])->name('confirmedemp');
Route::get('confirmedshow/{id}', [EmploymentController::class, 'confirmedshow'])->name('confirmedshow');
