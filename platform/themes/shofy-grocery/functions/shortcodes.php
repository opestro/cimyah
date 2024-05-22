<?php

use Botble\Ads\Facades\AdsManager;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Illuminate\Support\Arr;

app()->booted(function () {
    if (is_plugin_active('ecommerce')) {
        Shortcode::modifyAdminConfig('product-categories', function (ShortcodeForm $form) {
            return $form
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()->label(__('Title'))->toArray(),
                )
                ->add(
                    'subtitle',
                    TextField::class,
                    TextFieldOption::make()->label(__('Subtitle'))->toArray(),
                )
                ->remove(['style', 'background_color']);
        });

        Shortcode::modifyAdminConfig('ecommerce-product-groups', function (ShortcodeForm $form) {
            return $form
                ->add(
                    'ads',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Ads'))
                        ->helperText(__('Select ads to show at the left of slider'))
                        ->choices(AdsManager::getData(true, true)
                            ->pluck('name', 'key')
                            ->sortKeys()
                            ->all())
                        ->searchable()
                        ->collapseTrigger('style', 'columns', Arr::get($form->getModel(), 'style') === 'columns')
                        ->toArray(),
                );
        });
    }
});
