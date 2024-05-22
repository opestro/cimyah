<?php

use Botble\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function () {
    Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers', 'prefix' => 'ecommerce'], function () {
        Route::group([
            'prefix' => 'shipping-methods',
            'permission' => 'settings.index.shipping',
            'as' => 'shipping_methods.',
        ], function () {
            Route::post('region/create', [
                'as' => 'region.create',
                'uses' => 'ShippingMethodController@postCreateRegion',
            ]);

            Route::delete('region/delete', [
                'as' => 'region.destroy',
                'uses' => 'ShippingMethodController@deleteRegion',
            ]);

            Route::delete('region/rule/delete', [
                'as' => 'region.rule.destroy',
                'uses' => 'ShippingMethodController@deleteRegionRule',
            ]);

            Route::put('region/rule/update/{id}', [
                'as' => 'region.rule.update',
                'uses' => 'ShippingMethodController@putUpdateRule',
            ])->wherePrimaryKey();

            Route::post('region/rule/create', [
                'as' => 'region.rule.create',
                'uses' => 'ShippingMethodController@postCreateRule',
            ]);

            Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
                Route::post('update', [
                    'as' => 'update',
                    'uses' => 'ShippingMethodSettingController@update',
                    'middleware' => 'preventDemo',
                ]);
            });
        });

        Route::group(['as' => 'ecommerce.'], function () {
            Route::group([
                'prefix' => 'shipping-rule-items',
                'as' => 'shipping-rule-items.',
            ], function () {
                Route::resource('', 'ShippingRuleItemController')->parameters(['' => 'item']);

                Route::get('items/{rule_id}', [
                    'as' => 'items',
                    'uses' => 'ShippingRuleItemController@items',
                    'permission' => 'ecommerce.shipping-rule-items.index',
                ])->wherePrimaryKey('rule_id');

                Route::group([
                    'as' => 'bulk-import.',
                    'prefix' => 'bulk-import',
                    'permission' => 'ecommerce.shipping-rule-items.bulk-import',
                ], function () {
                    Route::get('/', [
                        'as' => 'index',
                        'uses' => 'ShippingRuleItemController@import',
                    ]);

                    Route::post('/', [
                        'as' => 'post',
                        'uses' => 'ShippingRuleItemController@postImport',
                    ]);

                    Route::post('/download-template', [
                        'as' => 'download-template',
                        'uses' => 'ShippingRuleItemController@downloadTemplate',
                    ]);
                });
            });
        });
    });
});
