<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\BioController;

// Pages publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/events', [HomeController::class, 'events'])->name('events');
Route::get('/event/{event}', [EventController::class, 'show'])->name('event.show');

// Bio Routes
Route::get('/bio', [BioController::class, 'index'])->name('bio.index');
Route::get('/bio/artist/{artist:slug}', [BioController::class, 'show'])->name('bio.show');


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

// Charger les routes Admin
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    require_once __DIR__ . '/admin/dashboard.php';
    require_once __DIR__ . '/admin/users.php';
    require_once __DIR__ . '/admin/settings.php';
    require_once __DIR__ . '/admin/events.php';
    require_once __DIR__ . '/admin/artworks.php';
    require_once __DIR__ . '/admin/categories.php';
    require_once __DIR__ . '/admin/artists.php';
    require_once __DIR__ . '/admin/orders.php';
});
