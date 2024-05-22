<?php

namespace Botble\Ecommerce\Services\Products;

use Botble\Ecommerce\Models\Product;
use Carbon\Carbon;
use Closure;

class ProductSalePriceService extends ProductPriceHandlerService
{
    public function handle(Product $product, Closure $next)
    {
        $price = $product->getFinalPrice();
        $salePrice = $product->sale_price;

        if ($salePrice === null || $salePrice > $price) {
            return $next($product);
        }

        if ($product->sale_type === 0) {
            $product->setFinalPrice($salePrice);

            return $next($product);
        }

        $startDate = $product->start_date;
        $endDate = $product->end_date;
        $now = Carbon::now();

        if (
            (! $startDate || ($startDate instanceof Carbon && $startDate->lte($now)))
            && (! $endDate || ($endDate instanceof Carbon && $endDate->gte($now)))
        ) {
            $product->setFinalPrice($salePrice);

            return $next($product);
        }

        return $next($product);
    }
}
