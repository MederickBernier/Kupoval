<?php

use App\Http\Controllers\Admin\PromotionController;
use Illuminate\Support\Facades\Route;

/**
 * Route definitions for managing promotions in the admin panel.
 *
 * Routes:
 * - GET /: List all promotions (name: admin.promotions.index)
 * - GET /create: Show form to create a new promotion (name: admin.promotions.create)
 * - POST /store: Store a new promotion (name: admin.promotions.store)
 * - GET /edit/{promotion}: Show form to edit a promotion (name: admin.promotions.edit)
 * - PUT /update/{promotion}: Update a promotion (name: admin.promotions.update)
 * - GET /trashed: List all trashed promotions (name: admin.promotions.trashed)
 * - PUT /restore/{id}: Restore a trashed promotion (name: admin.promotions.restore)
 * - DELETE /force-delete/{id}: Permanently delete a trashed promotion (name: admin.promotions.forceDelete)
 * - DELETE /delete/{promotion}: Soft delete a promotion (name: admin.promotions.destroy)
 * - GET /{promotion}: Show a single promotion (name: admin.promotions.show)
 */
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
