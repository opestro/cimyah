<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Blog\Http\Controllers\ExportPostController;
use Botble\Blog\Http\Controllers\ImportPostController;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\Blog\Http\Controllers'], function () {
    AdminHelper::registerRoutes(function () {
        Route::group(['prefix' => 'blog'], function () {
            Route::group(['prefix' => 'posts', 'as' => 'posts.'], function () {
                Route::resource('', 'PostController')
                    ->parameters(['' => 'post']);

                Route::get('widgets/recent-posts', [
                    'as' => 'widget.recent-posts',
                    'uses' => 'PostController@getWidgetRecentPosts',
                    'permission' => 'posts.index',
                ]);
            });

            Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
                Route::resource('', 'CategoryController')
                    ->parameters(['' => 'category']);

                Route::put('update-tree', [
                    'as' => 'update-tree',
                    'uses' => 'CategoryController@updateTree',
                    'permission' => 'categories.index',
                ]);
            });

            Route::group(['prefix' => 'tags', 'as' => 'tags.'], function () {
                Route::resource('', 'TagController')
                    ->parameters(['' => 'tag']);

                Route::get('all', [
                    'as' => 'all',
                    'uses' => 'TagController@getAllTags',
                    'permission' => 'tags.index',
                ]);
            });

            Route::prefix('tools/data-synchronize')->name('tools.data-synchronize.')->group(function () {
                Route::prefix('export')->name('export.')->group(function () {
                    Route::group(['prefix' => 'posts', 'as' => 'posts.', 'permission' => 'posts.export'], function () {
                        Route::get('/', [ExportPostController::class, 'index'])->name('index');
                        Route::post('/', [ExportPostController::class, 'store'])->name('store');
                    });
                });

                Route::prefix('import')->name('import.')->group(function () {
                    Route::group(['prefix' => 'posts', 'as' => 'posts.', 'permission' => 'posts.import'], function () {
                        Route::get('/', [ImportPostController::class, 'index'])->name('index');
                        Route::post('/', [ImportPostController::class, 'import'])->name('store');
                        Route::post('validate', [ImportPostController::class, 'validateData'])->name('validate');
                        Route::post('download-example', [ImportPostController::class, 'downloadExample'])->name('download-example');
                    });
                });
            });
        });

        Route::group(['prefix' => 'settings/blog', 'as' => 'blog.settings', 'permission' => 'blog.settings'], function () {
            Route::get('/', [
                'uses' => 'Settings\BlogSettingController@edit',
            ]);

            Route::put('/', [
                'as' => '.update',
                'uses' => 'Settings\BlogSettingController@update',
            ]);
        });
    });

    if (defined('THEME_MODULE_SCREEN_NAME')) {
        Theme::registerRoutes(function () {
            Route::get('search', [
                'as' => 'public.search',
                'uses' => 'PublicController@getSearch',
            ]);
        });
    }
});
