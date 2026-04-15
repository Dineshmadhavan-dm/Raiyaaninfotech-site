<?php

use App\Http\Controllers\Dashboard\HR\CompanyEmailController;
use Illuminate\Support\Facades\Route;


Route::resource('companyemail', CompanyEmailController::class)
    ->parameters(['companyemail' => 'employee']) // Map parameter name
    ->except(['show', 'destroy'])
    ->names([
        'index' => 'companyemail.index',
        'create' => 'companyemail.create',
        'store' => 'companyemail.store',
        'edit' => 'companyemail.edit',
        'update' => 'companyemail.update'
    ]);
// Special route for creating email for specific employee
Route::get('companyemail/create/{employee}', [CompanyEmailController::class, 'create'])
    ->name('companyemail.create');

Route::post('/get-permissions-for-roles', [CompanyEmailController::class, 'getPermissionsForRoles'])
    ->name('getPermissionsForRoles');
