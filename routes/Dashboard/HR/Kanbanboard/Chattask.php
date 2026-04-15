<?php

use App\Http\Controllers\Dashboard\HR\Kanbanboard\ChattaskController;
use Illuminate\Support\Facades\Route;






Route::prefix('task')->group(function () {
    Route::get('{id}/chat', [ChattaskController::class, 'showTaskChat'])->name('task.chat');
    Route::post('{id}/send-message', [ChattaskController::class, 'sendTaskMessage'])->name('task.send.message');
    Route::get('{id}/messages', [ChattaskController::class, 'getTaskMessages'])->name('task.get.messages');
    Route::post('{id}/mark-read', [ChattaskController::class, 'markMessagesAsRead'])->name('task.mark.read');
    Route::get('{id}/details', [ChattaskController::class, 'getTaskDetails'])->name('task.get.details');
});

Route::prefix('subtask')->group(function () {
    Route::get('{id}/chat', [ChattaskController::class, 'showSubtaskChat'])->name('subtask.chat');
    Route::post('{id}/send-message', [ChattaskController::class, 'sendSubtaskMessage'])->name('subtask.send.message');
    Route::get('{id}/messages', [ChattaskController::class, 'getSubtaskMessages'])->name('subtask.get.messages');
    Route::post('{id}/mark-read', [ChattaskController::class, 'markMessagesAsRead'])->name('subtask.mark.read');
    Route::get('{id}/details', [ChattaskController::class, 'getSubtaskDetails'])->name('subtask.get.details');
});

Route::prefix('messages')->group(function () {
    Route::put('{id}', [ChattaskController::class, 'updateMessage'])->name('messages.update');
    Route::delete('{id}', [ChattaskController::class, 'deleteMessage'])->name('messages.delete');
    Route::get('unread-counts', [ChattaskController::class, 'getUnreadCounts']);
    Route::post('mark-read/{id}', [ChattaskController::class, 'markMessagesAsRead']);
});



