<?php

namespace Database\Seeders\Themes\Main;

use Botble\Ecommerce\Models\ProductLabel;
use Illuminate\Database\Seeder;

class ProductLabelSeeder extends Seeder
{
    public function run(): void
    {
        ProductLabel::query()->truncate();

        $productCollections = [
            [
                'name' => 'Hot',
                'color' => '#AC2200',
            ],
            [
                'name' => 'New',
                'color' => '#006554',
            ],
            [
                'name' => 'Sale',
                'color' => '#9A3B00',
            ],
        ];

        foreach ($productCollections as $item) {
            ProductLabel::query()->create($item);
        }
    }
}
