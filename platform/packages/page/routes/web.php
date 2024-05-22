<?php

use Botble\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\Page\Http\Controllers'], function () {
    AdminHelper::registerRoutes(function () {
        Route::group(['prefix' => 'pages', 'as' => 'pages.'], function () {
            Route::resource('', 'PageController')->parameters(['' => 'page']);
        });
    });
});
