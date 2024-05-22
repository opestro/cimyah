<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Ecommerce\Models\Product;
use Botble\Slug\Facades\SlugHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function () {
    Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers', 'prefix' => 'ecommerce'], function () {
        Route::group(['prefix' => 'reviews', 'as' => 'reviews.'], function () {
            Route::match(['GET', 'POST'], '/', [
                'as' => 'index',
                'uses' => 'ReviewController@index',
                'permission' => 'reviews.index',
            ]);

            Route::get('create', [
                'as' => 'create',
                'uses' => 'ReviewController@create',
                'permission' => 'reviews.create',
            ]);

            Route::post('create', [
                'as' => 'store',
                'uses' => 'ReviewController@store',
                'permission' => 'reviews.create',
            ]);

            Route::get('/ajax-search-customers', [
                'as' => 'ajax-search-customers',
                'uses' => 'ReviewController@ajaxSearchCustomers',
                'permission' => 'reviews.create',
            ]);

            Route::get('/ajax-search-products', [
                'as' => 'ajax-search-products',
                'uses' => 'ReviewController@ajaxSearchProducts',
                'permission' => 'reviews.create',
            ]);

            Route::get('{review}', [
                'as' => 'show',
                'uses' => 'ReviewController@show',
                'permission' => 'reviews.index',
            ]);

            Route::delete('{review}', [
                'as' => 'destroy',
                'uses' => 'ReviewController@destroy',
                'permission' => 'reviews.destroy',
            ]);

            Route::post('{review}/publish', [
                'as' => 'publish',
                'uses' => 'PublishedReviewController@store',
                'permission' => 'reviews.publish',
            ]);

            Route::post('{review}/unpublish', [
                'as' => 'unpublish',
                'uses' => 'PublishedReviewController@destroy',
                'permission' => 'reviews.publish',
            ]);

            Route::post('{review}/reply', [
                'as' => 'reply',
                'uses' => 'ReviewReplyController@store',
                'permission' => 'reviews.reply',
            ]);

            Route::put('{review}/reply/{reply}', [
                'as' => 'reply.update',
                'uses' => 'ReviewReplyController@update',
                'permission' => 'reviews.reply',
            ]);

            Route::delete('{review}/reply/{reply}', [
                'as' => 'reply.destroy',
                'uses' => 'ReviewReplyController@destroy',
                'permission' => 'reviews.reply',
            ]);
        });
    });
});

Theme::registerRoutes(function () {
    Route::namespace('Botble\Ecommerce\Http\Controllers\Fronts')->group(function () {
        Route::group(['middleware' => ['customer']], function () {
            Route::post('review/create', [
                'as' => 'public.reviews.create',
                'uses' => 'ReviewController@store',
            ]);

            Route::delete('review/delete/{id}', [
                'as' => 'public.reviews.destroy',
                'uses' => 'ReviewController@destroy',
            ])->wherePrimaryKey();

            Route::get(SlugHelper::getPrefix(Product::class, 'products') . '/{slug}/review', [
                'uses' => 'ReviewController@getProductReview',
                'as' => 'public.product.review',
                'middleware' => 'customer',
            ]);
        });

        Route::get('ajax/reviews/{id}', [
            'uses' => 'ReviewController@ajaxReviews',
            'as' => 'public.ajax.reviews',
        ])->wherePrimaryKey();
    });
});
