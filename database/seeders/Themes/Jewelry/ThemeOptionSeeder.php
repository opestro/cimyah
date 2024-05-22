<?php

namespace Database\Seeders\Themes\Jewelry;

use Database\Seeders\Themes\Main\ThemeOptionSeeder as MainThemeOptionSeeder;

class ThemeOptionSeeder extends MainThemeOptionSeeder
{
    protected function getData(): array
    {
        return [
            ...parent::getData(),
            'primary_color' => '#BD844C',
            'tp_primary_font' => 'Jost',
            'header_style' => 4,
            'ecommerce_product_item_style' => 4,
            'section_title_shape_decorated' => 'none',
            'header_top_background_color' => '#fff',
            'header_top_text_color' => '#010f1c',
            'header_main_background_color' => '#fff',
            'header_main_text_color' => '#010f1c',
        ];
    }
}
