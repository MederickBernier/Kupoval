<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/bio', [HomeController::class, 'bio'])->name('bio');
Route::get('/about',[HomeController::class, 'about'])->name('about');
Route::get('/events',[HomeController::class, 'events'])->name('events');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

Route::middleware(['auth', 'verified'])->group(function(){
    Route::get('/dashboard', function(){
        return view('dashboard');
    })->name('dashboard');

    Route::get('/user-profile', function(){
        return view('public.user.profile');
    })->name('user_profile');
});

//Email Validation Routes
Route::get('/email/verify',[VerificationController::class,'notice'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class,'verify'])->middleware(['auth','signed'])->name('verification.verify');
Route::post('/email/verification-notification', [VerificationController::class,'send'])->middleware(['auth','throttle:6,1'])->name('verification.send');

//Login and Register Routes

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/lang/{locale}', function($locale){
    if(in_array($locale, ['en','fr'])){
        Session::put('locale', $locale);
        Cookie::queue('locale', $locale, 60*24*30);
    }
    return redirect()->back();
})->name('setLocale');
