<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StoreController::class, 'home'])
    ->name('home');

Route::get('/products', [StoreController::class, 'products'])
    ->name('products.index');

Route::get('/products/{product:slug}', [StoreController::class, 'product'])
    ->name('products.show');

Route::get('/checkout/{product:slug}', [CheckoutController::class, 'show'])
    ->name('checkout.show');

Route::post('/checkout/{product:slug}', [CheckoutController::class, 'store'])
    ->name('checkout.store');

Route::get('/track-order', [CheckoutController::class, 'trackingForm'])
    ->name('orders.track');

Route::post('/track-order', [CheckoutController::class, 'tracking'])
    ->name('orders.track.submit');

Route::get(
    '/orders/{order:order_number}',
    [CheckoutController::class, 'success']
)->name('orders.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');

    Route::get('/register', [AuthController::class, 'registerForm'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::view('/terms', 'legal.terms')
    ->name('legal.terms');

Route::view('/privacy', 'legal.privacy')
    ->name('legal.privacy');

Route::view('/refund-policy', 'legal.refund')
    ->name('legal.refund');

Route::view('/delivery-policy', 'legal.delivery')
    ->name('legal.delivery');

Route::view('/security', 'legal.security')
    ->name('legal.security');

Route::view('/contact', 'legal.contact')
    ->name('contact');