<?php

use Botble\Setting\Facades\Setting;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Facades\ThemeOption;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        if (! theme_option('section_title_shape_decorated', true)) {
            return;
        }

        $themeName = Theme::getThemeName();

        $style = match (true) {
            $themeName === 'shofy' => 'style-1',
            in_array($themeName, ['shofy-fashion', 'shofy-grocery']) => 'style-2',
            default => 'none',
        };

        Setting::set(ThemeOption::prepareFromArray([
            'section_title_shape_decorated' => $style,
        ]))->save();
    }
};
