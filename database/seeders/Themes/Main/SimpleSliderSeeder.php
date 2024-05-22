<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Facades\MetaBox;
use Botble\Language\Models\LanguageMeta;
use Botble\Setting\Facades\Setting;
use Botble\SimpleSlider\Models\SimpleSlider;
use Botble\SimpleSlider\Models\SimpleSliderItem;
use Botble\Theme\Database\Seeders\ThemeSeeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SimpleSliderSeeder extends ThemeSeeder
{
    public function run(): void
    {
        SimpleSlider::query()->truncate();
        SimpleSliderItem::query()->truncate();

        foreach ($this->getSliders() as $parent) {
            /** @var SimpleSlider $slider */
            $slider = SimpleSlider::query()->create(array_merge(Arr::except($parent, 'children'), [
                'key' => Str::slug($parent['name']),
            ]));

            LanguageMeta::saveMetaData($slider);

            foreach ($parent['children'] as $key => $item) {
                $sliderItem = $slider->sliderItems()->create([
                    ...Arr::except($item, ['metadata']),
                    'order' => $key,
                    'link' => '/products',
                ]);

                foreach ($item['metadata'] as $metaKey => $metaValue) {
                    MetaBox::saveMetaBoxData($sliderItem, $metaKey, $metaValue);
                }
            }
        }

        Setting::set('simple_slider_using_assets', false)->save();
    }

    protected function getSliders(): array
    {
        $this->uploadFiles('sliders');

        return [
            [
                'name' => 'Home slider',
                'description' => 'The main slider on homepage',
                'children' => [
                    [
                        'title' => 'The best tablet Collection 2023',
                        'description' => 'Exclusive offer <span>-35%</span> off this week',
                        'image' => $this->filePath('sliders/slider-1.png'),
                        'metadata' => [
                            'background_color' => '#115061',
                            'is_light' => 0,
                            'subtitle' => 'Starting at <b>$274.00</b>',
                            'button_label' => 'Shop Now',
                        ],
                    ],
                    [
                        'title' => 'The best note book collection 2023',
                        'description' => 'Exclusive offer <span>-10%</span> off this week',
                        'image' => $this->filePath('sliders/slider-2.png'),
                        'metadata' => [
                            'background_color' => '#115061',
                            'is_light' => 0,
                            'subtitle' => 'Starting at <b>$999.00</b>',
                            'button_label' => 'Shop Now',
                        ],
                    ],
                    [
                        'title' => 'The best phone collection 2023',
                        'description' => 'Exclusive offer <span>-10%</span> off this week',
                        'image' => $this->filePath('sliders/slider-3.png'),
                        'metadata' => [
                            'background_color' => '#E3EDF6',
                            'is_light' => 1,
                            'subtitle' => 'Starting at <b>$999.00</b>',
                            'button_label' => 'Shop Now',
                        ],
                    ],
                ],
            ],
        ];
    }
}
