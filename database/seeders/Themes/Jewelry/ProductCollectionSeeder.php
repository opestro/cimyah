<?php

namespace Database\Seeders\Themes\Jewelry;

use Database\Seeders\Themes\Main\ProductCollectionSeeder as MainProductCollectionSeeder;

class ProductCollectionSeeder extends MainProductCollectionSeeder
{
    protected function getData(): array
    {
        return [
            'Trendy Wardrobe Essentials',
            'Fashion Forward Finds',
            'Chic & Stylish Collection',
            'Weekly Fashion Picks',
        ];
    }
}
