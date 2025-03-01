<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminArtworksController;

/**
 * Admin Artworks Routes
 *
 * This file contains the routes for managing artworks in the admin panel.
 * The routes are defined using Laravel's Route facade and are mapped to
 * the corresponding methods in the AdminArtworksController.
 *
 * Routes:
 * - GET /artworks: Display a listing of artworks (index).
 * - GET /artworks/create: Show the form for creating a new artwork (create).
 * - POST /artworks: Store a newly created artwork in storage (store).
 * - GET /artworks/{artwork}/edit: Show the form for editing the specified artwork (edit).
 * - PUT /artworks/{artwork}: Update the specified artwork in storage (update).
 * - DELETE /artworks/{artwork}: Remove the specified artwork from storage (destroy).
 * - GET /artworks/deactivated: Display a listing of deactivated artworks (trashed).
 * - POST /artworks/restore/{artwork}: Restore the specified deactivated artwork (restore).
 * - DELETE /artworks/{artwork}/force-delete: Permanently delete the specified artwork (forceDelete).
 *
 * Controller:
 * - AdminArtworksController: Handles the logic for managing artworks in the admin panel.
 */
Route::get('/artworks', [AdminArtworksController::class, 'index'])->name('admin.artworks.index');
Route::get('/artworks/create', [AdminArtworksController::class, 'create'])->name('admin.artworks.create');
Route::post('/artworks', [AdminArtworksController::class, 'store'])->name('admin.artworks.store');
Route::get('/artworks/{artwork}/edit', [AdminArtworksController::class, 'edit'])->name('admin.artworks.edit');
Route::put('/artworks/{artwork}', [AdminArtworksController::class, 'update'])->name('admin.artworks.update');
Route::delete('/artworks/{artwork}', [AdminArtworksController::class, 'destroy'])->name('admin.artworks.destroy');

Route::get('/artworks/deactivated', [AdminArtworksController::class, 'trashed'])->name('admin.artworks.trashed');
Route::post('/artworks/restore/{artwork}', [AdminArtworksController::class, 'restore'])->name('admin.artworks.restore');
Route::delete('/artworks/{artwork}/force-delete', [AdminArtworksController::class, 'forceDelete'])->name('admin.artworks.forceDelete');
