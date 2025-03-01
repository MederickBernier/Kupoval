<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

/**
 * Define a route for the admin dashboard.
 *
 * This route responds to GET requests at the '/dashboard' URL.
 * It uses the 'index' method of the AdminController class to handle the request.
 * The route is named 'admin.dashboard'.
 */
Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
