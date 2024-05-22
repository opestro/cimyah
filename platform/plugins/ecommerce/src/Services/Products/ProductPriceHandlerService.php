<?php

namespace Botble\Ecommerce\Services\Products;

use Botble\Ecommerce\Models\Product;
use Closure;

abstract class ProductPriceHandlerService
{
    public function handle(Product $product, Closure $next)
    {
        return $next($product);
    }
}
