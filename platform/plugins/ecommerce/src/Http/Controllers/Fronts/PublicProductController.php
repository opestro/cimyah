<?php

namespace Botble\Ecommerce\Http\Controllers\Fronts;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Models\BaseQueryBuilder;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Forms\Fronts\OrderTrackingForm;
use Botble\Ecommerce\GoogleAnalytics\GoogleTagManager;
use Botble\Ecommerce\Http\Requests\Fronts\OrderTrackingRequest;
use Botble\Ecommerce\Http\Resources\ProductVariationResource;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Ecommerce\Models\ProductVariation;
use Botble\Ecommerce\Models\ProductVariationItem;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Services\HandleFrontPages;
use Botble\Ecommerce\Services\Products\GetProductService;
use Botble\Ecommerce\Services\Products\GetProductWithCrossSalesBySlugService;
use Botble\Ecommerce\Services\Products\ProductCrossSalePriceService;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PublicProductController extends BaseController
{
    public function getProducts(Request $request, GetProductService $productService)
    {
        if (! EcommerceHelper::productFilterParamsValidated($request)) {
            return $this
                ->httpResponse()
                ->setNextUrl(route('public.products'));
        }

        $with = EcommerceHelper::withProductEagerLoadingRelations();

        if (($query = BaseHelper::stringify($request->input('q'))) && ! $request->ajax()) {
            $products = $productService->getProduct($request, null, null, $with);

            SeoHelper::setTitle(__('Search result for ":query"', compact('query')));

            Theme::breadcrumb()
                ->add(__('Search'), route('public.products'));

            SeoHelper::meta()->setUrl(route('public.products'));

            return Theme::scope(
                'ecommerce.search',
                compact('products', 'query'),
                'plugins/ecommerce::themes.search'
            )->render();
        }

        Theme::breadcrumb()->add(__('Products'), route('public.products'));

        $products = $productService->getProduct($request, null, null, $with);

        if ($request->ajax()) {
            return $this->ajaxFilterProductsResponse($products);
        }

        SeoHelper::setTitle(__('Products'))->setDescription(__('Products'));

        do_action(PRODUCT_MODULE_SCREEN_NAME);

        app(GoogleTagManager::class)->viewItemList($products->all(), 'Product List');

        return Theme::scope(
            'ecommerce.products',
            compact('products'),
            'plugins/ecommerce::themes.products'
        )->render();
    }

    public function getProductVariation(
        int|string $id,
        Request $request,
        ProductInterface $productRepository,
        GetProductWithCrossSalesBySlugService $getProductWithCrossSalesBySlugService,
    ) {
        $request->validate([
            'reference_product' => ['sometimes', 'required', 'string'],
        ]);

        $product = null;

        if ($attributes = $request->input('attributes', [])) {
            $variation = ProductVariation::getVariationByAttributes($id, $attributes);

            if ($variation) {
                $product = $productRepository->getProductVariations($id, [
                    'condition' => [
                        'ec_product_variations.id' => $variation->getKey(),
                        'original_products.status' => BaseStatusEnum::PUBLISHED,
                    ],
                    'select' => [
                        'ec_products.id',
                        'ec_products.name',
                        'ec_products.quantity',
                        'ec_products.price',
                        'ec_products.sale_price',
                        'ec_products.sale_type',
                        'ec_products.start_date',
                        'ec_products.end_date',
                        'ec_products.allow_checkout_when_out_of_stock',
                        'ec_products.with_storehouse_management',
                        'ec_products.stock_status',
                        'ec_products.images',
                        'ec_products.sku',
                        'ec_products.barcode',
                        'ec_products.description',
                        'ec_products.is_variation',
                        'original_products.images as original_images',
                        'ec_products.height',
                        'ec_products.weight',
                        'ec_products.wide',
                        'ec_products.length',
                    ],
                    'take' => 1,
                ]);
            }
        } else {
            $product = Product::query()
                ->where('id', $id)
                ->wherePublished()
                ->select([
                    'id',
                    'name',
                    'quantity',
                    'price',
                    'sale_price',
                    'allow_checkout_when_out_of_stock',
                    'with_storehouse_management',
                    'stock_status',
                    'images',
                    'sku',
                    'description',
                    'is_variation',
                    'height',
                    'weight',
                    'wide',
                    'length',
                ])
                ->first();

            $attributes = $product ? $product->defaultVariation->productAttributes->pluck('id')->all() : [];
        }

        if ($product) {
            if ($product->images) {
                $originalImages = $product->images;

                if (get_ecommerce_setting(
                    'how_to_display_product_variation_images'
                ) == 'variation_images_and_main_product_images') {
                    $parentImages = is_array($product->original_images) ? $product->original_images : (array) json_decode($product->original_images, true);

                    if ($parentImages && is_array($parentImages)) {
                        $originalImages = array_merge($originalImages, $parentImages);
                    }
                }
            } else {
                $originalImages = $product->original_images ?: $product->original_product->images;

                if (! is_array($originalImages)) {
                    $originalImages = json_decode($originalImages, true);
                }
            }

            $product->image_with_sizes = rv_get_image_list($originalImages, [
                'origin',
                'thumb',
            ]);

            if ($product->isOutOfStock()) {
                $product->errorMessage = __('Out of stock');
            }

            if (! $product->with_storehouse_management || $product->quantity < 1) {
                $product->successMessage = __('In stock');
            } elseif ($product->quantity) {
                if (EcommerceHelper::showNumberOfProductsInProductSingle()) {
                    if ($product->quantity != 1) {
                        $product->successMessage = __(':number products available', ['number' => $product->quantity]);
                    } else {
                        $product->successMessage = __(':number product available', ['number' => $product->quantity]);
                    }
                } else {
                    $product->successMessage = __('In stock');
                }
            }

            $originalProduct = $product->original_product;
        } else {
            $originalProduct = Product::query()
                ->where('id', $id)
                ->wherePublished()
                ->select([
                    'id',
                    'name',
                    'quantity',
                    'price',
                    'sale_price',
                    'allow_checkout_when_out_of_stock',
                    'with_storehouse_management',
                    'stock_status',
                    'images',
                    'sku',
                    'description',
                    'is_variation',
                    'height',
                    'weight',
                    'wide',
                    'length',
                ])
                ->first();

            if ($originalProduct) {
                if ($originalProduct->images) {
                    $originalProduct->image_with_sizes = rv_get_image_list($originalProduct->images, [
                        'origin',
                        'thumb',
                    ]);
                }

                $originalProduct->errorMessage = __('Please select attributes');
            }
        }

        if (! $originalProduct) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Not available'));
        }

        $productAttributes = $productRepository->getRelatedProductAttributes($originalProduct)->sortBy('order');

        $attributeSets = $originalProduct->productAttributeSets()->orderBy('order')->get();

        $productVariations = ProductVariation::query()
            ->where('configurable_product_id', $originalProduct->id)
            ->get();

        $productVariationsInfo = ProductVariationItem::getVariationsInfo($productVariations->pluck('id')->toArray());

        $variationInfo = $productVariationsInfo;

        $unavailableAttributeIds = [];
        $variationNextIds = [];
        foreach ($attributeSets as $key => $set) {
            if ($key != 0) {
                $variationInfo = $productVariationsInfo
                    ->where('attribute_set_id', $set->id)
                    ->whereIn('variation_id', $variationNextIds);
            }

            [$variationNextIds, $unavailableAttributeIds] = handle_next_attributes_in_product(
                $productAttributes->where('attribute_set_id', $set->id),
                $productVariationsInfo,
                $set->id,
                $attributes,
                $key,
                $variationNextIds,
                $variationInfo,
                $unavailableAttributeIds
            );
        }

        if (! $product) {
            $product = $originalProduct;
        }

        if (! $product->is_variation) {
            $selectedAttributes = $product->defaultVariation->productAttributes->map(function ($item) {
                $item->attribute_set_slug = $item->productAttributeSet->slug;

                return $item;
            });
        } else {
            $selectedAttributes = $product->variationProductAttributes;
        }

        $product->unavailableAttributeIds = $unavailableAttributeIds;
        $product->selectedAttributes = $selectedAttributes;

        if (
            $request->filled('reference_product')
            && $referenceProduct = $getProductWithCrossSalesBySlugService->handle(
                $request->input('reference_product')
            )
        ) {
            app(ProductCrossSalePriceService::class)->applyProduct($referenceProduct);
        }

        return $this
            ->httpResponse()
            ->setData(new ProductVariationResource($product));
    }

    public function getOrderTracking(OrderTrackingRequest $request)
    {
        if (! EcommerceHelper::isOrderTrackingEnabled()) {
            abort(404);
        }

        $order = null;

        $title = __('Order tracking');

        if ($request->validated()) {
            $code = $request->input('order_id');

            $query = Order::query()
                ->where(function (Builder $query) use ($code) {
                    $query
                        ->where('ec_orders.code', $code)
                        ->orWhere('ec_orders.code', '#' . $code);
                })
                ->with(['address', 'products'])
                ->select('ec_orders.*')
                ->when(EcommerceHelper::isLoginUsingPhone(), function (BaseQueryBuilder $query) use ($request) {
                    $query->where(function (BaseQueryBuilder $query) use ($request) {
                        $query
                            ->whereHas('address', fn ($subQuery) => $subQuery->where('phone', $request->input('phone')))
                            ->orWhereHas('user', fn ($subQuery) => $subQuery->where('phone', $request->input('phone')));
                    });
                }, function (BaseQueryBuilder $query) use ($request) {
                    $query->where(function (Builder $query) use ($request) {
                        $query
                            ->whereHas('address', fn ($subQuery) => $subQuery->where('email', $request->input('email')))
                            ->orWhereHas('user', fn ($subQuery) => $subQuery->where('email', $request->input('email')));
                    });
                });

            $order = apply_filters('ecommerce_order_tracking_query', $query)->first();

            if ($order && is_plugin_active('payment')) {
                $order->load('payment');
            }

            $title = __('Order tracking :code', ['code' => $code]);
        }

        SeoHelper::setTitle($title);

        Theme::breadcrumb()
            ->add($title, route('public.orders.tracking'));

        $form = OrderTrackingForm::createFromArray($request->validated());

        return Theme::scope('ecommerce.order-tracking', compact('order', 'form'), 'plugins/ecommerce::themes.order-tracking')
            ->render();
    }

    protected function ajaxFilterProductsResponse($products, ?ProductCategory $category = null)
    {
        return app(HandleFrontPages::class)->ajaxFilterProductsResponse($products, $this->httpResponse(), $category);
    }
}
