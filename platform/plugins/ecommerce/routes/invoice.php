<?php

use Botble\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function () {
    Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers', 'prefix' => 'ecommerce'], function () {
        Route::group(['prefix' => 'invoices', 'as' => 'ecommerce.invoice.'], function () {
            Route::resource('', 'InvoiceController')
                ->parameters(['' => 'invoice'])
                ->except(['create', 'store', 'update']);

            Route::get('generate-invoice/{invoice}', [
                'as' => 'generate-invoice',
                'uses' => 'InvoiceController@getGenerateInvoice',
                'permission' => 'ecommerce.invoice.edit',
            ])->wherePrimaryKey('invoice');

            Route::get('generate-invoices', [
                'as' => 'generate-invoices',
                'uses' => 'InvoiceController@generateInvoices',
                'permission' => 'ecommerce.invoice.edit',
            ]);
        });
    });
});
