<?php

namespace Botble\SimpleSlider\Providers;

use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Supports\ServiceProvider;
use Botble\Shortcode\Compilers\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\SimpleSlider\Models\SimpleSlider;
use Botble\Theme\Facades\Theme;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (function_exists('shortcode')) {
            add_shortcode(
                'simple-slider',
                trans('plugins/simple-slider::simple-slider.simple_slider_shortcode_name'),
                trans('plugins/simple-slider::simple-slider.simple_slider_shortcode_description'),
                [$this, 'render']
            );

            shortcode()->setPreviewImage(
                'simple-slider',
                asset('vendor/core/plugins/simple-slider/images/ui-blocks/simple-slider.png')
            );

            shortcode()->setAdminConfig('simple-slider', function (array $attributes) {
                return ShortcodeForm::createFromArray($attributes)
                    ->add(
                        'key',
                        SelectField::class,
                        SelectFieldOption::make()
                            ->label(trans('plugins/simple-slider::simple-slider.select_slider'))
                            ->choices(SimpleSlider::query()
                                ->wherePublished()
                                ->pluck('name', 'key')
                                ->all())
                            ->toArray()
                    );
            });
        }
    }

    public function render(Shortcode $shortcode): View|Factory|Application|null
    {
        $slider = SimpleSlider::query()
            ->wherePublished()
            ->where('key', $shortcode->key)
            ->first();

        if (empty($slider) || $slider->sliderItems->isEmpty()) {
            return null;
        }

        if (setting('simple_slider_using_assets', true) && defined('THEME_OPTIONS_MODULE_SCREEN_NAME')) {
            $version = '1.0.2';
            $dist = asset('vendor/core/plugins/simple-slider');

            Theme::asset()
                ->container('footer')
                ->usePath(false)
                ->add(
                    'simple-slider-owl-carousel-css',
                    $dist . '/libraries/owl-carousel/owl.carousel.css',
                    [],
                    [],
                    $version
                )
                ->add('simple-slider-css', $dist . '/css/simple-slider.css', [], [], $version)
                ->add(
                    'simple-slider-owl-carousel-js',
                    $dist . '/libraries/owl-carousel/owl.carousel.js',
                    ['jquery'],
                    [],
                    $version
                )
                ->add('simple-slider-js', $dist . '/js/simple-slider.js', ['jquery'], [], $version);
        }

        return view(apply_filters(SIMPLE_SLIDER_VIEW_TEMPLATE, 'plugins/simple-slider::sliders'), [
            'sliders' => $slider->sliderItems,
            'shortcode' => $shortcode,
            'slider' => $slider,
        ]);
    }
}
