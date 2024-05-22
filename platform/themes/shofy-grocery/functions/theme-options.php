<?php

use Botble\Theme\Events\RenderingThemeOptionSettings;
use Botble\Theme\Facades\ThemeOption;

app()->make('events')->listen(RenderingThemeOptionSettings::class, function () {
    ThemeOption::setField([
        'id' => 'logo_light',
        'section_id' => 'opt-text-subsection-logo',
        'type' => 'mediaImage',
        'label' => __('Logo light'),
        'attributes' => [
            'name' => 'logo_light',
        ],
    ]);
});
