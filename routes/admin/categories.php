<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminCategoriesController;

Route::get('/categories', [AdminCategoriesController::class, 'index'])->name('admin.categories.index');
Route::get('/categories/create', [AdminCategoriesController::class, 'create'])->name('admin.categories.create');
Route::post('/categories', [AdminCategoriesController::class, 'store'])->name('admin.categories.store');
Route::get('/categories/{category}/edit', [AdminCategoriesController::class, 'edit'])->name('admin.categories.edit');
Route::put('/categories/{category}', [AdminCategoriesController::class, 'update'])->name('admin.categories.update');
Route::delete('/categories/{category}', [AdminCategoriesController::class, 'destroy'])->name('admin.categories.destroy');

Route::get('/categories/deactivated', [AdminCategoriesController::class, 'trashed'])->name('admin.categories.trashed');
Route::post('/categories/restore/{category}', [AdminCategoriesController::class, 'restore'])->name('admin.categories.restore');
Route::delete('/categories/force-delete/{category}', [AdminCategoriesController::class, 'forceDelete'])->name('admin.categories.forceDelete');
