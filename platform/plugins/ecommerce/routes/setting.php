<?php

use Botble\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function () {
    Route::group([
        'namespace' => 'Botble\Ecommerce\Http\Controllers\Settings',
    ], function () {
        Route::group(['prefix' => 'ecommerce'], function () {
            Route::prefix('settings')->group(function () {
                Route::get('general', [
                    'as' => 'ecommerce.settings.general',
                    'uses' => 'GeneralSettingController@edit',
                ]);

                Route::put('general', [
                    'as' => 'ecommerce.settings.general.update',
                    'uses' => 'GeneralSettingController@update',
                    'permission' => 'ecommerce.settings.general',
                ]);

                Route::get('currencies', [
                    'as' => 'ecommerce.settings.currencies',
                    'uses' => 'CurrencySettingController@index',
                ]);

                Route::put('currencies', [
                    'as' => 'ecommerce.settings.currencies.update',
                    'uses' => 'CurrencySettingController@update',
                    'permission' => 'ecommerce.settings.currencies',
                ]);

                Route::get('store-locators', [
                    'as' => 'ecommerce.settings.store-locators',
                    'uses' => 'StoreLocatorSettingController@index',
                ]);

                Route::get('products', [
                    'as' => 'ecommerce.settings.products',
                    'uses' => 'ProductSettingController@edit',
                ]);

                Route::put('products', [
                    'as' => 'ecommerce.settings.products.update',
                    'uses' => 'ProductSettingController@update',
                    'permission' => 'ecommerce.settings.products',
                ]);

                Route::get('product-search', [
                    'as' => 'ecommerce.settings.product-search',
                    'uses' => 'ProductSearchSettingController@edit',
                ]);

                Route::put('product-search', [
                    'as' => 'ecommerce.settings.product-search.update',
                    'uses' => 'ProductSearchSettingController@update',
                    'permission' => 'ecommerce.settings.product-search',
                ]);

                Route::get('digital-products', [
                    'as' => 'ecommerce.settings.digital-products',
                    'uses' => 'DigitalProductSettingController@edit',
                ]);

                Route::put('digital-products', [
                    'as' => 'ecommerce.settings.digital-products.update',
                    'uses' => 'DigitalProductSettingController@update',
                    'permission' => 'ecommerce.settings.digital-products',
                ]);

                Route::get('product-reviews', [
                    'as' => 'ecommerce.settings.product-reviews',
                    'uses' => 'ProductReviewSettingController@edit',
                ]);

                Route::put('product-reviews', [
                    'as' => 'ecommerce.settings.product-reviews.update',
                    'uses' => 'ProductReviewSettingController@update',
                    'permission' => 'ecommerce.settings.product-reviews',
                ]);

                Route::get('shopping', [
                    'as' => 'ecommerce.settings.shopping',
                    'uses' => 'ShoppingSettingController@edit',
                ]);

                Route::put('shopping', [
                    'as' => 'ecommerce.settings.shopping.update',
                    'uses' => 'ShoppingSettingController@update',
                    'permission' => 'ecommerce.settings.shopping',
                ]);

                Route::get('checkout', [
                    'as' => 'ecommerce.settings.checkout',
                    'uses' => 'CheckoutSettingController@edit',
                ]);

                Route::put('checkout', [
                    'as' => 'ecommerce.settings.checkout.update',
                    'uses' => 'CheckoutSettingController@update',
                    'permission' => 'ecommerce.settings.checkout',
                ]);

                Route::get('return', [
                    'as' => 'ecommerce.settings.return',
                    'uses' => 'ReturnSettingController@edit',
                ]);

                Route::put('return', [
                    'as' => 'ecommerce.settings.return.update',
                    'uses' => 'ReturnSettingController@update',
                    'permission' => 'ecommerce.settings.return',
                ]);

                Route::get('invoices', [
                    'as' => 'ecommerce.settings.invoices',
                    'uses' => 'InvoiceSettingController@edit',
                ]);

                Route::put('invoices', [
                    'as' => 'ecommerce.settings.invoices.update',
                    'uses' => 'InvoiceSettingController@update',
                    'permission' => 'ecommerce.settings.invoices',
                ]);

                Route::get('invoice-template', [
                    'as' => 'ecommerce.settings.invoice-template',
                    'uses' => 'InvoiceTemplateSettingController@edit',
                    'permission' => 'ecommerce.invoice-template.index',
                ]);

                Route::put('invoice-template', [
                    'as' => 'ecommerce.settings.invoice-template.update',
                    'uses' => 'InvoiceTemplateSettingController@update',
                    'permission' => 'ecommerce.invoice-template.index',
                    'middleware' => 'preventDemo',
                ]);

                Route::post('reset', [
                    'as' => 'ecommerce.settings.invoice-template.reset',
                    'uses' => 'InvoiceTemplateSettingController@reset',
                    'permission' => 'ecommerce.invoice-template.index',
                    'middleware' => 'preventDemo',
                ]);

                Route::get('preview', [
                    'as' => 'ecommerce.settings.invoice-template.preview',
                    'uses' => 'InvoiceTemplateSettingController@preview',
                    'permission' => 'ecommerce.invoice-template.index',
                ]);

                Route::match(['GET', 'POST'], 'taxes', [
                    'as' => 'ecommerce.settings.taxes',
                    'uses' => 'TaxSettingController@index',
                ]);

                Route::put('taxes', [
                    'as' => 'ecommerce.settings.taxes.update',
                    'uses' => 'TaxSettingController@update',
                    'permission' => 'ecommerce.settings.taxes',
                ]);

                Route::get('customers', [
                    'as' => 'ecommerce.settings.customers',
                    'uses' => 'CustomerSettingController@edit',
                ]);

                Route::put('customers', [
                    'as' => 'ecommerce.settings.customers.update',
                    'uses' => 'CustomerSettingController@update',
                    'permission' => 'ecommerce.settings.customers',
                ]);

                Route::get('shipping', [
                    'as' => 'ecommerce.settings.shipping',
                    'uses' => 'ShippingSettingController@edit',
                ]);

                Route::put('shipping', [
                    'as' => 'ecommerce.settings.shipping.update',
                    'uses' => 'ShippingSettingController@update',
                    'permission' => 'ecommerce.settings.shipping',
                ]);

                Route::get('webhook', [
                    'as' => 'ecommerce.settings.webhook',
                    'uses' => 'WebhookSettingController@edit',
                ]);

                Route::put('webhook', [
                    'as' => 'ecommerce.settings.webhook.update',
                    'uses' => 'WebhookSettingController@update',
                    'permission' => 'ecommerce.settings.webhook',
                ]);

                Route::get('tracking', [
                    'as' => 'ecommerce.settings.tracking',
                    'uses' => 'TrackingSettingController@edit',
                ]);

                Route::put('tracking', [
                    'as' => 'ecommerce.settings.tracking.update',
                    'uses' => 'TrackingSettingController@update',
                    'permission' => 'ecommerce.settings.tracking',
                ]);

                Route::get('standard-and-format', [
                    'as' => 'ecommerce.settings.standard-and-format',
                    'uses' => 'StandardAndFormatSettingController@edit',
                ]);

                Route::put('standard-and-format', [
                    'as' => 'ecommerce.settings.standard-and-format.update',
                    'uses' => 'StandardAndFormatSettingController@update',
                    'permission' => 'ecommerce.settings.standard-and-format',
                ]);

                Route::get('flash-sale', [
                    'as' => 'ecommerce.settings.flash-sale',
                    'uses' => 'FlashSaleSettingController@edit',
                ]);

                Route::put('flash-sale', [
                    'as' => 'ecommerce.settings.flash-sale.update',
                    'uses' => 'FlashSaleSettingController@update',
                    'permission' => 'ecommerce.settings.flash-sale',
                ]);
            });
        });
    });
});
