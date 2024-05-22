<?php

namespace Botble\SimpleSlider\Forms\Settings;

use Botble\Setting\Forms\SettingForm;
use Botble\SimpleSlider\Http\Requests\Settings\SimpleSliderSettingRequest;

class SimpleSliderSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/simple-slider::simple-slider.settings.title'))
            ->setSectionDescription(trans('plugins/simple-slider::simple-slider.settings.description'))
            ->setValidatorClass(SimpleSliderSettingRequest::class)
            ->add('simple_slider_using_assets', 'onOffCheckbox', [
                'label' => trans('plugins/simple-slider::simple-slider.settings.using_assets'),
                'value' => setting('simple_slider_using_assets', true),
                'wrapper' => [
                    'class' => 'mb-0',
                ],
            ])
            ->add('simple_slider_assets', 'html', [
                'html' => view('plugins/simple-slider::partials.simple-slider-asset-fields')->render(),
            ]);
    }
}
