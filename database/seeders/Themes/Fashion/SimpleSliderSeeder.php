<?php

namespace Database\Seeders\Themes\Fashion;

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
                        'title' => 'The Clothing Collection',
                        'description' => 'New Arrivals 2023',
                        'image' => $this->filePath('sliders/slider-1.png'),
                        'metadata' => [
                            'button_label' => 'Shop Collection',
                        ],
                    ],
                    [
                        'title' => 'The Summer Collection',
                        'description' => 'Best Selling 2023',
                        'image' => $this->filePath('sliders/slider-2.png'),
                        'metadata' => [
                            'button_label' => 'Shop Collection',
                        ],
                    ],
                    [
                        'title' => 'Amazing New designs',
                        'description' => 'Winter Has Arrived',
                        'image' => $this->filePath('sliders/slider-3.png'),
                        'metadata' => [
                            'button_label' => 'Shop Collection',
                        ],
                    ],
                ],
            ],
        ];
    }
}
