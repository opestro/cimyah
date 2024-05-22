<?php

namespace Botble\Ecommerce\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;

class QuickViewController extends BaseController
{
    public function show(Request $request, int|string|null $id = null)
    {
        $id ??= $request->input('product_id');

        $product = get_products([
            'condition' => [
                'ec_products.id' => $id,
            ],
            'take' => 1,
            'with' => [
                'slugable',
                'tags',
                'tags.slugable',
                'options',
                'options.values',
            ],
        ] + EcommerceHelper::withReviewsParams());

        if (! $product) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('This product is not available.'));
        }

        [$productImages, $productVariation, $selectedAttrs] = EcommerceHelper::getProductVariationInfo($product);

        $data = apply_filters('ecommerce_quick_view_data', [
            'product' => $product,
            'productImages' => $productImages,
            'productVariation' => $productVariation,
            'selectedAttrs' => $selectedAttrs,
        ]);

        $view = apply_filters('ecommerce_quick_view_template', $this->getQuickViewTemplate());

        return $this
            ->httpResponse()
            ->setData(view($view, $data)->render());
    }

    protected function getQuickViewTemplate(): string
    {
        if (view()->exists($view = Theme::getThemeNamespace('views.ecommerce.quick-view'))) {
            return $view;
        }

        if (view()->exists($view = Theme::getThemeNamespace('partials.ecommerce.quick-view'))) {
            return $view;
        }

        if (view()->exists($view = Theme::getThemeNamespace('partials.quick-view'))) {
            return $view;
        }

        return EcommerceHelper::viewPath('includes.quick-view');
    }
}
