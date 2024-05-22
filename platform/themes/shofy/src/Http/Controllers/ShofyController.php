<?php

namespace Theme\Shofy\Http\Controllers;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Services\Products\ProductCrossSalePriceService;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Http\Controllers\PublicController;
use Illuminate\Http\Request;

class ShofyController extends PublicController
{
    public function ajaxGetProducts(Request $request): BaseHttpResponse
    {
        $params = [
            'take' => $limit = $request->integer('limit', 10),
        ];

        $products = match ($request->query('type')) {
            'featured' => get_featured_products($params),
            'on-sale' => get_products_on_sale($params),
            'trending' => get_trending_products($params),
            'top-rated' => get_top_rated_products($limit),
            default => get_products($params + EcommerceHelper::withReviewsParams()),
        };

        return $this
            ->httpResponse()
            ->setData([
                'count' => number_format($products->count()),
                'html' => view(
                    Theme::getThemeNamespace('views.ecommerce.includes.product-items'),
                    ['products' => $products, 'itemsPerRow' => 4, 'layout' => 'grid']
                )->render(),
            ]);
    }

    public function ajaxGetCartContent()
    {
        return $this
            ->httpResponse()
            ->setData([
                'content' => Theme::partial('mini-cart.content'),
                'footer' => Theme::partial('mini-cart.footer'),
            ]);
    }

    public function ajaxGetCrossSaleProducts(Product $product, ProductCrossSalePriceService $productCrossSalePriceService)
    {
        $parentProduct = $product;
        $products = $product->crossSaleProducts;

        $productCrossSalePriceService->applyProduct($product);

        return $this
            ->httpResponse()
            ->setData(view(
                Theme::getThemeNamespace(
                    'views.ecommerce.includes.cross-sale-products'
                ),
                compact('products', 'parentProduct')
            )->render());
    }

    public function ajaxGetRelatedProducts(Product $product)
    {
        return $this
            ->httpResponse()
            ->setData(view(
                Theme::getThemeNamespace(
                    'views.ecommerce.includes.related-products'
                ),
                ['products' => get_related_products($product)]
            )->render());
    }
}
