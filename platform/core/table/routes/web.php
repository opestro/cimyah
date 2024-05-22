<?php

use Botble\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function () {
    require __DIR__ . '/web-actions.php';

    Route::group([
        'prefix' => 'tables',
        'permission' => false,
        'as' => 'table.',
        'namespace' => 'Botble\Table\Http\Controllers',
    ], function () {
        Route::put('columns-visibility', [
            'as' => 'update-columns-visibility',
            'uses' => 'TableColumnVisibilityController@update',
            'permission' => false,
            'middleware' => 'preventDemo',
        ]);
    });
});
