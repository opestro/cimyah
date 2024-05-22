<?php

use Botble\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\SocialLogin\Http\Controllers'], function () {
    AdminHelper::registerRoutes(function () {
        Route::group(['prefix' => 'settings'], function () {
            Route::get('social-login', [
                'as' => 'social-login.settings',
                'uses' => 'Settings\SocialLoginSettingController@edit',
            ]);

            Route::put('social-login', [
                'as' => 'social-login.settings.update',
                'uses' => 'Settings\SocialLoginSettingController@update',
                'permission' => 'social-login.settings',
            ]);
        });
    });

    Route::group(['middleware' => ['web', 'core']], function () {
        Route::get('auth/{provider}', [
            'as' => 'auth.social',
            'uses' => 'SocialLoginController@redirectToProvider',
        ]);

        Route::get('auth/callback/{provider}', [
            'as' => 'auth.social.callback',
            'uses' => 'SocialLoginController@handleProviderCallback',
        ]);
    });

    Route::post('facebook-data-deletion-request-callback', [
        'as' => 'facebook-data-deletion-request-callback',
        'uses' => 'FacebookDataDeletionRequestCallbackController@store',
    ]);

    Route::get('facebook-deletion-status/{id}', [
        'as' => 'facebook-deletion-status',
        'uses' => 'FacebookDataDeletionRequestCallbackController@show',
    ]);
});
