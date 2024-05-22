<?php

namespace Database\Seeders\Themes\Grocery;

use Database\Seeders\Themes\Main\ProductCategorySeeder as MainProductCategorySeeder;

class ProductCategorySeeder extends MainProductCategorySeeder
{
    protected function getData(): array
    {
        return [
            [
                'name' => 'Frozen Food',
                'image' => $this->filePath('product-categories/1.jpg'),
                'is_featured' => true,
                'children' => [
                    ['name' => 'Baby Food'],
                    ['name' => 'Strawberry'],
                    ['name' => 'Ice Cream'],
                ],
            ],
            [
                'name' => 'Meat & Seafood',
                'image' => $this->filePath('product-categories/2.jpg'),
                'is_featured' => true,
                'children' => [
                    ['name' => 'Chicken'],
                    ['name' => 'Fish'],
                    ['name' => 'Beef'],
                ],
            ],
            [
                'name' => 'Milk & Dairy',
                'image' => $this->filePath('product-categories/3.jpg'),
                'is_featured' => true,
                'children' => [
                    ['name' => 'Milk'],
                    ['name' => 'Cheese'],
                    ['name' => 'Yogurt'],
                ],
            ],
            [
                'name' => 'Beverages',
                'image' => $this->filePath('product-categories/4.jpg'),
                'is_featured' => true,
            ],
            [
                'name' => 'Vegetables',
                'image' => $this->filePath('product-categories/5.jpg'),
                'is_featured' => true,
                'children' => [
                    ['name' => 'Carrot'],
                    ['name' => 'Tomato'],
                    ['name' => 'Potato'],
                ],
            ],
            [
                'name' => 'Fruits',
                'image' => $this->filePath('product-categories/6.jpg'),
                'is_featured' => true,
                'children' => [
                    ['name' => 'Banana'],
                    ['name' => 'Mango'],
                ],
            ],
        ];
    }
}
