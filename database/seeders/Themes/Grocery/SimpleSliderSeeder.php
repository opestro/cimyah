<?php

namespace Database\Seeders\Themes\Grocery;

use Database\Seeders\Themes\Main\SimpleSliderSeeder as MainSimpleSliderSeeder;

class SimpleSliderSeeder extends MainSimpleSliderSeeder
{
    protected function getSliders(): array
    {
        $this->uploadFiles('sliders');

        return [
            [
                'name' => 'Home slider',
                'description' => 'The main slider on homepage',
                'children' => [
                    [
                        'title' => 'The Online <br> Grocery Store',
                        'image' => $this->filePath('sliders/slider-1.png'),
                        'metadata' => [
                            'button_label' => 'Shop Now',
                        ],
                    ],
                ],
            ],
        ];
    }
}
