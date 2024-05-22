<?php

use Botble\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\GetStarted\Http\Controllers'], function () {
    AdminHelper::registerRoutes(function () {
        Route::group(['prefix' => 'get-started'], function () {
            Route::post('save', [
                'as' => 'get-started.save',
                'uses' => 'GetStartedController@save',
                'permission' => false,
                'middleware' => 'preventDemo',
            ]);
        });
    });
});
