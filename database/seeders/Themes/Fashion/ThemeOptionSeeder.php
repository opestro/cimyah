<?php

namespace Database\Seeders\Themes\Fashion;

use Database\Seeders\Themes\Main\ThemeOptionSeeder as MainThemeOptionSeeder;

class ThemeOptionSeeder extends MainThemeOptionSeeder
{
    protected function getData(): array
    {
        return [
            ...parent::getData(),
            'primary_color' => '#821F40',
            'tp_primary_font' => 'Jost',
            'header_style' => 2,
            'ecommerce_product_item_style' => 2,
            'section_title_shape_decorated' => 'style-3',
            'header_main_background_color' => '#fff',
            'header_main_text_color' => '#010f1c',
        ];
    }
}
