<?php

namespace Database\Seeders\Themes\Grocery;

use Database\Seeders\Themes\Main\DatabaseSeeder as MainDatabaseSeeder;

class DatabaseSeeder extends MainDatabaseSeeder
{
    protected string $themeName = 'shofy-grocery';

    protected function getSeeders(): array
    {
        return [
            ...parent::getSeeders(),
            AnnouncementSeeder::class,
            SimpleSliderSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            ReviewSeeder::class,
            AdsSeeder::class,
            PageSeeder::class,
            WidgetSeeder::class,
            ThemeOptionSeeder::class,
        ];
    }
}
