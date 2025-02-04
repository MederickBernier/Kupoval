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

    // Routes HTMX pour édition rapide
    Route::get('/user/edit-field/{field}', [UserProfileController::class, 'editField'])->name('user.edit-field');
    Route::post('/user/update-field/{field}', [UserProfileController::class, 'updateField'])->name('user.update-field');
});

// Admin (nécessite email vérifié)
Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users.list');
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings.list');
    })->middleware('verified');

// Changement de langue
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'fr'])) {
        Session::put('locale', $locale);
        Cookie::queue('locale', $locale, 60 * 24 * 30);
    }
    return redirect()->back();
})->name('setLocale');
