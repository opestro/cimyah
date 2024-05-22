<?php

namespace Database\Seeders\Themes\Fashion;

use Database\Seeders\Themes\Main\ProductCategorySeeder as MainProductCategorySeeder;

class ProductCategorySeeder extends MainProductCategorySeeder
{
    protected function getData(): array
    {
        return [
            [
                'name' => 'Bags',
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
            ['name' => 'Footwear', 'image' => $this->filePath('product-categories/6.jpg'), 'is_featured' => true],
            [
                'name' => 'Accessories',
                'children' => [
                    ['name' => 'Hats'],
                    ['name' => 'Scarves'],
                    ['name' => 'Jewelry'],
                ],
            ],
            [
                'name' => 'Sportswear',
                'children' => [
                    ['name' => 'Activewear'],
                    ['name' => 'Running Shoes', 'image' => $this->filePath('product-categories/3.jpg'), 'is_featured' => true],
                ],
            ],
            ['name' => 'Outerwear', 'image' => $this->filePath('product-categories/5.jpg'), 'is_featured' => true],
        ];
    }
}
