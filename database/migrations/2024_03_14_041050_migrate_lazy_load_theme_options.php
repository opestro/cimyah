<?php

use Botble\Setting\Facades\Setting;
use Botble\Theme\Facades\ThemeOption;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        Setting::set(ThemeOption::prepareFromArray([
            'lazy_load_images' => theme_option('lazy_load_image', false),
            'lazy_load_placeholder_image' => theme_option('placeholder_image'),
        ]));

        Setting::delete([
            ThemeOption::getOptionKey('lazy_load_image'),
            ThemeOption::getOptionKey('placeholder_image'),
        ]);

        Setting::save();
    }
};
