<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Facades\Assets;
use Botble\Base\Supports\Breadcrumb;
use Botble\Ecommerce\Enums\ProductTypeEnum;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Forms\ProductForm;
use Botble\Ecommerce\Http\Requests\ProductRequest;
use Botble\Ecommerce\Models\GroupedProduct;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductVariation;
use Botble\Ecommerce\Models\ProductVariationItem;
use Botble\Ecommerce\Services\Products\DuplicateProductService;
use Botble\Ecommerce\Services\Products\StoreAttributesOfProductService;
use Botble\Ecommerce\Services\Products\StoreProductService;
use Botble\Ecommerce\Services\StoreProductTagService;
use Botble\Ecommerce\Tables\ProductTable;
use Botble\Ecommerce\Tables\ProductVariationTable;
use Botble\Ecommerce\Traits\ProductActionsTrait;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    use ProductActionsTrait;

    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/ecommerce::products.name'), route('products.index'));
    }

    public function index(ProductTable $dataTable)
    {
        $this->pageTitle(trans('plugins/ecommerce::products.name'));

        Assets::addScripts(['bootstrap-editable'])
            ->addStyles(['bootstrap-editable']);

        return $dataTable->renderTable();
    }

    public function create(Request $request)
    {
        if (EcommerceHelper::isEnabledSupportDigitalProducts()) {
            if ($request->input('product_type') == ProductTypeEnum::DIGITAL) {
                $this->pageTitle(trans('plugins/ecommerce::products.create_product_type.digital'));
            } else {
                $this->pageTitle(trans('plugins/ecommerce::products.create_product_type.physical'));
            }
        } else {
            $this->pageTitle(trans('plugins/ecommerce::products.create'));
        }

        return ProductForm::create()->renderForm();
    }

    public function edit(Product $product, Request $request)
    {
        if ($product->is_variation) {
            abort(404);
        }

        $this->pageTitle(trans('plugins/ecommerce::products.edit', ['name' => $product->name]));

        event(new BeforeEditContentEvent($request, $product));

        return ProductForm::createFromModel($product)->renderForm();
    }

    public function store(
        ProductRequest $request,
        StoreProductService $service,
        StoreAttributesOfProductService $storeAttributesOfProductService,
        StoreProductTagService $storeProductTagService
    ) {
        $product = new Product();

        $product->status = $request->input('status');
        if (EcommerceHelper::isEnabledSupportDigitalProducts() && $productType = $request->input('product_type')) {
            $product->product_type = $productType;
        }

        $product = $service->execute($request, $product);
        $storeProductTagService->execute($request, $product);

        $addedAttributes = $request->input('added_attributes', []);

        if ($request->input('is_added_attributes') == 1 && $addedAttributes) {
            $storeAttributesOfProductService->execute(
                $product,
                array_keys($addedAttributes),
                array_values($addedAttributes)
            );

            $variation = ProductVariation::query()->create([
                'configurable_product_id' => $product->getKey(),
            ]);

            foreach ($addedAttributes as $attribute) {
                ProductVariationItem::query()->create([
                    'attribute_id' => $attribute,
                    'variation_id' => $variation->getKey(),
                ]);
            }

            $variation = $variation->toArray();

            $variation['variation_default_id'] = $variation['id'];

            $variation['sku'] = $product->sku;
            $variation['auto_generate_sku'] = true;

            $variation['images'] = array_filter((array) $request->input('images', []));

            $this->postSaveAllVersions(
                [$variation['id'] => $variation],
                $product->getKey(),
                $this->httpResponse()
            );
        }

        if ($request->has('grouped_products')) {
            GroupedProduct::createGroupedProducts(
                $product->getKey(),
                array_map(function ($item) {
                    return [
                        'id' => $item,
                        'qty' => 1,
                    ];
                }, array_filter(explode(',', $request->input('grouped_products', ''))))
            );
        }

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('products.index'))
            ->setNextUrl(route('products.edit', $product->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function update(
        Product $product,
        ProductRequest $request,
        StoreProductService $service,
        StoreProductTagService $storeProductTagService
    ) {
        $product->status = $request->input('status');

        $product = $service->execute($request, $product);
        $storeProductTagService->execute($request, $product);

        if ($request->has('variation_default_id')) {
            ProductVariation::query()
                ->where('configurable_product_id', $product->getKey())
                ->update(['is_default' => 0]);

            $defaultVariation = ProductVariation::query()->find($request->input('variation_default_id'));

            if ($defaultVariation) {
                $defaultVariation->is_default = true;
                $defaultVariation->save();
            }
        }

        $addedAttributes = $request->input('added_attributes', []);

        if ($request->input('is_added_attributes') == 1 && $addedAttributes) {
            $result = ProductVariation::getVariationByAttributesOrCreate($product->getKey(), $addedAttributes);

            /**
             * @var ProductVariation $variation
             */
            $variation = $result['variation'];

            foreach ($addedAttributes as $attribute) {
                ProductVariationItem::query()->create([
                    'attribute_id' => $attribute,
                    'variation_id' => $variation->getKey(),
                ]);
            }

            $variation = $variation->toArray();
            $variation['variation_default_id'] = $variation['id'];

            $product->productAttributeSets()->sync(array_keys($addedAttributes));

            $variation['sku'] = $product->sku;
            $variation['auto_generate_sku'] = true;

            $this->postSaveAllVersions([$variation['id'] => $variation], $product->getKey(), $this->httpResponse());
        } elseif ($product->variations()->count() === 0) {
            $product->productAttributeSets()->detach();
        }

        if ($request->has('grouped_products')) {
            GroupedProduct::createGroupedProducts(
                $product->getKey(),
                array_map(function ($item) {
                    return [
                        'id' => $item,
                        'qty' => 1,
                    ];
                }, array_filter(explode(',', $request->input('grouped_products', ''))))
            );
        }

        $relatedProductIds = $product->variations()->pluck('product_id')->all();

        Product::query()->whereIn('id', $relatedProductIds)->update(['status' => $product->status]);

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('products.index'))
            ->withUpdatedSuccessMessage();
    }

    public function duplicate(Product $product, DuplicateProductService $duplicateProductService)
    {
        $duplicatedProduct = $duplicateProductService->handle($product);

        return $this
            ->httpResponse()
            ->setData([
                'next_url' => route('products.edit', $duplicatedProduct->getKey()),
            ])
            ->setMessage(trans('plugins/ecommerce::ecommerce.forms.duplicate_success_message'));
    }

    public function getProductVariations(Product $product, ProductVariationTable $dataTable)
    {
        $dataTable->setProductId($product->getKey());

        if (EcommerceHelper::isEnabledSupportDigitalProducts() && $product->isTypeDigital()) {
            $dataTable->isDigitalProduct();
        }

        return $dataTable->renderTable();
    }

    public function setDefaultProductVariation(ProductVariation $productVariation)
    {
        ProductVariation::query()
            ->where('configurable_product_id', $productVariation->configurable_product_id)
            ->update(['is_default' => 0]);

        $productVariation->is_default = true;
        $productVariation->save();

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage();
    }
}
