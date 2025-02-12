<?php

use App\Http\Controllers\Admin\AdminOrdersController;
use Illuminate\Support\Facades\Route;

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
