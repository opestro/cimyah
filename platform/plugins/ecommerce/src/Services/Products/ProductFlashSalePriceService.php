<?php

namespace Botble\Ecommerce\Services\Products;

use Botble\Ecommerce\Facades\FlashSale;
use Botble\Ecommerce\Models\Product;
use Closure;

class ProductFlashSalePriceService extends ProductPriceHandlerService
{
    public function handle(Product $product, Closure $next)
    {
        if (! FlashSale::isEnabled()) {
            return $next($product);
        }

        $finalPrice = (float) ($product->getFinalPrice() ?: $product->price);
        $flashSalePrice = (float) $product->getFlashSalePrice();

        if ($flashSalePrice < $finalPrice) {
            $product->setOriginalPrice($flashSalePrice);
            $product->setFinalPrice($flashSalePrice);
        } else {
            $product->setOriginalPrice($finalPrice);
        }

        return $next($product);
    }
}
