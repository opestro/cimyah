<?php

use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\TextField;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;

app()->booted(function () {
    if (is_plugin_active('blog')) {
        Shortcode::modifyAdminConfig('blog-posts', function (ShortcodeForm $form) {
            return $form->addAfter(
                'title',
                'subtitle',
                TextField::class,
                TextFieldOption::make()->label(__('Subtitle'))->colspan(2)->toArray()
            );
        });
    }
});
