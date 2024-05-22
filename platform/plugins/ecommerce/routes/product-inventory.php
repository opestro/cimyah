<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Ecommerce\Http\Controllers\ExportProductInventoryController;
use Botble\Ecommerce\Http\Controllers\ImportProductInventoryController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function () {
    Route::group(
        [
            'namespace' => 'Botble\Ecommerce\Http\Controllers',
            'prefix' => 'ecommerce',
            'as' => 'ecommerce.',
        ],
        function () {
            Route::group([
                'prefix' => 'product-inventory',
                'as' => 'product-inventory.',
            ], function () {
                Route::match(['GET', 'POST'], '', [
                    'uses' => 'ProductInventoryController@index',
                    'as' => 'index',
                    'permission' => 'ecommerce.product-inventory.index',
                ]);

                Route::put('{product}', [
                    'uses' => 'ProductInventoryController@update',
                    'as' => 'update',
                    'permission' => 'ecommerce.product-inventory.edit',
                ])->wherePrimaryKey();
            });
        }
    );

    Route::group(['prefix' => 'tools/data-synchronize/import/product-inventory', 'as' => 'ecommerce.product-inventory.import.', 'permission' => 'ecommerce.product-inventory.import'], function () {
        Route::get('/', [ImportProductInventoryController::class, 'index'])->name('index');
        Route::post('validate', [ImportProductInventoryController::class, 'validateData'])->name('validate');
        Route::post('import', [ImportProductInventoryController::class, 'import'])->name('store');
        Route::post('download-example', [ImportProductInventoryController::class, 'downloadExample'])->name('download-example');
    });

    Route::group(['prefix' => 'tools/data-synchronize/export/product-inventory', 'as' => 'ecommerce.product-inventory.export.', 'permission' => 'ecommerce.product-inventory.export'], function () {
        Route::post('export', [ExportProductInventoryController::class, 'store'])->name('store');
    });
});
