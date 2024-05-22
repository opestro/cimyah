<?php

namespace Botble\Ecommerce\Services;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Helper;
use Botble\Ecommerce\Events\ProductViewed;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\GoogleAnalytics\GoogleTagManager;
use Botble\Ecommerce\Models\Brand;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Ecommerce\Models\ProductTag;
use Botble\Ecommerce\Services\Products\GetProductService;
use Botble\Ecommerce\Services\Products\ProductCrossSalePriceService;
use Botble\Ecommerce\Services\Products\UpdateDefaultProductService;
use Botble\Ecommerce\Traits\CheckReviewConditionTrait;
use Botble\Media\Facades\RvMedia;
use Botble\SeoHelper\Entities\Twitter\Card;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\SeoHelper\SeoOpenGraph;
use Botble\Slug\Models\Slug;
use Botble\Theme\Facades\AdminBar;
use Botble\Theme\Facades\Theme;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class HandleFrontPages
{
    use CheckReviewConditionTrait;

    public function __construct(
        protected ProductCrossSalePriceService $productCrossSalePriceService
    ) {
    }

    public function handle(Slug|array $slug): array|Slug
    {
        if (! $slug instanceof Slug) {
            return $slug;
        }

        $request = request();

        $response = BaseHttpResponse::make();

        $isPreview = Auth::guard()->check() && $request->input('preview');

        switch ($slug->reference_type) {
            case Product::class:

                $condition = [
                    'ec_products.id' => $slug->reference_id,
                    'ec_products.status' => BaseStatusEnum::PUBLISHED,
                ];

                if ($isPreview) {
                    $condition['ec_products.status'] = BaseStatusEnum::PENDING;
                }

                $product = get_products(
                    [
                        'condition' => $condition,
                        'take' => 1,
                        'with' => [
                            'slugable',
                            'tags',
                            'tags.slugable',
                            'categories',
                            'categories.slugable',
                            'options',
                            'options.values',
                            'crossSales' => function (BelongsToMany $query) {
                                $query->where('ec_product_cross_sale_relations.is_variant', false);
                            },
                        ],
                        ...EcommerceHelper::withReviewsParams(),
                    ]
                );

                if (! $product) {
                    abort(404);
                }

                $this->productCrossSalePriceService->applyProduct($product);

                SeoHelper::setTitle($product->name)->setDescription($product->description);

                $meta = new SeoOpenGraph();
                if ($product->image) {
                    $meta->setImage(RvMedia::getImageUrl($product->image));
                }
                $meta->setDescription($product->description);
                $meta->setUrl($product->url);
                $meta->setTitle($product->name);

                SeoHelper::setSeoOpenGraph($meta);

                SeoHelper::meta()->setUrl($product->url);

                $card = new Card();
                $card->setType(Card::TYPE_PRODUCT);
                $card->addMeta('label1', 'Price');
                $card->addMeta(
                    'data1',
                    $product->price()->displayAsText() . ' ' . strtoupper(get_application_currency()->title)
                );
                $card->addMeta('label2', 'Website');
                $card->addMeta('data2', SeoHelper::openGraph()->getProperty('site_name'));
                $card->addMeta('domain', url(''));

                SeoHelper::twitter()->setCard($card);

                if (Helper::handleViewCount($product, 'viewed_product')) {
                    event(new ProductViewed($product, Carbon::now()));

                    EcommerceHelper::handleCustomerRecentlyViewedProduct($product);
                }

                Theme::breadcrumb()->add(__('Products'), route('public.products'));

                $category = $product->categories->sortByDesc('id')->first();

                if ($category) {
                    if ($category->parents->count()) {
                        foreach ($category->parents->reverse() as $parentCategory) {
                            Theme::breadcrumb()->add($parentCategory->name, $parentCategory->url);
                        }
                    }

                    Theme::breadcrumb()->add($category->name, $category->url);
                }

                Theme::breadcrumb()->add($product->name);

                Theme::addBodyAttributes(['class' => 'single-product']);

                if (function_exists('admin_bar')) {
                    admin_bar()
                        ->registerLink(
                            trans('plugins/ecommerce::products.edit_this_product'),
                            route('products.edit', $product->getKey()),
                            null,
                            'products.edit'
                        );
                }

                do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, PRODUCT_MODULE_SCREEN_NAME, $product);

                app(GoogleTagManager::class)
                    ->viewItem($product);

                [$productImages, $productVariation, $selectedAttrs] = EcommerceHelper::getProductVariationInfo(
                    $product,
                    $request->input()
                );

                if (! $product->is_variation && $productVariation) {
                    $product = app(UpdateDefaultProductService::class)->updateColumns($product, $productVariation);
                    $selectedProductVariation = $productVariation->defaultVariation;
                    $selectedProductVariation->product_id = $productVariation->id;

                    $product->defaultVariation = $selectedProductVariation;

                    $product->image = $selectedProductVariation->configurableProduct->image ?: $product->image;
                }

                $checkReview = $this->checkReviewCondition($product->getKey());

                return [
                    'view' => 'ecommerce.product',
                    'default_view' => 'plugins/ecommerce::themes.product',
                    'data' => compact('product', 'selectedAttrs', 'productImages', 'productVariation', 'checkReview'),
                    'slug' => $product->slug,
                ];

            case ProductCategory::class:
                $category = ProductCategory::query()
                    ->where('id', $slug->reference_id)
                    ->when(! $isPreview, function ($query) {
                        $query->wherePublished();
                    })
                    ->with(['slugable'])
                    ->firstOrFail();

                if (! EcommerceHelper::productFilterParamsValidated($request)) {
                    $request = request();
                }

                $with = EcommerceHelper::withProductEagerLoadingRelations();

                $categoryIds = [$category->getKey()];

                $children = $category->activeChildren;

                while ($children->isNotEmpty()) {
                    foreach ($children as $item) {
                        $categoryIds[] = $item->id;
                        $children = $item->activeChildren;
                    }
                }

                if (! $request->input('categories')) {
                    $request->merge(['categories' => $categoryIds]);
                }

                $products = app(GetProductService::class)->getProduct($request, null, null, $with);

                $request->merge([
                    'categories' => array_merge($category->parents->pluck('id')->all(), $categoryIds),
                ]);

                SeoHelper::setTitle($category->name)->setDescription($category->description);

                $meta = new SeoOpenGraph();
                if ($category->image) {
                    $meta->setImage(RvMedia::getImageUrl($category->image));
                }

                $meta->setDescription($category->description);
                $meta->setUrl($category->url);
                $meta->setTitle($category->name);

                SeoHelper::setSeoOpenGraph($meta);

                SeoHelper::meta()->setUrl($category->url);

                if (function_exists('admin_bar')) {
                    AdminBar::registerLink(
                        trans('plugins/ecommerce::product-categories.edit_this_category'),
                        route('product-categories.edit', $category->getKey()),
                        null,
                        'product-categories.edit'
                    );
                }

                Theme::breadcrumb()->add(__('Products'), route('public.products'));

                if ($category->parents->isNotEmpty()) {
                    foreach ($category->parents->reverse() as $parentCategory) {
                        Theme::breadcrumb()->add($parentCategory->name, $parentCategory->url);
                    }
                }

                Theme::breadcrumb()->add($category->name);

                do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, PRODUCT_CATEGORY_MODULE_SCREEN_NAME, $category);

                if ($request->ajax()) {
                    return $this->ajaxFilterProductsResponse($products, $response, $category);
                }

                return [
                    'view' => 'ecommerce.product-category',
                    'default_view' => 'plugins/ecommerce::themes.product-category',
                    'data' => compact('category', 'products'),
                    'slug' => $category->slug,
                ];

            case Brand::class:
                $brand = Brand::query()
                    ->where('id', $slug->reference_id)
                    ->when(! $isPreview, function ($query) {
                        $query->wherePublished();
                    })
                    ->with(['slugable'])
                    ->firstOrFail();

                if (! EcommerceHelper::productFilterParamsValidated($request)) {
                    $request = request();
                }

                $request->merge(['brands' => array_merge((array) request()->input('brands', []), [$brand->getKey()])]);

                $products = app(GetProductService::class)->getProduct(
                    $request,
                    null,
                    $brand->getKey(),
                    EcommerceHelper::withProductEagerLoadingRelations()
                );

                if ($request->ajax()) {
                    return $this->ajaxFilterProductsResponse($products, $response);
                }

                SeoHelper::setTitle($brand->name)->setDescription($brand->description);

                Theme::breadcrumb()->add($brand->name);

                $meta = new SeoOpenGraph();
                if ($brand->logo) {
                    $meta->setImage(RvMedia::getImageUrl($brand->logo));
                }
                $meta->setDescription($brand->description);
                $meta->setUrl($brand->url);
                $meta->setTitle($brand->name);

                SeoHelper::setSeoOpenGraph($meta);

                SeoHelper::meta()->setUrl($brand->url);

                if (function_exists('admin_bar')) {
                    admin_bar()
                        ->registerLink(
                            trans('plugins/ecommerce::brands.edit_this_brand'),
                            route('brands.edit', $brand->getKey()),
                            null,
                            'brands.edit'
                        );
                }

                do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, BRAND_MODULE_SCREEN_NAME, $brand);

                return [
                    'view' => 'ecommerce.brand',
                    'default_view' => 'plugins/ecommerce::themes.brand',
                    'data' => compact('brand', 'products'),
                    'slug' => $brand->slug,
                ];

            case ProductTag::class:
                $condition = [
                    'ec_product_tags.id' => $slug->reference_id,
                    'ec_product_tags.status' => BaseStatusEnum::PUBLISHED,
                ];

                if ($isPreview) {
                    Arr::forget($condition, 'ec_product_tags.status');
                }

                $tag = ProductTag::query()
                    ->with(['slugable', 'products'])
                    ->where($condition)
                    ->firstOrFail();

                if (! EcommerceHelper::productFilterParamsValidated($request)) {
                    $request = request();
                }

                $with = EcommerceHelper::withProductEagerLoadingRelations();

                $request->merge([
                    'tags' => [$tag->getKey()],
                ]);

                $products = app(GetProductService::class)->getProduct($request, null, null, $with);

                if ($request->ajax()) {
                    return $this->ajaxFilterProductsResponse($products, $response);
                }

                SeoHelper::setTitle($tag->name)->setDescription($tag->description);

                $meta = new SeoOpenGraph();
                $meta->setDescription($tag->description);
                $meta->setUrl($tag->url);
                $meta->setTitle($tag->name);

                SeoHelper::setSeoOpenGraph($meta);

                SeoHelper::meta()->setUrl($tag->url);

                Theme::breadcrumb()
                    ->add(__('Products'), route('public.products'))
                    ->add($tag->name);

                if (function_exists('admin_bar')) {
                    admin_bar()
                        ->registerLink(
                            trans('plugins/ecommerce::product-tag.edit_this_product_tag'),
                            route('product-tag.edit', $tag->getKey()),
                            null,
                            'product-tag.edit'
                        );
                }

                do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, PRODUCT_TAG_MODULE_SCREEN_NAME, $tag);

                return [
                    'view' => 'ecommerce.product-tag',
                    'default_view' => 'plugins/ecommerce::themes.product-tag',
                    'data' => compact('tag', 'products'),
                    'slug' => $tag->slug,
                ];
        }

        return $slug;
    }

    public function ajaxFilterProductsResponse(
        $products,
        BaseHttpResponse $response,
        ?ProductCategory $category = null
    ): array {
        $total = $products->total();
        $message = $total === 1 ? __(':total Product found', compact('total')) : __(
            ':total Products found',
            compact('total')
        );

        $data = view(EcommerceHelper::viewPath('includes.product-items'), compact('products'))->render();

        $breadcrumbView = Theme::getThemeNamespace('partials.breadcrumbs');

        if (view()->exists($breadcrumbView)) {
            $additional['breadcrumb'] = Theme::partial('breadcrumbs');
        } else {
            $additional['breadcrumb'] = Theme::breadcrumb()->render();
        }

        $filtersView = EcommerceHelper::viewPath('includes.filters');

        if (view()->exists($filtersView)) {
            $additional['filters_html'] = view($filtersView, compact('category'))->render();
        }

        return $response
            ->setData($data)
            ->setAdditional($additional)
            ->setMessage($message)
            ->toArray();
    }
}
