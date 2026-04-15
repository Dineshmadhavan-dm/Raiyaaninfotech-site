<?php

use App\Http\Controllers\Dashboard\HR\HrDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('show/{id}', [HrDashboardController::class, 'adminshow'])->name('adminshow');
Route::delete('{id}', [HrDashboardController::class, 'admindestroy'])->name('admindestroy');
Route::post('create', [HrDashboardController::class, 'createadmin'])->name('createadmin');
Route::get('{id}/edit', [HrDashboardController::class, 'adminedit'])->name('adminedit');
Route::put('{id}', [HrDashboardController::class, 'adminupdate'])->name('adminupdate');
Route::get('adminempshow/{id}', [HrDashboardController::class, 'adminempshow'])->name('adminempshow');
Route::get('list', [HrDashboardController::class, 'adminlist'])->name('adminlist');
Route::get('editcolumn', [HrDashboardController::class, 'admineditcolumn'])->name('admineditcolumn');
Route::post('/admin/columns/save', [HrDashboardController::class, 'saveColumns'])->name('admin.save-columns');
Route::post('bulk', [HrDashboardController::class, 'bulkDelete'])->name('adminbulkdelete');
Route::get('profile', [HrDashboardController::class, 'profile'])->name('profile');
Route::patch('profile', [HrDashboardController::class, 'profilepost'])->name('profilepost');
Route::get('change-password', [HrDashboardController::class, 'cpwd'])->name('cpwd');
Route::post('change-password', [HrDashboardController::class, 'cpwdpost'])->name('cpwdpost');

Route::get('maintenance', [HrDashboardController::class, 'maintenance'])->name('maintenance');
Route::get('setting', [HrDashboardController::class, 'setting'])->name('setting');
