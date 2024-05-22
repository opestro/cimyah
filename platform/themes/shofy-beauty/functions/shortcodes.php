<?php

use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\TextField;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;

app()->booted(function () {
    if (is_plugin_active('ecommerce')) {
        Shortcode::modifyAdminConfig('ecommerce-categories', function (ShortcodeForm $form) {
            return $form
                ->add(
                    'button_view_more_label',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Button view more label'))
                        ->value(__('View more'))
                        ->toArray()
                )
                ->add(
                    'button_label',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Button label'))
                        ->toArray()
                )
                ->add(
                    'button_url',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Button URL'))
                        ->toArray()
                );
        });

        Shortcode::modifyAdminConfig('ecommerce-products', function (ShortcodeForm $form) {
            return $form
                ->add(
                    'button_label',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Button label'))
                        ->toArray()
                )
                ->add(
                    'button_url',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Button URL'))
                        ->toArray()
                );
        });
    }
});
