<?php

namespace Database\Seeders\Themes\Main;

use Botble\Ecommerce\Database\Seeders\CurrencySeeder;
use Botble\Ecommerce\Database\Seeders\DiscountSeeder;
use Botble\Ecommerce\Database\Seeders\ShippingSeeder;
use Botble\Ecommerce\Database\Seeders\TaxSeeder;
use Botble\Language\Database\Seeders\LanguageSeeder;
use Botble\Theme\Database\Seeders\ThemeSeeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends ThemeSeeder
{
    protected string $themeName = 'shofy';

    public function run(): void
    {
        $this->prepareRun();

        $this->activateTheme($this->themeName);

        $seeders = [];
        foreach ($this->getSeeders() as $seeder) {
            $seeders[Str::afterLast($seeder, '\\')] = $seeder;
        }

        $this->call($seeders);

        $this->finished();
    }

    protected function getSeeders(): array
    {
        return [
            SettingSeeder::class,
            UserSeeder::class,
            LanguageSeeder::class,
            BrandSeeder::class,
            CurrencySeeder::class,
            ProductLabelSeeder::class,
            FaqSeeder::class,
            ProductAttributeSeeder::class,
            CustomerSeeder::class,
            TaxSeeder::class,
            ProductTagSeeder::class,
            ShippingSeeder::class,
            ContactSeeder::class,
            BlogSeeder::class,
            DiscountSeeder::class,
            StoreLocatorSeeder::class,
            ProductOptionSeeder::class,
            MarketplaceSeeder::class,
            AnnouncementSeeder::class,
            TestimonialSeeder::class,
            SimpleSliderSeeder::class,
            ProductCategorySeeder::class,
            ProductCollectionSeeder::class,
            ProductSeeder::class,
            AdsSeeder::class,
            FlashSaleSeeder::class,
            GallerySeeder::class,
            PageSeeder::class,
            MenuSeeder::class,
            ThemeOptionSeeder::class,
            WidgetSeeder::class,
            ReviewSeeder::class,
        ];
    }
}
