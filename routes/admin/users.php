<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminUsersController;

Route::get('/users', [AdminUsersController::class, 'index'])->name('admin.users.index');
Route::delete('/users/{user}', [AdminUsersController::class, 'destroy'])->name('admin.users.destroy');

Route::get('/users/deactivated', [AdminUsersController::class, 'trashed'])->name('admin.users.trashed');
Route::post('/users/restore/{id}', [AdminUsersController::class, 'restore'])->name('admin.users.restore');
Route::delete('/users/{user}/force-delete', [AdminUsersController::class, 'forceDelete'])->name('admin.users.forceDelete');
