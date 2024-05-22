<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Facades\Assets;
use Botble\Ecommerce\Http\Requests\UpdateProductPriceRequest;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Services\Products\UpdateDefaultProductService;
use Botble\Ecommerce\Tables\ProductPriceTable;

class ProductPriceController extends BaseController
{
    public function index(ProductPriceTable $dataTable)
    {
        $this->pageTitle(trans('plugins/ecommerce::product-prices.name'));

        Assets::addScriptsDirectly('vendor/core/plugins/ecommerce/js/product-bulk-editable-table.js');

        return $dataTable->renderTable();
    }

    public function update(Product $product, UpdateProductPriceRequest $request)
    {
        $product->forceFill([
            $request->input('column') => $request->input('value'),
        ])->save();

        if ($product->is_variation) {
            $product->load('variationInfo.configurableProduct');

            if ($product->variationInfo->is_default) {
                app(UpdateDefaultProductService::class)->execute($product);
            }
        }

        return $this->httpResponse()->withUpdatedSuccessMessage();
    }
}
