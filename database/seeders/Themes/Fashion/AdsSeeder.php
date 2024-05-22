<?php

namespace Database\Seeders\Themes\Fashion;

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
                    'title' => 'Smartphone \n BLU G91 Pro 2022',
                    'subtitle' => 'Sale 20% off all store',
                    'button_label' => 'Shop Now',
                ],
            ],
            [
                'key' => 'B30VDBKO7SBF',
                'image' => $this->filePath('banners/2.jpg', 'main'),
                'url' => '/products',
                'metadata' => [
                    'title' => 'HyperX Cloud II \n Wireless',
                    'subtitle' => 'Sale 35% off',
                    'button_label' => 'Shop Now',
                ],
            ],
            [
                'key' => 'WXAUTIJV1QU0',
                'image' => $this->filePath('banners/1.jpg'),
                'url' => '/products',
                'metadata' => [
                    'title' => "T-Shirt Tunic \n Tops Blouse",
                    'button_label' => 'Shop Now',
                ],
            ],
            [
                'key' => '7Z5RXBBWV7J2',
                'image' => $this->filePath('banners/2.jpg'),
                'url' => '/products',
                'metadata' => [
                    'title' => "Satchel Tote \n Crossbody Bags",
                    'button_label' => 'Shop Now',
                ],
            ],
            [
                'key' => 'JY08TDO8FG1E',
                'image' => $this->filePath('banners/3.jpg'),
                'url' => '/products',
                'metadata' => [
                    'title' => "Men's Tennis \n Walking Shoes",
                    'button_label' => 'Shop Now',
                ],
            ],
            [
                'key' => 'VKJNCBIBQC1O',
                'image' => $this->filePath('banners/4.jpg'),
                'url' => '/products',
                'metadata' => [
                    'title' => "Short Sleeve Tunic \n Tops Casual Swing",
                    'button_label' => 'Explore More',
                ],
            ],
        ];
    }
}
