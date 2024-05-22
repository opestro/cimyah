<?php

use Botble\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\Slug\Http\Controllers'], function () {
    AdminHelper::registerRoutes(function () {
        Route::group(['prefix' => 'settings/permalink'], function () {
            Route::get('', [
                'as' => 'slug.settings',
                'uses' => 'SlugController@edit',
                'permission' => 'settings.options',
            ]);

            Route::put('', [
                'as' => 'slug.settings.update',
                'uses' => 'SlugController@update',
                'permission' => 'settings.options',
                'middleware' => 'preventDemo',
            ]);
        });
    });

    Route::group(['prefix' => 'ajax/slug', 'middleware' => ['web', 'core']], function () {
        Route::post('create', [
            'as' => 'slug.create',
            'uses' => 'SlugController@store',
        ]);
    });
});
