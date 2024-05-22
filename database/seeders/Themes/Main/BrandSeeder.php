<?php

namespace Database\Seeders\Themes\Main;

use Botble\Ecommerce\Models\Brand;
use Botble\Slug\Facades\SlugHelper;
use Botble\Theme\Database\Seeders\ThemeSeeder;

class BrandSeeder extends ThemeSeeder
{
    public function run(): void
    {
        $this->uploadFiles('brands');

        $brands = [
            [
                'name' => 'FoodPound',
                'description' => 'New Snacks Release',
                'logo' => $this->filePath('brands/1.png'),
            ],
            [
                'name' => 'iTea JSC',
                'description' => 'Happy Tea 100% Organic. From $29.9',
                'logo' => $this->filePath('brands/2.png'),
            ],
            [
                'name' => 'Soda Brand',
                'description' => 'Fresh Meat Sausage. BUY 2 GET 1!',
                'logo' => $this->filePath('brands/3.png'),
            ],
            [
                'name' => 'Shofy',
                'description' => 'Fresh Meat Sausage. BUY 2 GET 1!',
                'logo' => $this->filePath('brands/4.png'),
            ],
            [
                'name' => 'Soda Brand',
                'description' => 'Fresh Meat Sausage. BUY 2 GET 1!',
                'logo' => $this->filePath('brands/5.png'),
            ],
        ];

        Brand::query()->truncate();

        foreach ($brands as $key => $brand) {
            $brand = Brand::query()->create([
                ...$brand,
                'order' => $key,
                'is_featured' => true,
            ]);

            SlugHelper::createSlug($brand);
        }
    }
}
