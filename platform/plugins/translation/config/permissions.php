<?php

return [
    [
        'name' => 'Localization',
        'flag' => 'plugins.translation',
        'parent_flag' => 'settings.index',
    ],
    [
        'name' => 'Locales',
        'flag' => 'translations.locales',
        'parent_flag' => 'plugins.translation',
    ],
    [
        'name' => 'Theme translations',
        'flag' => 'translations.theme-translations',
        'parent_flag' => 'plugins.translation',
    ],
    [
        'name' => 'Other translations',
        'flag' => 'translations.index',
        'parent_flag' => 'plugins.translation',
    ],
];
