<?php

namespace Botble\Ecommerce\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Services\ProductWishlistService;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class WishlistController extends BaseController
{
    public function index(Request $request, ProductInterface $productRepository)
    {
        SeoHelper::setTitle(__('Wishlist'));

        $queryParams = [
            'paginate' => [
                'per_page' => 100,
                'current_paged' => $request->integer('page', 1) ?: 1,
            ],
            'with' => ['slugable'],
            ...EcommerceHelper::withReviewsParams(),
        ];

        if (auth('customer')->check()) {
            $products = $productRepository->getProductsWishlist(auth('customer')->id(), $queryParams);
        } else {
            $products = new LengthAwarePaginator([], 0, 10);

            $itemIds = Cart::instance('wishlist')
                ->content()
                ->sortBy([['updated_at', 'desc']])
                ->pluck('id')
                ->unique('id')
                ->all();

            if ($itemIds) {
                $products = $productRepository->getProductsByIds($itemIds, $queryParams);
            }
        }

        Theme::breadcrumb()->add(__('Wishlist'), route('public.wishlist'));

        return Theme::scope('ecommerce.wishlist', compact('products'), 'plugins/ecommerce::themes.wishlist')->render();
    }

    public function store(int|string $productId, Request $request)
    {
        if (! $productId) {
            $productId = $request->input('product_id');
        }

        if (! $productId) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('This product is not available.'));
        }

        /**
         * @var Product $product
         */
        $product = Product::query()->findOrFail($productId);

        $isAdded = app(ProductWishlistService::class)->handle($product);

        return $this
            ->httpResponse()
            ->setMessage(
                $isAdded
                ? __('Added product :product successfully!', ['product' => $product->name])
                : __('Removed product :product from wishlist successfully!', ['product' => $product->name])
            )
            ->setData([
                'count' => $this->wishlistCount(),
                'added' => $isAdded,
            ]);
    }

    public function destroy(int|string $productId)
    {
        /**
         * @var Product $product
         */
        $product = Product::query()->findOrFail($productId);

        app(ProductWishlistService::class)->handle($product);

        return $this
            ->httpResponse()
            ->setMessage(__('Removed product :product from wishlist successfully!', ['product' => $product->name]))
            ->setData(['count' => $this->wishlistCount()]);
    }

    protected function wishlistCount(): int
    {
        if (! auth('customer')->check()) {
            return Cart::instance('wishlist')->count();
        }

        return auth('customer')->user()->wishlist()->count();
    }
}
