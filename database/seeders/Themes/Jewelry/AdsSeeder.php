<?php

namespace Database\Seeders\Themes\Jewelry;

use Database\Seeders\Themes\Main\AdsSeeder as MainAdsSeeder;

class AdsSeeder extends MainAdsSeeder
{
    protected function getData(): array
    {
        $this->uploadFiles('banners');

        return [
            [
                'key' => 'UROL9F9ZZVAA',
                'image' => $this->filePath('banners/1.jpg'),
                'url' => '/products',
                'metadata' => [
                    'title' => "Ardeco pearl \n Rings style 2023",
                    'subtitle' => 'Collection',
                    'button_label' => 'Shop Now',
                ],
            ],
            [
                'key' => 'B30VDBKO7SBF',
                'image' => $this->filePath('banners/2.jpg'),
                'url' => '/products',
                'metadata' => [
                    'title' => 'Tropical Set',
                    'subtitle' => 'Trending',
                    'button_label' => 'Shop Now',
                ],
            ],
            [
                'key' => 'BN3ZCHLIE95I',
                'image' => $this->filePath('banners/3.jpg'),
                'url' => '/products',
                'metadata' => [
                    'title' => 'Gold Jewelry',
                    'subtitle' => 'New Arrival',
                    'button_label' => 'Shop Now',
                ],
            ],
            [
                'key' => 'QGPRRJ2MPZYA',
                'image' => $this->filePath('banners/4.jpg'),
                'url' => '/products',
                'metadata' => [
                    'title' => "Ring gold with \n diamonds",
                    'subtitle' => 'Collection',
                    'button_label' => 'Shop Now',
                ],
            ],
        ];
    }
}
