<?php

namespace Botble\Ecommerce\Supports;

use Botble\Ecommerce\Facades\EcommerceHelper as EcommerceHelperFacade;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductAttributeSet;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Illuminate\View\View;

class RenderProductAttributesViewOnlySupport
{
    public function __construct(
        protected Product $product,
        protected ProductAttributeSet $productAttributeSet,
        protected ProductInterface $productRepository
    ) {
    }

    public function render(array $options = []): View
    {
        $product = $this->product;
        $attributeSet = $this->productAttributeSet;

        $view = EcommerceHelperFacade::viewPath('attributes.attributes-view-only');

        $options = apply_filters('ecommerce_render_product_attributes_view_only_options_before', $options);

        if (isset($options['view'])) {
            $view = $options['view'];
        }

        $attributes = $this
            ->productRepository
            ->getRelatedProductAttributes($product)
            ->where('attribute_set_id', $attributeSet->getKey())->sortBy('order');

        return view($view, compact('attributes', 'attributeSet'));
    }
}
