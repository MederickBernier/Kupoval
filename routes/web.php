<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminEventsController;
use App\Http\Controllers\Admin\AdminArtworksController;
use App\Http\Controllers\LanguageController;

// Pages publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/bio', [HomeController::class, 'bio'])->name('bio');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/events', [HomeController::class, 'events'])->name('events');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

// Authentification (guest seulement)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Vérification d'email
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [VerificationController::class, 'send'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');
});

// Profil utilisateur - Nécessite un email vérifié
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user-profile', [UserProfileController::class, 'profile'])->name('user.profile');
    Route::put('/user-profile/update', [UserProfileController::class, 'updateProfile'])->name('user.profile.update');

    Route::get('/user/edit-field/{field}', [UserProfileController::class, 'editField'])->name('user.edit-field');
    Route::post('/user/update-field/{field}', [UserProfileController::class, 'updateField'])->name('user.update-field');
});

// Lang Switching Route
Route::post('/lang-switch', [LanguageController::class, 'switch'])->middleware('auth')->name('lang.switch');

// Admin (nécessite email vérifié)
Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        // Users Routes
        Route::get('/users', [AdminUsersController::class, 'index'])->name('admin.users.list');
        Route::delete('/users/{id}', [AdminUsersController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('/users/deactivated', [AdminUsersController::class, 'trashed'])->name('admin.users.trashed');
        Route::post('/users/restore/{id}', [AdminUsersController::class, 'restore'])->name('admin.users.restore');

        // Settings Routes
        Route::get('/settings', [AdminSettingsController::class, 'index'])->name('admin.settings.list');
        Route::post('/settings', [AdminSettingsController::class, 'store'])->name('admin.settings.store');
        Route::put('/settings/{id}', [AdminSettingsController::class, 'update'])->name('admin.settings.update');
        Route::delete('/settings/{id}', [AdminSettingsController::class, 'destroy'])->name('admin.settings.destroy');

        // Events Routes
        Route::get('/events', [AdminEventsController::class,'index'])->name('admin.events.list');
        Route::post('/events', [AdminEventsController::class, 'store'])->name('admin.events.store');
        Route::put('/events/{id}', [AdminEventsController::class, 'update'])->name('admin.events.update');
        Route::delete('/events/{id}', [AdminEventsController::class, 'destroy'])->name('admin.events.destroy');

        // Gestion des événements supprimés
        Route::get('/events/deactivated', [AdminEventsController::class, 'trashed'])->name('admin.events.trashed');
        Route::post('/events/restore/{id}', [AdminEventsController::class, 'restore'])->name('admin.events.restore');
        Route::delete('/events/force-delete/{id}', [AdminEventsController::class, 'forceDelete'])->name('admin.events.force-delete');

        // Afficher la liste des œuvres d'art
        Route::get('artworks', [AdminArtworksController::class, 'index'])->name('admin.artworks.index');
        Route::get('artworks/create', [AdminArtworksController::class, 'create'])->name('admin.artworks.create');
        Route::post('artworks', [AdminArtworksController::class, 'store'])->name('admin.artworks.store');
        Route::get('artworks/{id}/edit', [AdminArtworksController::class, 'edit'])->name('admin.artworks.edit');
        Route::put('artworks/{id}', [AdminArtworksController::class, 'update'])->name('admin.artworks.update');
        Route::delete('artworks/{id}', [AdminArtworksController::class, 'destroy'])->name('admin.artworks.destroy');
        Route::get('artworks/deactivated', [AdminArtworksController::class, 'trashed'])->name('admin.artworks.trashed');
        Route::post('artworks/{id}/restore', [AdminArtworksController::class, 'restore'])->name('admin.artworks.restore');
        Route::delete('artworks/{id}/force-delete', [AdminArtworksController::class, 'forceDelete'])->name('admin.artworks.forceDelete');
    })->middleware('verified');

