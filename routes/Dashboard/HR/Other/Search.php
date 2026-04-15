<?php

use App\Http\Controllers\Dashboard\HR\Other\SearchController;
use Illuminate\Support\Facades\Route;



Route::get('/search-companyemail', [SearchController::class, 'search'])->name('companyemail.search');
Route::get('/search-location', [SearchController::class, 'searchloc'])->name('location.search');
Route::get('/search-role', [SearchController::class, 'searchrole'])->name('role.search');
Route::get('/search-designation', [SearchController::class, 'searchdesignation'])->name('designation.search');
Route::get('/search-department', [SearchController::class, 'searchdepartment'])->name('department.search');
Route::get('/search-bloodgroup', [SearchController::class, 'searchbloodgroup'])->name('bloodgroup.search');
Route::get('/search-jobtype', [SearchController::class, 'searchjobtype'])->name('jobtype.search');
Route::get('/search-holidaytype', [SearchController::class, 'searchholidaytype'])->name('holidaytype.search');
Route::get('/search-relationship', [SearchController::class, 'searchrelationship'])->name('relationship.search');
Route::get('/search-qualification', [SearchController::class, 'searchqualification'])->name('qualification.search');