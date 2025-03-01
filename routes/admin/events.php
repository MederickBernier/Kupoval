<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminEventsController;

/**
 * Admin Events Routes
 *
 * This file contains the routes for managing events in the admin panel.
 * The routes include functionalities for listing, creating, updating,
 * deleting, restoring, and force deleting events.
 *
 * Routes:
 * - GET /events: Display a listing of events (AdminEventsController@index).
 * - POST /events: Store a newly created event (AdminEventsController@store).
 * - PUT /events/{event}: Update the specified event (AdminEventsController@update).
 * - DELETE /events/{event}: Remove the specified event (AdminEventsController@destroy).
 * - GET /events/deactivated: Display a listing of deactivated events (AdminEventsController@trashed).
 * - POST /events/restore/{event}: Restore the specified deactivated event (AdminEventsController@restore).
 * - DELETE /events/{event}/force-delete: Permanently delete the specified event (AdminEventsController@forceDelete).
 *
 * @package App\Routes\Admin
 */
Route::get('/events', [AdminEventsController::class, 'index'])->name('admin.events.index');
Route::post('/events', [AdminEventsController::class, 'store'])->name('admin.events.store');
Route::put('/events/{event}', [AdminEventsController::class, 'update'])->name('admin.events.update');
Route::delete('/events/{event}', [AdminEventsController::class, 'destroy'])->name('admin.events.destroy');

Route::get('/events/deactivated', [AdminEventsController::class, 'trashed'])->name('admin.events.trashed');
Route::post('/events/restore/{event}', [AdminEventsController::class, 'restore'])->name('admin.events.restore');
Route::delete('/events/{event}/force-delete', [AdminEventsController::class, 'forceDelete'])->name('admin.events.forceDelete');
