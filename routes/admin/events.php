<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminEventsController;

Route::get('/events', [AdminEventsController::class, 'index'])->name('admin.events.list');
Route::post('/events', [AdminEventsController::class, 'store'])->name('admin.events.store');
Route::put('/events/{event}', [AdminEventsController::class, 'update'])->name('admin.events.update');
Route::delete('/events/{event}', [AdminEventsController::class, 'destroy'])->name('admin.events.destroy');

Route::get('/events/deactivated', [AdminEventsController::class, 'trashed'])->name('admin.events.trashed');
Route::post('/events/restore/{event}', [AdminEventsController::class, 'restore'])->name('admin.events.restore');
Route::delete('/events/{event}/force-delete', [AdminEventsController::class, 'forceDelete'])->name('admin.events.forceDelete');
