<?php

use App\Http\Controllers\Admin\AdminOrdersController;
use Illuminate\Support\Facades\Route;

/**
 * Admin Orders Routes
 *
 * This file contains the routes for managing orders in the admin panel.
 *
 * Routes:
 * - GET /orders: Display a listing of the orders.
 * - GET /orders/create: Show the form for creating a new order.
 * - POST /orders: Store a newly created order in storage.
 * - GET /orders/{order}: Display the specified order.
 * - GET /orders/{order}/edit: Show the form for editing the specified order.
 * - PUT /orders/{order}: Update the specified order in storage.
 * - DELETE /orders/{order}: Remove the specified order from storage.
 *
 * Additional Routes:
 * - GET /orders/deactivated: Display a listing of the deactivated orders.
 * - POST /orders/restore/{order}: Restore the specified deactivated order.
 * - DELETE /orders/{order}/force-delete: Permanently delete the specified order.
 *
 * Note: The {order} parameter must be a numeric value.
 */
Route::get('/orders', [AdminOrdersController::class, 'index'])->name('admin.orders.index');
Route::get('/orders/create', [AdminOrdersController::class, 'create'])->name('admin.orders.create');
Route::post('/orders', [AdminOrdersController::class, 'store'])->name('admin.orders.store');
Route::get('/orders/{order}', [AdminOrdersController::class, 'show'])->where('order', '[0-9]+')->name('admin.orders.show');
Route::get('/orders/{order}/edit', [AdminOrdersController::class, 'edit'])->where('order', '[0-9]+')->name('admin.orders.edit');
Route::put('/orders/{order}', [AdminOrdersController::class, 'update'])->where('order', '[0-9]+')->name('admin.orders.update');
Route::delete('/orders/{order}', [AdminOrdersController::class, 'destroy'])->where('order', '[0-9]+')->name('admin.orders.destroy');

Route::get('/orders/deactivated', [AdminOrdersController::class, 'trashed'])->name('admin.orders.trashed');
Route::post('/orders/restore/{order}', [AdminOrdersController::class, 'restore'])->where('order', '[0-9]+')->name('admin.orders.restore');
Route::delete('/orders/{order}/force-delete', [AdminOrdersController::class, 'forceDelete'])->where('order', '[0-9]+')->name('admin.orders.forceDelete');
