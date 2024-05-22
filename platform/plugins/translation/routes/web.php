<?php

use Botble\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\Translation\Http\Controllers'], function () {
    AdminHelper::registerRoutes(function () {
        Route::group(['prefix' => 'translations'], function () {
            Route::group(['prefix' => 'locales', 'permission' => 'translations.locales', ], function () {
                Route::get('', [
                    'as' => 'translations.locales',
                    'uses' => 'LocaleController@index',
                ]);

                Route::post('', [
                    'as' => 'translations.locales.post',
                    'uses' => 'LocaleController@store',
                    'middleware' => 'preventDemo',
                ]);

                Route::delete('{locale}', [
                    'as' => 'translations.locales.delete',
                    'uses' => 'LocaleController@destroy',
                    'middleware' => 'preventDemo',
                ]);

                Route::get('download/{locale}', [
                    'as' => 'translations.locales.download',
                    'uses' => 'LocaleController@download',
                    'middleware' => 'preventDemo',
                ]);
            });

            Route::group(['permission' => 'translations.index'], function () {
                Route::match(['GET', 'POST'], '', [
                    'as' => 'translations.index',
                    'uses' => 'TranslationController@index',
                ]);

                Route::post('edit', [
                    'as' => 'translations.group.edit',
                    'uses' => 'TranslationController@update',
                    'middleware' => 'preventDemo',
                ]);
            });

            Route::group(['prefix' => 'theme', 'permission' => 'translations.theme-translations'], function () {
                Route::match(['GET', 'POST'], '', [
                    'as' => 'translations.theme-translations',
                    'uses' => 'ThemeTranslationController@index',
                ]);

                Route::post('post', [
                    'as' => 'translations.theme-translations.post',
                    'uses' => 'ThemeTranslationController@update',
                    'middleware' => 'preventDemo',
                ]);

                Route::post('re-import', [
                    'as' => 'translations.theme-translations.re-import',
                    'uses' => 'ReImportThemeTranslationController@__invoke',
                    'middleware' => 'preventDemo',
                ]);
            });
        });
    });
});
