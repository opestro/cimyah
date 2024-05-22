<?php

namespace Botble\Ecommerce\Services;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Services\Products\ProductCrossSalePriceService;

class HandleApplyProductCrossSaleService
{
    public function __construct(
        protected ProductCrossSalePriceService $productCrossSalePriceService
    ) {

    }

    public function handle(): void
    {
        $cart = Cart::instance('cart');

        if ($cart->isEmpty()) {
            return;
        }

        $ids = $cart->content()->pluck('id')->toArray();

        $products = get_products([
            'condition' => [
                ['ec_products.id', 'IN', $ids],
                'ec_products.status' => BaseStatusEnum::PUBLISHED,
            ],
            'with' => [
                'crossSales',
                'variationInfo.configurableProduct',
            ],
        ]);

        if ($products->isEmpty()) {
            return;
        }

        $crossSaleProducts = [];

        foreach ($products as $product) {
            if (! $product->is_variation) {
                $crossSaleProducts[] = $product;

                continue;
            }

            $crossSaleProducts[] = $product->original_product;
        }

        if (empty($crossSaleProducts)) {
            return;
        }

        $this->productCrossSalePriceService->applyProducts($crossSaleProducts);

        $productPrices = [];

        foreach ($products as $product) {
            $productPrices[$product->getKey()] = $product->front_sale_price;
        }

        foreach ($cart->content() as $rowId => $cartItem) {
            if (! isset($productPrices[$cartItem->id])) {
                continue;
            }

            $newPrice = $productPrices[$cartItem->id];

            if ($cartItem->price == $newPrice) {
                continue;
            }

            $cart->removeQuietly($rowId);

            $cart->addQuietly(
                $cartItem->id,
                $cartItem->name,
                $cartItem->qty,
                $newPrice,
                $cartItem->options->toArray()
            );
        }
    }
}
