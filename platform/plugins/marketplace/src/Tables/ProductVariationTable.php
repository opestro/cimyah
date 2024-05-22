<?php

namespace Botble\Marketplace\Tables;

use Botble\Base\Facades\Form;
use Botble\Base\Facades\Html;
use Botble\Ecommerce\Models\ProductVariation;
use Botble\Ecommerce\Tables\ProductVariationTable as EcommerceProductVariationTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class ProductVariationTable extends EcommerceProductVariationTable
{
    public function ajax(): JsonResponse
    {
        $data = $this->loadDataTable();
        $data
            ->editColumn('is_default', function (ProductVariation $item) {
                return Html::tag(
                    'label',
                    Form::radio('variation_default_id', $item->getKey(), $item->is_default, [
                        'data-url' => route('products.set-default-product-variation', $item->getKey()),
                        'data-bs-toggle' => 'tooltip',
                        'title' => trans('plugins/ecommerce::products.set_this_variant_as_default'),
                        'class' => 'form-check-input',
                    ])
                );
            })
            ->editColumn('operations', function (ProductVariation $item) {
                $update = route('marketplace.vendor.products.update-version', $item->getKey());
                $loadForm = route('marketplace.vendor.products.get-version-form', $item->getKey());
                $delete = route('marketplace.vendor.products.delete-version', $item->getKey());

                return view('plugins/ecommerce::products.variations.actions', compact('update', 'loadForm', 'delete', 'item'));
            });

        return $this->toJson($data);
    }

    protected function baseQuery(): Relation|Builder|QueryBuilder
    {
        return $this
            ->getModel()
            ->query()
            ->whereHas('configurableProduct', function (Builder $query) {
                $query
                    ->where([
                        'configurable_product_id' => $this->productId,
                        'store_id' => auth('customer')->user()->store->getKey(),
                    ]);
            });
    }

    public function setProductId(int|string $productId): self
    {
        parent::setProductId($productId);
        $this->setAjaxUrl(route('marketplace.vendor.products.product-variations', $this->productId));

        return $this;
    }
}
