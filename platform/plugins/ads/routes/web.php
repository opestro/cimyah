<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\Ads\Http\Controllers'], function () {
    AdminHelper::registerRoutes(function () {
        Route::group(['prefix' => 'ads', 'as' => 'ads.'], function () {
            Route::resource('', 'AdsController')->parameters(['' => 'ads']);
        });

        Route::group(['prefix' => 'settings'], function () {
            Route::get('ads', [
                'as' => 'ads.settings',
                'uses' => 'Settings\AdsSettingController@edit',
            ]);

            Route::put('ads', [
                'as' => 'ads.settings.update',
                'uses' => 'Settings\AdsSettingController@update',
                'permission' => 'ads.settings',
            ]);
        });
    });

    if (defined('THEME_MODULE_SCREEN_NAME')) {
        Theme::registerRoutes(function () {
            Route::get('ads-click/{key}', [
                'as' => 'public.ads-click',
                'uses' => 'PublicController@getAdsClick',
            ]);

            Route::get('ac-{randomHash}/{adsKey}', [
                'as' => 'public.ads-click.alternative',
                'uses' => 'PublicController@getAdsClickAlternative',
            ]);

            Route::get('ac-{randomHash}/{adsKey}/{size}/{hashName}', [
                'as' => 'public.ads-click.image',
                'uses' => 'PublicController@getAdsImage',
            ]);
        });
    }
});
