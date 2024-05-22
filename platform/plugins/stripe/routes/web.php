<?php

use Botble\Stripe\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

Route::prefix('payment/stripe')
    ->name('payments.stripe.')
    ->group(function () {
        Route::post('webhook', [StripeController::class, 'webhook'])->name('webhook');

        Route::middleware(['web', 'core'])->group(function () {
            Route::get('success', [StripeController::class, 'success'])->name('success');
            Route::get('error', [StripeController::class, 'error'])->name('error');
        });
    });
