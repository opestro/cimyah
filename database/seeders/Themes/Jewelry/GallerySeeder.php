<?php

namespace Database\Seeders\Themes\Jewelry;

use Database\Seeders\Themes\Main\GallerySeeder as MainGallerySeeder;

class GallerySeeder extends MainGallerySeeder
{
    protected function getData(): array
    {
        return [
            'Radiant Reflections',
            'Glamour Grove',
            'Serene Styles Showcase',
            'Allure Alcove',
            'Glamour Galleria',
            'Beauty Boulevard',
        ];
    }
}
