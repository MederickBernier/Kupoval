<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminArtistsController;

/**
 * Admin Artists Routes
 *
 * This file contains the routes for managing artists in the admin panel.
 * The routes are defined using Laravel's Route facade and are mapped to
 * the corresponding methods in the AdminArtistsController.
 *
 * Routes:
 * - GET /artists: Display a listing of the artists. (name: admin.artists.index)
 * - GET /artists/create: Show the form for creating a new artist. (name: admin.artists.create)
 * - POST /artists: Store a newly created artist in storage. (name: admin.artists.store)
 * - GET /artists/{artist}/edit: Show the form for editing the specified artist. (name: admin.artists.edit)
 * - PUT /artists/{artist}: Update the specified artist in storage. (name: admin.artists.update)
 * - DELETE /artists/{artist}: Remove the specified artist from storage. (name: admin.artists.destroy)
 * - GET /artists/deactivated: Display a listing of the deactivated artists. (name: admin.artists.trashed)
 * - POST /artists/restore/{artist}: Restore the specified deactivated artist. (name: admin.artists.restore)
 * - DELETE /artists/{artist}/force-delete: Permanently delete the specified artist. (name: admin.artists.forceDelete)
 *
 * Controller:
 * - AdminArtistsController: Handles the logic for managing artists in the admin panel.
 */
Route::get('/artists', [AdminArtistsController::class, 'index'])->name('admin.artists.index');
Route::get('/artists/create', [AdminArtistsController::class, 'create'])->name('admin.artists.create');
Route::post('/artists', [AdminArtistsController::class, 'store'])->name('admin.artists.store');
Route::get('/artists/{artist}/edit', [AdminArtistsController::class, 'edit'])->name('admin.artists.edit');
Route::put('/artists/{artist}', [AdminArtistsController::class, 'update'])->name('admin.artists.update');
Route::delete('/artists/{artist}', [AdminArtistsController::class, 'destroy'])->name('admin.artists.destroy');
Route::get('/artists/deactivated', [AdminArtistsController::class, 'trashed'])->name('admin.artists.trashed');
Route::post('/artists/restore/{artist}', [AdminArtistsController::class, 'restore'])->name('admin.artists.restore');
Route::delete('/artists/{artist}/force-delete', [AdminArtistsController::class, 'forceDelete'])->name('admin.artists.forceDelete');
