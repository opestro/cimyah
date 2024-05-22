<?php

use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Controllers\Fronts\WishlistController;
use Botble\Ecommerce\Http\Middleware\CheckWishlistEnabledMiddleware;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

Theme::registerRoutes(function () {
    Route::middleware(CheckWishlistEnabledMiddleware::class)
        ->controller(WishlistController::class)
        ->prefix(EcommerceHelper::getPageSlug('wishlist'))
        ->name('public.')
        ->group(function () {
            Route::get('/', 'index')->name('wishlist');
            Route::post('{productId}', 'store')->name('wishlist.add')->wherePrimaryKey('productId');
            Route::delete('{productId}', 'destroy')->name('wishlist.remove')->wherePrimaryKey('productId');
        });
});
