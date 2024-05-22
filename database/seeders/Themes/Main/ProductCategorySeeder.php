<?php

namespace Database\Seeders\Themes\Main;

use Botble\Ecommerce\Database\Seeders\Traits\HasProductCategorySeeder;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Theme\Database\Seeders\ThemeSeeder;

class ProductCategorySeeder extends ThemeSeeder
{
    use HasProductCategorySeeder;

    public function run(): void
    {
        $this->uploadFiles('product-categories');

        ProductCategory::query()->truncate();

        foreach ($this->getData() as $index => $item) {
            $this->createCategoryItem($index, $item);
        }
    }

    protected function getData(): array
    {
        return [
            [
                'name' => 'New Arrivals',
                'icon' => 'ti ti-home',
            ],
            [
                'name' => 'Electronics',
                'icon' => 'ti ti-device-tv',
                'children' => [
                    [
                        'name' => 'Featured',
                        'image' => $this->filePath('product-categories/menu-1.jpg'),
                        'children' => [
                            ['name' => 'New Arrivals'],
                            ['name' => 'Best Sellers'],
                            [
                                'name' => 'Mobile Phone',
                                'image' => $this->filePath('product-categories/2.png'),
                                'is_featured' => true,
                            ],
                        ],
                    ],
                    [
                        'name' => 'Computers & Laptops',
                        'image' => $this->filePath('product-categories/menu-2.jpg'),
                        'is_featured' => true,
                        'children' => [
                            ['name' => 'Top Brands'],
                            ['name' => 'Weekly Best Selling'],
                            [
                                'name' => 'CPU Heat Pipes',
                                'image' => $this->filePath('product-categories/3.png'),
                                'is_featured' => true,
                            ],
                            [
                                'name' => 'CPU Coolers',
                                'image' => $this->filePath('product-categories/category-thumb-9.jpg'),
                            ],
                        ],
                    ],
                    [
                        'name' => 'Accessories',
                        'image' => $this->filePath('product-categories/menu-3.jpg'),
                        'children' => [
                            [
                                'name' => 'Headphones',
                                'image' => $this->filePath('product-categories/1.png'),
                                'is_featured' => true,
                            ],
                            [
                                'name' => 'Wireless Headphones',
                                'image' => $this->filePath('product-categories/category-thumb-1.jpg'),
                            ],
                            [
                                'name' => 'TWS Earphones',
                                'image' => $this->filePath('product-categories/category-thumb-6.jpg'),
                            ],
                            [
                                'name' => 'Smart Watch',
                                'image' => $this->filePath('product-categories/4.png'),
                                'is_featured' => true,
                            ],
                        ],
                    ],
                    [
                        'name' => 'Gaming Console',
                        'image' => $this->filePath('product-categories/category-thumb-8.jpg'),
                    ],
                    [
                        'name' => 'Playstation',
                        'image' => $this->filePath('product-categories/category-thumb-12.jpg'),
                    ],
                ],
            ],
            [
                'name' => 'Gifts',
                'icon' => 'ti ti-gift',
            ],
            [
                'name' => 'Computers',
                'icon' => 'ti ti-device-laptop',
                'children' => [
                    [
                        'name' => 'Desktop',
                        'icon' => 'ti ti-device-desktop',
                        'image' => $this->filePath('product-categories/category-thumb-5.jpg'),
                    ],
                    [
                        'name' => 'Laptop',
                        'icon' => 'ti ti-device-laptop',
                        'image' => $this->filePath('product-categories/category-thumb-3.jpg'),
                    ],
                    [
                        'name' => 'Tablet',
                        'icon' => 'ti ti-device-tablet',
                        'image' => $this->filePath('product-categories/category-thumb-4.jpg'),
                    ],
                    ['name' => 'Accessories', 'icon' => 'ti ti-keyboard'],
                ],
            ],
            [
                'name' => 'Smartphones & Tablets',
                'image' => $this->filePath('product-categories/category-thumb-10.jpg'),
                'icon' => 'ti ti-device-mobile',
            ],
            [
                'name' => 'TV,
                Video & Music',
                'icon' => 'ti ti-device-tv',
            ],
            [
                'name' => 'Cameras',
                'icon' => 'ti ti-camera',
            ],
            [
                'name' => 'Cooking',
                'icon' => 'ti ti-grill-spatula',
            ],
            [
                'name' => 'Accessories',
                'icon' => 'ti ti-building-store',
                'children' => [
                    [
                        'name' => 'With Bluetooth',
                        'image' => $this->filePath('product-categories/5.png'),
                        'is_featured' => true,
                    ],
                ],
            ],
            [
                'name' => 'Sports',
                'icon' => 'ti ti-ball-football',
            ],
            [
                'name' => 'Electronics Gadgets',
                'icon' => 'ti ti-cpu-2',
                'children' => [
                    ['name' => 'Micrscope'],
                    ['name' => 'Remote Control'],
                    ['name' => 'Monitor'],
                    ['name' => 'Thermometer'],
                    ['name' => 'Backpack'],
                    [
                        'name' => 'Headphones',
                        'image' => $this->filePath('product-categories/category-thumb-1.jpg'),
                    ],
                ],
            ],
        ];
    }
}
