<?php

use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\FieldOptions\UiSelectorFieldOption;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\UiSelectorField;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Theme\Facades\Theme;

app()->booted(function () {
    if (! is_plugin_active('gallery')) {
        return;
    }

    add_filter('galleries_box_template_view', function () {
        return Theme::getThemeNamespace('partials.shortcodes.galleries.index');
    });

    Shortcode::setPreviewImage('gallery', Theme::asset()->url('images/shortcodes/galleries/style-1.png'));

    Shortcode::modifyAdminConfig('gallery', function (ShortcodeForm $form) {
        $styles = [];

        foreach (range(1, 2) as $i) {
            $styles[$i] = [
                'label' => __('Style :number', ['number' => $i]),
                'image' => Theme::asset()->url(sprintf('images/shortcodes/galleries/style-%s.png', $i)),
            ];
        }

        return $form
            ->addBefore(
                'title',
                'style',
                UiSelectorField::class,
                UiSelectorFieldOption::make()
                    ->choices($styles)
                    ->collapsible('style')
                    ->toArray()
            )
            ->remove('title')
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
                    ->toArray()
            )
            ->add(
                'subtitle',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Subtitle'))
                    ->toArray()
            );
    });
});
