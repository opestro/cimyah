<?php

namespace Database\Seeders\Themes\Jewelry;

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
                        'title' => 'Shine bright',
                        'description' => 'The original',
                        'image' => $this->filePath('sliders/slider-1.png'),
                        'metadata' => [
                            'button_label' => 'Discover Now',
                        ],
                    ],
                    [
                        'title' => 'Creative Design',
                        'description' => 'The original',
                        'image' => $this->filePath('sliders/slider-2.png'),
                        'metadata' => [
                            'button_label' => 'Discover Now',
                        ],
                    ],
                    [
                        'title' => 'Gold Plateted',
                        'description' => 'The original',
                        'image' => $this->filePath('sliders/slider-3.png'),
                        'metadata' => [
                            'button_label' => 'Discover Now',
                        ],
                    ],
                    [
                        'title' => 'Unique shapes',
                        'description' => 'The original',
                        'image' => $this->filePath('sliders/slider-4.png'),
                        'metadata' => [
                            'button_label' => 'Discover Now',
                        ],
                    ],
                ],
            ],
        ];
    }
}
