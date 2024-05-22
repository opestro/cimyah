<?php

namespace Botble\Ecommerce\Services\Products;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Models\Product;
use Botble\Slug\Facades\SlugHelper;

class GetProductBySlugService
{
    public function handle(string $slug, array $params = []): ?Product
    {
        $slug = SlugHelper::getSlug($slug, model: Product::class);

        if (! $slug) {
            return null;
        }

        $condition = [
            'ec_products.id' => $slug->reference_id,
            'ec_products.status' => BaseStatusEnum::PUBLISHED,
        ];

        return get_products(
            [
                'condition' => $condition,
                'take' => 1,
                ...$params,
            ]
        );
    }
}
