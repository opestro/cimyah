<?php

use Botble\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function () {
    Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers', 'prefix' => 'ecommerce'], function () {
        Route::group(['prefix' => 'taxes', 'as' => 'tax.'], function () {
            Route::resource('', 'TaxController')->parameters(['' => 'tax']);

            Route::group(['prefix' => '{tax}/rules', 'as' => 'rule.'], function () {
                Route::resource('', 'TaxRuleController')
                    ->parameters(['' => 'rule'])
                    ->only(['index']);
            });

            Route::group(['prefix' => 'rules', 'as' => 'rule.'], function () {
                Route::resource('', 'TaxRuleController')
                    ->parameters(['' => 'rule'])
                    ->except(['index']);
            });
        });
    });
});
