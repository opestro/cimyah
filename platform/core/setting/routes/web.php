<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Setting\Http\Controllers\EmailRuleSettingController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\Setting\Http\Controllers'], function () {
    AdminHelper::registerRoutes(function () {
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'HomeSettingController@index',
            ]);

            Route::get('options', [
                'as' => 'options',
                'uses' => 'HomeSettingController@index',
                'permission' => 'settings.index',
            ]);

            Route::prefix('general')->group(function () {
                Route::get('/', [
                    'as' => 'general',
                    'uses' => 'GeneralSettingController@edit',
                    'permission' => 'settings.options',
                ]);

                Route::put('/', [
                    'as' => 'general.update',
                    'uses' => 'GeneralSettingController@update',
                    'permission' => 'settings.options',
                ]);
            });

            Route::prefix('admin-appearance')->group(function () {
                Route::get('/', [
                    'as' => 'admin-appearance',
                    'uses' => 'AdminAppearanceSettingController@index',
                ]);

                Route::put('/', [
                    'as' => 'admin-appearance.update',
                    'uses' => 'AdminAppearanceSettingController@update',
                    'permission' => 'settings.admin-appearance',
                ]);
            });

            Route::prefix('cache')->group(function () {
                Route::get('/', [
                    'as' => 'cache',
                    'uses' => 'CacheSettingController@edit',
                ]);

                Route::put('cache', [
                    'as' => 'cache.update',
                    'uses' => 'CacheSettingController@update',
                    'permission' => 'settings.cache',
                ]);
            });

            Route::prefix('datatables')->group(function () {
                Route::get('/', [
                    'as' => 'datatables',
                    'uses' => 'DataTableSettingController@edit',
                ]);

                Route::put('/', [
                    'as' => 'datatables.update',
                    'uses' => 'DataTableSettingController@update',
                    'permission' => 'settings.datatables',
                ]);
            });

            Route::prefix('media')->group(function () {
                Route::get('/', [
                    'as' => 'media',
                    'uses' => 'MediaSettingController@edit',
                ]);

                Route::put('/', [
                    'as' => 'media.update',
                    'uses' => 'MediaSettingController@update',
                    'permission' => 'settings.media',
                    'middleware' => 'preventDemo',
                ]);

                Route::post('generate-thumbnails', [
                    'as' => 'media.generate-thumbnails',
                    'uses' => 'MediaSettingController@generateThumbnails',
                    'permission' => 'settings.media',
                    'middleware' => 'preventDemo',
                ]);
            });

            Route::prefix('license')->name('license.')->group(function () {
                /**
                 * @deprecated
                 */
                Route::get('verify/old', [
                    'as' => 'verify',
                    'uses' => 'GeneralSettingController@getVerifyLicense',
                    'permission' => false,
                ]);

                Route::get('verify', [
                    'as' => 'verify.index',
                    'uses' => 'GeneralSettingController@getVerifyLicense',
                    'permission' => false,
                ]);

                Route::post('activate', [
                    'as' => 'activate',
                    'uses' => 'GeneralSettingController@activateLicense',
                    'middleware' => 'preventDemo',
                    'permission' => 'core.manage.license',
                ]);

                Route::post('deactivate', [
                    'as' => 'deactivate',
                    'uses' => 'GeneralSettingController@deactivateLicense',
                    'middleware' => 'preventDemo',
                    'permission' => 'core.manage.license',
                ]);

                Route::post('reset', [
                    'as' => 'reset',
                    'uses' => 'GeneralSettingController@resetLicense',
                    'middleware' => 'preventDemo',
                    'permission' => 'core.manage.license',
                ]);
            });

            Route::group(['prefix' => 'email', 'permission' => 'settings.email'], function () {
                Route::get('/', [
                    'as' => 'email',
                    'uses' => 'EmailSettingController@edit',
                ]);

                Route::put('/', [
                    'as' => 'email.update',
                    'uses' => 'EmailSettingController@update',
                    'middleware' => 'preventDemo',
                ]);

                Route::post('test/send', [
                    'as' => 'email.test.send',
                    'uses' => 'EmailTestController@__invoke',
                ]);

                Route::prefix('templates')->name('email.')->group(function () {
                    Route::get('/', [
                        'as' => 'template',
                        'uses' => 'EmailTemplateSettingController@index',
                    ]);

                    Route::put('', [
                        'as' => 'template.update-settings',
                        'uses' => 'EmailTemplateSettingController@update',
                    ]);

                    Route::prefix('{type}/{module}/{template}')->name('template.')->group(function () {
                        Route::post('status', [
                            'as' => 'status.update',
                            'uses' => 'EmailTemplateStatusController@__invoke',
                            'middleware' => 'preventDemo',
                        ]);

                        Route::get('edit', [
                            'as' => 'edit',
                            'uses' => 'EmailTemplateController@index',
                        ]);

                        Route::put('/', [
                            'as' => 'update',
                            'uses' => 'EmailTemplateController@update',
                            'middleware' => 'preventDemo',
                        ]);

                        Route::post('restore', [
                            'as' => 'restore',
                            'uses' => 'EmailTemplateRestoreController@__invoke',
                            'middleware' => 'preventDemo',
                        ]);

                        Route::match(['POST', 'GET'], 'preview', [
                            'as' => 'preview',
                            'uses' => 'EmailTemplatePreviewController@__invoke',
                        ]);

                        Route::get('iframe', [
                            'as' => 'iframe',
                            'uses' => 'EmailTemplateIframeController@__invoke',
                        ]);
                    });
                });

                Route::get('rules', [EmailRuleSettingController::class, 'edit'])
                    ->name('email.rules');

                Route::put('rules', [
                    'as' => 'rules.update',
                    'uses' => 'EmailRuleSettingController@update',
                    'permission' => 'settings.email.rules',
                ]);
            });
        });
    });
});
