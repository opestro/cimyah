<?php

use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductAttributeSet;
use Botble\Ecommerce\Supports\RenderProductAttributesViewOnlySupport;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

if (! function_exists('get_product_attribute_groups_for_product_list')) {
    function get_product_attribute_groups_for_product_list(Collection $attributes): array
    {
        $groups = [];

        foreach ($attributes->groupBy('attribute_set_id') as $key => $item) {
            /**
             * @var Builder $item
             */
            $first = $item->first();

            if (! $first) {
                continue;
            }

            $groups[] = [
                'attribute_set_id' => $key,
                'attribute_set_title' => $first->product_attribute_set_title,
                'product_attribute_set_slug' => $first->product_attribute_set_slug,
                'product_attribute_set_order' => $first->product_attribute_set_order,
                'product_attribute_set_display_layout' => $first->product_attribute_set_display_layout,
                'items' => $item,
            ];
        }

        return $groups;
    }
}

if (! function_exists('render_product_attributes_view_only')) {
    function render_product_attributes_view_only(
        Product $product,
        ProductAttributeSet $attributeSet,
        array $options = [],
    ): View {
        return app()
            ->makeWith(RenderProductAttributesViewOnlySupport::class, [
                'product' => $product,
                'productAttributeSet' => $attributeSet,
            ])
            ->render($options);
    }
}
