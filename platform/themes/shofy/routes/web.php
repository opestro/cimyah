<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Base\Http\Middleware\RequiresJsonRequestMiddleware;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Http\Controllers\Fronts\PublicAjaxController;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Ecommerce\Models\ProductCollection;
use Botble\Theme\Facades\Theme;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Theme\Shofy\Http\Controllers\ShofyController;

if (is_plugin_active('ecommerce')) {
    Route::controller(ShofyController::class)
        ->middleware(RequiresJsonRequestMiddleware::class)
        ->group(function () {
            Theme::registerRoutes(function () {
                Route::prefix('ajax')->name('public.ajax.')->group(function () {
                    Route::get('products', 'ajaxGetProducts')->name('products');
                    Route::get('search-products', [PublicAjaxController::class, 'ajaxSearchProducts'])
                        ->name('search-products');
                    Route::get('categories-dropdown', [PublicAjaxController::class, 'ajaxGetCategoriesDropdown'])
                        ->name('categories-dropdown');
                    Route::get('cart-content', 'ajaxGetCartContent')
                        ->name('cart-content');
                    Route::get('cross-sale-products/{product}', 'ajaxGetCrossSaleProducts')
                        ->name('cross-sale-products');
                    Route::get('related-products/{product}', 'ajaxGetRelatedProducts')
                        ->name('related-products');
                });
            });

            AdminHelper::registerRoutes(function () {
                Route::prefix('ajax')->name('admin.ajax.')->group(function () {
                    Route::get('search-products', function () {
                        $products = Product::query()
                            ->wherePublished()
                            ->where('is_variation', false)
                            ->when(request()->input('search'), function (Builder $query, string $search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->select('name', 'id')
                            ->paginate();

                        return BaseHttpResponse::make()
                            ->setData($products);
                    })->name('search-products');

                    Route::get('search-categories', function () {
                        $categories = ProductCategory::query()
                            ->when(request()->input('search'), function (Builder $query, string $search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->select('name', 'id')
                            ->paginate();

                        return BaseHttpResponse::make()
                            ->setData($categories);
                    })->name('search-categories');

                    Route::get('search-collections', function () {
                        $collections = ProductCollection::query()
                            ->when(request()->input('search'), function (Builder $query, string $search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->select('name', 'id')
                            ->paginate();

                        return BaseHttpResponse::make()
                            ->setData($collections);
                    })->name('search-collections');
                });
            });
        });
}

Theme::routes();
