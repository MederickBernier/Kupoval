<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminSettingsController;

Route::get('/settings', [AdminSettingsController::class, 'index'])->name('admin.settings.list');
Route::post('/settings', [AdminSettingsController::class, 'store'])->name('admin.settings.store');
Route::put('/settings/{setting}', [AdminSettingsController::class, 'update'])->name('admin.settings.update');
Route::delete('/settings/{setting}', [AdminSettingsController::class, 'destroy'])->name('admin.settings.destroy');
