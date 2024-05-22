<?php

namespace Database\Seeders\Themes\Main;

use Botble\Ads\Models\Ads;
use Botble\Base\Facades\MetaBox;
use Botble\Ecommerce\Models\Product;
use Botble\Theme\Database\Seeders\ThemeSeeder;
use Illuminate\Support\Arr;

class AdsSeeder extends ThemeSeeder
{
    public function run(): void
    {
        Ads::query()->truncate();

        $now = $this->now();

        foreach ($this->getData() as $index => $item) {
            $index++;

            $ads = Ads::query()->create([
                ...Arr::except($item, 'metadata'),
                'name' => "Ads $index",
                'expired_at' => $now->clone()->addYears(5)->toDateString(),
                'location' => 'not_set',
                'order' => $index,
            ]);

            if (isset($item['metadata'])) {
                foreach ($item['metadata'] as $key => $value) {
                    MetaBox::saveMetaBoxData($ads, $key, $value);
                }
            }
        }
    }

    protected function getData(): array
    {
        $this->uploadFiles('banners');
        $this->uploadFiles('gadgets');

        $products = Product::query()
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return [
            [
                'key' => 'UROL9F9ZZVAA',
                'image' => $this->filePath('banners/1.jpg'),
                'url' => '/products',
                'metadata' => [
                    'title' => "Smartphone \n BLU G91 Pro 2022",
                    'subtitle' => 'Sale 20% off all store',
                    'button_label' => 'Shop Now',
                ],
            ],
            [
                'key' => 'B30VDBKO7SBF',
                'image' => $this->filePath('banners/2.jpg'),
                'url' => '/products',
                'metadata' => [
                    'title' => "HyperX Cloud II \n Wireless",
                    'subtitle' => 'Sale 35% off',
                    'button_label' => 'Shop Now',
                ],
            ],
            [
                'key' => 'BN3ZCHLIE95I',
                'image' => $this->filePath('gadgets/gadget-banner-1.jpg'),
                'url' => '/products',
                'metadata' => [
                    'title' => "Selected novelty \n Products",
                    'subtitle' => 'Only $99.00',
                ],
            ],
            [
                'key' => 'QGPRRJ2MPZYA',
                'image' => $this->filePath('gadgets/gadget-banner-2.jpg'),
                'url' => '/products',
                'metadata' => [
                    'title' => "Top Rated \n Products",
                    'subtitle' => 'Only $55.00',
                ],
            ],
            [
                'key' => 'B5ZA76ZWMWAE',
                'image' => $this->filePath('banners/slider-1.png'),
                'url' => $products[0]->url,
                'metadata' => [
                    'title' => 'Samsung Galaxy Tab S6, Wifi Tablet',
                    'subtitle' => 'Tablet Collection 2023',
                    'button_label' => 'Shop Now',
                ],
            ],
            [
                'key' => 'F1LTQS976YPY',
                'image' => $this->filePath('banners/slider-2.png'),
                'url' => $products[1]->url,
                'metadata' => [
                    'title' => 'Samsung Galaxy Tab S6, Wifi Tablet',
                    'subtitle' => 'Tablet Collection 2023',
                    'button_label' => 'Shop Now',
                ],
            ],
            [
                'key' => 'IHPZ2WBSYJUK',
                'image' => $this->filePath('banners/slider-3.png'),
                'url' => $products[2]->url,
                'metadata' => [
                    'title' => 'Samsung Galaxy Tab S6, Wifi Tablet',
                    'subtitle' => 'Tablet Collection 2023',
                    'button_label' => 'Shop Now',
                ],
            ],
        ];
    }
}
