<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminUsersController;

/**
 * Route definitions for managing admin users.
 *
 * Routes:
 * - GET /users: Display a listing of the users.
 *   - Controller: AdminUsersController
 *   - Method: index
 *   - Name: admin.users.index
 *
 * - DELETE /users/{user}: Remove the specified user.
 *   - Controller: AdminUsersController
 *   - Method: destroy
 *   - Name: admin.users.destroy
 *
 * - GET /users/deactivated: Display a listing of deactivated users.
 *   - Controller: AdminUsersController
 *   - Method: trashed
 *   - Name: admin.users.trashed
 *
 * - POST /users/restore/{id}: Restore the specified deactivated user.
 *   - Controller: AdminUsersController
 *   - Method: restore
 *   - Name: admin.users.restore
 *
 * - DELETE /users/{user}/force-delete: Permanently delete the specified user.
 *   - Controller: AdminUsersController
 *   - Method: forceDelete
 *   - Name: admin.users.forceDelete
 */
Route::get('/users', [AdminUsersController::class, 'index'])->name('admin.users.index');
Route::delete('/users/{user}', [AdminUsersController::class, 'destroy'])->name('admin.users.destroy');

Route::get('/users/deactivated', [AdminUsersController::class, 'trashed'])->name('admin.users.trashed');
Route::post('/users/restore/{id}', [AdminUsersController::class, 'restore'])->name('admin.users.restore');
Route::delete('/users/{user}/force-delete', [AdminUsersController::class, 'forceDelete'])->name('admin.users.forceDelete');
