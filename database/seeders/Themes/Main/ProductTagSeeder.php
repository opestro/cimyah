<?php

namespace Database\Seeders\Themes\Main;

use Botble\Ecommerce\Models\ProductTag;
use Botble\Slug\Facades\SlugHelper;
use Botble\Theme\Database\Seeders\ThemeSeeder;

class ProductTagSeeder extends ThemeSeeder
{
    public function run(): void
    {
        $tags = [
            [
                'name' => 'Electronic',
            ],
            [
                'name' => 'Mobile',
            ],
            [
                'name' => 'Iphone',
            ],
            [
                'name' => 'Printer',
            ],
            [
                'name' => 'Office',
            ],
            [
                'name' => 'IT',
            ],
        ];

        ProductTag::query()->truncate();

        foreach ($tags as $item) {
            $tag = ProductTag::query()->create($item);

            SlugHelper::createSlug($tag);
        }
    }
}
