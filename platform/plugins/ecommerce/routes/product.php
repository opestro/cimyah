<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Ecommerce\Http\Controllers\ExportProductController;
use Botble\Ecommerce\Http\Controllers\ImportProductController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function () {
    Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers', 'prefix' => 'ecommerce'], function () {
        Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
            Route::resource('', 'ProductController')
                ->parameters(['' => 'product']);

            Route::post('{product}/duplicate', [
                'as' => 'duplicate',
                'uses' => 'ProductController@duplicate',
                'permission' => 'products.duplicate',
            ]);

            Route::post('add-attribute-to-product/{id}', [
                'as' => 'add-attribute-to-product',
                'uses' => 'ProductController@postAddAttributeToProduct',
                'permission' => 'products.edit',
            ])->wherePrimaryKey();

            Route::post('delete-version/{id}', [
                'as' => 'delete-version',
                'uses' => 'ProductController@deleteVersion',
                'permission' => 'products.edit',
            ])->wherePrimaryKey();

            Route::delete('items/delete-versions', [
                'as' => 'delete-versions',
                'uses' => 'ProductController@deleteVersions',
                'permission' => 'products.edit',
            ]);

            Route::post('add-version/{id}', [
                'as' => 'add-version',
                'uses' => 'ProductController@postAddVersion',
                'permission' => 'products.edit',
            ])->wherePrimaryKey();

            Route::get('get-version-form/{id?}', [
                'as' => 'get-version-form',
                'uses' => 'ProductController@getVersionForm',
                'permission' => 'products.edit',
            ]);

            Route::post('update-version/{id}', [
                'as' => 'update-version',
                'uses' => 'ProductController@postUpdateVersion',
                'permission' => 'products.edit',
            ])->wherePrimaryKey();

            Route::post('generate-all-version/{id}', [
                'as' => 'generate-all-versions',
                'uses' => 'ProductController@postGenerateAllVersions',
                'permission' => 'products.edit',
            ])->wherePrimaryKey();

            Route::post('store-related-attributes/{id}', [
                'as' => 'store-related-attributes',
                'uses' => 'ProductController@postStoreRelatedAttributes',
                'permission' => 'products.edit',
            ])->wherePrimaryKey();

            Route::post('save-all-version/{id}', [
                'as' => 'save-all-versions',
                'uses' => 'ProductController@postSaveAllVersions',
                'permission' => 'products.edit',
            ])->wherePrimaryKey();

            Route::get('get-list-product-for-search', [
                'as' => 'get-list-product-for-search',
                'uses' => 'ProductController@getListProductForSearch',
                'permission' => 'products.edit',
            ]);

            Route::get('get-relations-box/{id?}', [
                'as' => 'get-relations-boxes',
                'uses' => 'ProductController@getRelationBoxes',
                'permission' => 'products.edit',
            ]);

            Route::get('get-list-products-for-select', [
                'as' => 'get-list-products-for-select',
                'uses' => 'ProductController@getListProductForSelect',
                'permission' => 'products.index',
            ]);

            Route::post('create-product-when-creating-order', [
                'as' => 'create-product-when-creating-order',
                'uses' => 'ProductController@postCreateProductWhenCreatingOrder',
                'permission' => 'products.create',
            ]);

            Route::get('get-all-products-and-variations', [
                'as' => 'get-all-products-and-variations',
                'uses' => 'ProductController@getAllProductAndVariations',
                'permission' => 'products.index',
            ]);

            Route::post('update-order-by', [
                'as' => 'update-order-by',
                'uses' => 'ProductController@postUpdateOrderby',
                'permission' => 'products.edit',
            ]);

            Route::post('product-variations/{product}', [
                'as' => 'product-variations',
                'uses' => 'ProductController@getProductVariations',
                'permission' => 'products.index',
            ])->wherePrimaryKey();

            Route::get('product-attribute-sets/{id?}', [
                'as' => 'product-attribute-sets',
                'uses' => 'ProductController@getProductAttributeSets',
                'permission' => 'products.index',
            ])->wherePrimaryKey();

            Route::post('set-default-product-variation/{productVariation}', [
                'as' => 'set-default-product-variation',
                'uses' => 'ProductController@setDefaultProductVariation',
                'permission' => 'products.edit',
            ])->wherePrimaryKey();
        });
    });

    Route::prefix('tools/data-synchronize')->name('tools.data-synchronize.')->group(function () {
        Route::prefix('export')->name('export.')->group(function () {
            Route::group(['prefix' => 'products', 'as' => 'products.', 'permission' => 'ecommerce.export.products.index'], function () {
                Route::get('/', [ExportProductController::class, 'index'])->name('index');
                Route::post('/', [ExportProductController::class, 'store'])->name('store');
            });
        });

        Route::prefix('import')->name('import.')->group(function () {
            Route::group(['prefix' => 'products', 'as' => 'products.', 'permission' => 'ecommerce.import.products.index'], function () {
                Route::get('/', [ImportProductController::class, 'index'])->name('index');
                Route::post('validate', [ImportProductController::class, 'validateData'])->name('validate');
                Route::post('import', [ImportProductController::class, 'import'])->name('store');
                Route::post('download-example', [ImportProductController::class, 'downloadExample'])->name('download-example');
            });
        });
    });
});
