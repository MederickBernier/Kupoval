<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminSettingsController;

/**
 * Admin Settings Routes
 *
 * This file defines the routes for managing admin settings.
 *
 * Routes:
 * - GET /settings: Display the list of settings (AdminSettingsController@index)
 * - POST /settings: Store a new setting (AdminSettingsController@store)
 * - PUT /settings/{setting}: Update an existing setting (AdminSettingsController@update)
 * - DELETE /settings/{setting}: Delete a setting (AdminSettingsController@destroy)
 *
 * Route Names:
 * - admin.settings.index
 * - admin.settings.store
 * - admin.settings.update
 * - admin.settings.destroy
 */
Route::get('/settings', [AdminSettingsController::class, 'index'])->name('admin.settings.index');
Route::post('/settings', [AdminSettingsController::class, 'store'])->name('admin.settings.store');
Route::put('/settings/{setting}', [AdminSettingsController::class, 'update'])->name('admin.settings.update');
Route::delete('/settings/{setting}', [AdminSettingsController::class, 'destroy'])->name('admin.settings.destroy');
