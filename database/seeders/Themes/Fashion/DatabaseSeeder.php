<?php

namespace Database\Seeders\Themes\Fashion;

use Database\Seeders\Themes\Main\DatabaseSeeder as MainDatabaseSeeder;

class DatabaseSeeder extends MainDatabaseSeeder
{
    protected string $themeName = 'shofy-fashion';

    protected function getSeeders(): array
    {
        return [
            ...parent::getSeeders(),
            AnnouncementSeeder::class,
            SimpleSliderSeeder::class,
            AdsSeeder::class,
            ProductCategorySeeder::class,
            ProductCollectionSeeder::class,
            ProductSeeder::class,
            ReviewSeeder::class,
            GallerySeeder::class,
            PageSeeder::class,
            MenuSeeder::class,
            ThemeOptionSeeder::class,
            WidgetSeeder::class,
        ];
    }
}
