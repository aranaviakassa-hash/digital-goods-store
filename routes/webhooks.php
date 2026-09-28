<?php

use App\Http\Controllers\PaymentWebhookController;
use Illuminate\Support\Facades\Route;

Route::post(
    '/payment',
    [PaymentWebhookController::class, 'handle']
)->name('payment');