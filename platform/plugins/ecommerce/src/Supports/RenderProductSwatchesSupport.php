<?php

namespace Botble\Ecommerce\Supports;

use Botble\Ecommerce\Facades\EcommerceHelper as EcommerceHelperFacade;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductVariation;
use Botble\Ecommerce\Models\ProductVariationItem;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Illuminate\Support\Arr;

class RenderProductSwatchesSupport
{
    protected Product $product;

    public function __construct(protected ProductInterface $productRepository)
    {
    }

    public function setProduct(Product $product): RenderProductSwatchesSupport
    {
        $this->product = $product;

        return $this;
    }

    public function render(array $params = []): string
    {
        $params = array_merge([
            'selected' => [],
            'view' => EcommerceHelperFacade::viewPath('attributes.swatches-renderer'),
        ], $params);

        $product = $this->product;

        $attributeSets = $product->productAttributeSets()->orderBy('order')->get();

        $attributes = $this->productRepository->getRelatedProductAttributes($this->product)->sortBy('order');

        $productVariations = ProductVariation::query()
            ->where('configurable_product_id', $product->getKey())
            ->with(['productAttributes', 'product'])
            ->get();

        $productVariationsInfo = ProductVariationItem::getVariationsInfo($productVariations->pluck('id')->toArray());

        if ($productVariationsInfo->isNotEmpty()) {
            $productVariationsInfo = $productVariationsInfo
                ->reject(function (ProductVariationItem $productVariation) use ($productVariations) {
                    $variationItem = $productVariations->where('id', $productVariation->variation_id)->first();

                    if (! $variationItem) {
                        return false;
                    }

                    return $variationItem->product->isOutOfStock();
                });
        }

        $selected = $params['selected'];

        return view(
            $params['view'],
            [
                ...compact(
                    'attributeSets',
                    'attributes',
                    'product',
                    'selected',
                    'productVariationsInfo',
                    'productVariations'
                ),
                ...Arr::except($params, ['view', 'selected']),
            ]
        )->render();
    }
}
