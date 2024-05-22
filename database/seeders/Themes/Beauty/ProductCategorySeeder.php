<?php

namespace Database\Seeders\Themes\Beauty;

use Database\Seeders\Themes\Main\ProductCategorySeeder as MainProductCategorySeeder;

class ProductCategorySeeder extends MainProductCategorySeeder
{
    protected function getData(): array
    {
        return [
            [
                'name' => 'Discover Skincare',
                'image' => $this->filePath('product-categories/1.jpg'),
                'is_featured' => true,
            ],
            [
                'name' => 'Clothing',
                'image' => $this->filePath('product-categories/4.jpg'),
                'is_featured' => true,
                'children' => [
                    [
                        'name' => 'Men\'s Clothing',
                        'children' => [
                            ['name' => 'T-Shirts'],
                            ['name' => 'Jeans'],
                            ['name' => 'Suits'],
                        ],
                    ],
                    [
                        'name' => 'Women\'s Clothing',
                        'image' => $this->filePath('product-categories/2.jpg'),
                        'is_featured' => true,
                        'children' => [
                            ['name' => 'Dresses'],
                            ['name' => 'Blouses'],
                            ['name' => 'Pants'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Accessories',
                'image' => $this->filePath('product-categories/5.jpg'),
                'is_featured' => true,
                'children' => [
                    ['name' => 'Hats'],
                    ['name' => 'Scarves'],
                    ['name' => 'Jewelry'],
                ],
            ],
            [
                'name' => 'Shoes',
                'is_featured' => true,
                'image' => $this->filePath('product-categories/6.jpg'),
            ],
            [
                'name' => 'Sportswear',
                'children' => [
                    [
                        'name' => 'Running Shoes',
                        'image' => $this->filePath('product-categories/3.jpg'),
                        'is_featured' => true,
                    ],
                    ['name' => 'Activewear'],
                ],
            ],
        ];
    }
}
