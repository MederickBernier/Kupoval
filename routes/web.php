<?php

use App\Http\Controllers\ArtworkController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\BioController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Webhooks\StripeWebhookController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\WishlistController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Spatie\Sitemap\SitemapGenerator;

// Pages publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/cart', [ShopController::class, 'cart'])->name('shop.cart');
Route::get('/artwork/{artwork}', [ArtworkController::class, 'show'])->name('artwork.show');
Route::get('/events', [HomeController::class, 'events'])->name('events');
Route::get('/event/{event}', [EventController::class, 'show'])->name('event.show');
Route::delete('/wishlist/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');


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

// Stripe webhook route
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->withoutMiddleware([VerifyCsrfToken::class]);

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
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [InvoiceController::class, 'generateInvoice'])->name('orders.invoice');
});

// Lang Switching Route
Route::post('/lang-switch', [LanguageController::class, 'switch'])->middleware('auth')->name('lang.switch');

// Checkout routes
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'createCheckoutSession'])->name('checkout');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
    Route::get('/checkout/confirmation', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');
    Route::post('/checkout/apply-promo', [CheckoutController::class, 'applyPromoCode'])->name('checkout.applyPromo');
    Route::post('/checkout/remove-promo', [CheckoutController::class, 'removePromoCode'])->name('checkout.removePromo');
    Route::post('/checkout/update-shipping', [CheckoutController::class, 'updateShipping'])->name('checkout.updateShipping');
    Route::post('/checkout/store-session', [CheckoutController::class, 'storeSession'])->name('checkout.storeSession');
});

// Email Routes (for testing and manual triggering)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/email/test-verification', [EmailController::class, 'sendVerificationEmail'])->name('email.test.verification');
    Route::get('/email/test-password-reset', [EmailController::class, 'sendPasswordResetEmail'])->name('email.test.password_reset');
    Route::get('/email/test-order-confirmation/{order}', [EmailController::class, 'sendOrderConfirmationEmail'])->name('email.test.order_confirmation');
    Route::get('/email/test-payment-receipt/{payment}', [EmailController::class, 'sendPaymentReceiptEmail'])->name('email.test.payment_receipt');
    Route::get('/email/test-shipping-notification/{order}', [EmailController::class, 'sendShippingNotificationEmail'])->name('email.test.shipping_notification');
    Route::get('/email/test-refund-confirmation/{payment}', [EmailController::class, 'sendRefundConfirmationEmail'])->name('email.test.refund_confirmation');
});

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
    require_once __DIR__ . '/admin/promotions.php';
});
