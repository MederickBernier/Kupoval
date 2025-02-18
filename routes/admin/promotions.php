<?php

use App\Http\Controllers\Admin\PromotionController;
use Illuminate\Support\Facades\Route;

// List all promotions
Route::get('/', [PromotionController::class, 'index'])->name('admin.promotions.index');

// Create new promotion
Route::get('/create', [PromotionController::class, 'create'])->name('admin.promotions.create');
Route::post('/store', [PromotionController::class, 'store'])->name('admin.promotions.store');

// Edit and update promotion
Route::get('/edit/{promotion}', [PromotionController::class, 'edit'])->name('admin.promotions.edit');
Route::put('/update/{promotion}', [PromotionController::class, 'update'])->name('admin.promotions.update');

// Soft delete (Trash)
Route::get('/trashed', [PromotionController::class, 'trashed'])->name('admin.promotions.trashed');
Route::put('/restore/{id}', [PromotionController::class, 'restore'])->name('admin.promotions.restore');
Route::delete('/force-delete/{id}', [PromotionController::class, 'forceDelete'])->name('admin.promotions.forceDelete');

// Delete a promotion
Route::delete('/delete/{promotion}', [PromotionController::class, 'destroy'])->name('admin.promotions.destroy');

// Show single promotion (placed last)
Route::get('/{promotion}', [PromotionController::class, 'show'])->name('admin.promotions.show');
