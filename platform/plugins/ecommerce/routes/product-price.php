<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Ecommerce\Http\Controllers\ExportProductPriceController;
use Botble\Ecommerce\Http\Controllers\ImportProductPriceController;
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
                'prefix' => 'product-prices',
                'as' => 'product-prices.',
            ], function () {
                Route::match(['GET', 'POST'], '', [
                    'uses' => 'ProductPriceController@index',
                    'as' => 'index',
                    'permission' => 'ecommerce.product-prices.index',
                ]);

                Route::put('{product}', [
                    'uses' => 'ProductPriceController@update',
                    'as' => 'update',
                    'permission' => 'ecommerce.product-prices.edit',
                ])->wherePrimaryKey();
            });
        }
    );

    Route::group(['prefix' => 'tools/data-synchronize/import/product-prices', 'as' => 'ecommerce.product-prices.import.', 'permission' => 'ecommerce.product-prices.import'], function () {
        Route::get('/', [ImportProductPriceController::class, 'index'])->name('index');
        Route::post('validate', [ImportProductPriceController::class, 'validateData'])->name('validate');
        Route::post('import', [ImportProductPriceController::class, 'import'])->name('store');
        Route::post('download-example', [ImportProductPriceController::class, 'downloadExample'])->name('download-example');
    });

    Route::group(['prefix' => 'tools/data-synchronize/export/product-prices', 'as' => 'ecommerce.product-prices.export.', 'permission' => 'ecommerce.product-prices.export'], function () {
        Route::post('export', [ExportProductPriceController::class, 'store'])->name('store');
    });
});
