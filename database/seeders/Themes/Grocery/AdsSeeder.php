<?php

namespace Database\Seeders\Themes\Grocery;

use Database\Seeders\Themes\Main\AdsSeeder as MainAdsSeeder;

class AdsSeeder extends MainAdsSeeder
{
    protected function getData(): array
    {
        $this->uploadFiles('banners', 'main');
        $this->uploadFiles('banners');

        return [
            [
                'key' => 'UROL9F9ZZVAA',
                'image' => $this->filePath('banners/1.jpg', 'main'),
                'url' => '/products',
                'metadata' => [
                    'title' => "Smartphone \n BLU G91 Pro 2022",
                    'subtitle' => 'Sale 20% off all store',
                    'button_label' => 'Shop Now',
                ],
            ],
            [
                'key' => 'B30VDBKO7SBF',
                'image' => $this->filePath('banners/2.jpg', 'main'),
                'url' => '/products',
                'metadata' => [
                    'title' => "HyperX Cloud II \n Wireless",
                    'subtitle' => 'Sale 35% off',
                    'button_label' => 'Shop Now',
                ],
            ],
            [
                'key' => 'L1WDL8YGZUOR',
                'image' => $this->filePath('banners/1.jpg'),
                'url' => '/products',
            ],
            [
                'key' => 'GA3K1VZWNMPF',
                'image' => $this->filePath('banners/2.png'),
                'url' => '/products',
            ],
        ];
    }
}
