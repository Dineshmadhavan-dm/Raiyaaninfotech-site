<?php

use App\Http\Controllers\Dashboard\HR\Other\RelationshipController;
use Illuminate\Support\Facades\Route;


Route::get('relationship', [RelationshipController::class, 'relationship'])->name('relationship');
Route::post('relationship', [RelationshipController::class, 'relationshippost'])->name('relationshippost');
Route::get('relationship/edit/{relationship_id}', [RelationshipController::class, 'relationshipedit'])->name('relationshipedit');
Route::put('relationship/update/{relationship_id}', [RelationshipController::class, 'relationshipupdate'])->name('relationshipupdate');
Route::delete('relationship/delete/{relationship_id}', [RelationshipController::class, 'relationshipdelete'])->name('relationshipdelete');
