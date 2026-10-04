<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\StoreController;
use App\Models\Product;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [StoreController::class, 'home'])->name('home');

Route::get('/language/{locale}', [LocaleController::class, 'update'])
    ->name('locale.update');

Route::get('/products', [StoreController::class, 'products'])
    ->name('products.index');

Route::get('/products/{product:slug}', [StoreController::class, 'product'])
    ->name('products.show');

Route::get('/review/checkout/{product:slug}', function (Product $product) {
    abort_if(config('company.live_payment_enabled'), 404);
    abort_unless($product->catalog_visible, 404);

    return view('store.review-checkout', [
        'product' => $product,
    ]);
})->name('checkout.review');

Route::get('/checkout/{product:slug}', [CheckoutController::class, 'show'])
    ->name('checkout.show');

Route::post('/checkout/{product:slug}', [CheckoutController::class, 'store'])
    ->middleware('throttle:8,1')
    ->name('checkout.store');

Route::get('/track-order', [CheckoutController::class, 'trackingForm'])
    ->name('orders.track');

Route::post('/track-order', [CheckoutController::class, 'tracking'])
    ->middleware('throttle:10,1')
    ->name('orders.track.submit');

Route::get('/orders/{order:order_number}', [CheckoutController::class, 'success'])
    ->name('orders.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:6,1')
        ->name('login.submit');

    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:6,1')
        ->name('register.submit');

    Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])
        ->name('password.request');

    Route::post('/forgot-password', [PasswordResetController::class, 'sendLink'])
        ->middleware('throttle:3,1')
        ->name('password.email');

    Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])
        ->name('password.reset');

    Route::post('/reset-password', [PasswordResetController::class, 'reset'])
        ->middleware('throttle:5,1')
        ->name('password.update');

    Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])
        ->middleware('throttle:10,1')
        ->name('auth.google.redirect');

    Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])
        ->middleware('throttle:10,1')
        ->name('auth.google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->route('account.index');
})
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('status', 'Verification link sent.');
})
    ->middleware(['auth', 'throttle:3,1'])
    ->name('verification.send');

Route::get('/account', [AccountController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('account.index');

Route::view('/help', 'help.index')->name('help.index');
Route::view('/terms', 'legal.terms')->name('legal.terms');
Route::view('/privacy', 'legal.privacy')->name('legal.privacy');
Route::view('/refund-policy', 'legal.refund')->name('legal.refund');
Route::view('/delivery-policy', 'legal.delivery')->name('legal.delivery');
Route::view('/security', 'legal.security')->name('legal.security');
Route::view('/contact', 'legal.contact')->name('contact');
