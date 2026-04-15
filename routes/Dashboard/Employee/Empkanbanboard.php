<?php

use App\Http\Controllers\Dashboard\Employee\EmpKanbanboardController;
use App\Http\Controllers\Dashboard\Employee\EmpTaskchatController;
use Illuminate\Support\Facades\Route;

Route::get('empkanbanboard', [EmpKanbanboardController::class, 'empkanbanboard'])->name('empkanbanboard');
Route::get('empkanbanboard/project/{id}', [EmpKanbanboardController::class, 'getProjectDetails'])->name('empkanbanboard.project.details');


Route::get('etaskchat', [EmpTaskchatController::class, 'etaskchat'])->name('etaskchat');
Route::get('etaskchat/task/{id}/messages', [EmpTaskchatController::class, 'getTaskMessages'])->name('etaskchat.task.messages');
Route::get('etaskchat/subtask/{id}/messages', [EmpTaskchatController::class, 'getSubtaskMessages'])->name('etaskchat.subtask.messages');
Route::post('etaskchat/task/{id}/send-message', [EmpTaskchatController::class, 'sendTaskMessage'])->name('etaskchat.task.send');
Route::post('etaskchat/subtask/{id}/send-message', [EmpTaskchatController::class, 'sendSubtaskMessage'])->name('etaskchat.subtask.send');
Route::delete('etaskchat/messages/{id}', [EmpTaskchatController::class, 'deleteMessage'])->name('etaskchat.message.delete');
Route::get('etaskchat/unread-counts', [EmpTaskchatController::class, 'getUnreadCounts'])->name('etaskchat.unread.counts');
Route::get('etaskchat/notifications', [EmpTaskchatController::class, 'getTaskNotifications'])->name('etaskchat.notifications');
Route::post('etaskchat/notifications/{id}/read', [EmpTaskchatController::class, 'markAsRead'])->name('etaskchat.notification.read');
Route::post('etaskchat/notifications/read-all', [EmpTaskchatController::class, 'markAllAsRead'])->name('etaskchat.notifications.read-all');
Route::put('etaskchat/messages/{id}', [EmpTaskchatController::class, 'updateMessage'])->name('etaskchat.message.update');
