<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminArtworksController;

Route::get('/artworks', [AdminArtworksController::class, 'index'])->name('admin.artworks.index');
Route::get('/artworks/create', [AdminArtworksController::class, 'create'])->name('admin.artworks.create');
Route::post('/artworks', [AdminArtworksController::class, 'store'])->name('admin.artworks.store');
Route::get('/artworks/{artwork}/edit', [AdminArtworksController::class, 'edit'])->name('admin.artworks.edit');
Route::put('/artworks/{artwork}', [AdminArtworksController::class, 'update'])->name('admin.artworks.update');
Route::delete('/artworks/{artwork}', [AdminArtworksController::class, 'destroy'])->name('admin.artworks.destroy');

Route::get('/artworks/deactivated', [AdminArtworksController::class, 'trashed'])->name('admin.artworks.trashed');
Route::post('/artworks/restore/{artwork}', [AdminArtworksController::class, 'restore'])->name('admin.artworks.restore');
Route::delete('/artworks/{artwork}/force-delete', [AdminArtworksController::class, 'forceDelete'])->name('admin.artworks.forceDelete');
