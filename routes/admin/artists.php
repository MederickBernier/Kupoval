<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminArtistsController;

Route::get('/artists', [AdminArtistsController::class, 'index'])->name('admin.artists.index');
Route::get('/artists/create', [AdminArtistsController::class, 'create'])->name('admin.artists.create');
Route::post('/artists', [AdminArtistsController::class, 'store'])->name('admin.artists.store');
Route::get('/artists/{artist}/edit', [AdminArtistsController::class, 'edit'])->name('admin.artists.edit');
Route::put('/artists/{artist}', [AdminArtistsController::class, 'update'])->name('admin.artists.update');
Route::delete('/artists/{artist}', [AdminArtistsController::class, 'destroy'])->name('admin.artists.destroy');
Route::get('/artists/deactivated', [AdminArtistsController::class, 'trashed'])->name('admin.artists.trashed');
Route::post('/artists/restore/{artist}', [AdminArtistsController::class, 'restore'])->name('admin.artists.restore');
Route::delete('/artists/{artist}/force-delete', [AdminArtistsController::class, 'forceDelete'])->name('admin.artists.forceDelete');
