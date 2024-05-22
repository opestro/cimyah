<?php

namespace Botble\SalePopup\Http\Controllers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Media\Facades\RvMedia;
use Botble\SalePopup\Support\SalePopupHelper;

class SalePopupController extends BaseController
{
    public function ajaxSalePopup(SalePopupHelper $salePopupHelper): ?string
    {
        $limit = (int) $salePopupHelper->getSetting('limit_products', 20);
        $loadProductFrom = $salePopupHelper->getSetting('load_product_from', 'featured_products');

        if ($loadProductFrom === 'featured_products') {
            $products = get_products([
                'condition' => [
                    'ec_products.status' => BaseStatusEnum::PUBLISHED,
                    'ec_products.is_variation' => false,
                    'ec_products.is_featured' => true,
                ],
                'take' => $limit,
                'with' => [
                    'slugable',
                ],
            ]);
        } else {
            $products = get_products_by_collections([
                'take' => $limit,
                'with' => [
                    'slugable',
                ],
                'collections' => [
                    'by' => 'id',
                    'value_in' => [$loadProductFrom],
                ],
            ]);
        }

        $urls = [];
        $images = [];

        foreach ($products as $product) {
            $urls[] = $product->url;
            $images[] = RvMedia::getImageUrl($product->image, 'thumb', false, RvMedia::getDefaultImage());
        }

        if ($products->isEmpty()) {
            return null;
        }

        return view('plugins/sale-popup::sale-popup', compact('salePopupHelper', 'products', 'images', 'urls'))->render();
    }
}
