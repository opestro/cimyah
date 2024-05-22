<?php

use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\UiSelectorFieldOption;
use Botble\Base\Forms\Fields\GoogleFontsField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\UiSelectorField;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Arr;

app()->booted(function () {
    if (! is_plugin_active('simple-slider')) {
        return;
    }

    add_filter(SIMPLE_SLIDER_VIEW_TEMPLATE, function () {
        return Theme::getThemeNamespace('partials.shortcodes.simple-slider.index');
    }, 120);

    Shortcode::modifyAdminConfig('simple-slider', function (ShortcodeForm $form) {
        $styles = [];

        foreach (range(1, 5) as $i) {
            $styles[$i] = [
                'label' => __('Style :number', ['number' => $i]),
                'image' => Theme::asset()->url(sprintf('images/shortcodes/simple-slider/style-%s.png', $i)),
            ];
        }

        $styles['full-width'] = [
            'label' => __('Full width'),
        ];

        $form
            ->addBefore(
                'key',
                'style',
                UiSelectorField::class,
                UiSelectorFieldOption::make()
                    ->choices($styles)
                    ->selected(Arr::get($form->getModel(), 'style', 1))
                    ->helperText(__('Full width style will only display the slider image without any text or button. The recommended image dimension is 1920x512 px.'))
                    ->toArray()
            )
            ->add(
                'customize_font_family_of_description',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(__('Customize font family of description text?'))
                    ->defaultValue(false)
                    ->helperText(__('If enabled, you can select font family for description text. Otherwise, it will use the default font family.'))
                    ->collapsible('customize_font_family_of_description')
                    ->value(Arr::get($form->getModel(), 'customize_font_family_of_description', theme_option('tp_cursive_font') ? '1' : '0'))
                    ->toArray()
            )
            ->add(
                'font_family_of_description',
                GoogleFontsField::class,
                SelectFieldOption::make()
                    ->label(__('Font family for description text'))
                    ->defaultValue(theme_option('tp_cursive_font', 'Oregano'))
                    ->collapseTrigger(
                        'customize_font_family_of_description',
                        true,
                        Arr::get($form->getModel(), 'customize_font_family_of_description', false)
                    )
                    ->toArray()
            );

        foreach (range(1, 4) as $i) {
            $form->add(
                "shape_$i",
                MediaImageField::class,
                MediaImageFieldOption::make()->label(__('Shape :number', ['number' => $i]))->toArray()
            );
        }

        $form
            ->add(
                'is_autoplay',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Is autoplay?'))
                    ->choices(['no' => __('No'), 'yes' => __('Yes')])
                    ->toArray()
            )
            ->add(
                'autoplay_speed',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Autoplay speed (if autoplay enabled)'))
                    ->choices(
                        array_combine(
                            [2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000],
                            [2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000]
                        )
                    )
                    ->toArray()
            )
            ->add(
                'is_loop',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Loop?'))
                    ->choices(['yes' => __('Continuously'), 'no' => __('Stop on the last slide')])
                    ->toArray()
            );

        return $form;
    });
});
