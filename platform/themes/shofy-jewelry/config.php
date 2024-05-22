<?php

use Botble\Theme\Theme;
use Illuminate\View\View;

return [
    'inherit' => 'shofy',

    'events' => [
        'beforeRenderTheme' => function (Theme $theme) {
            $theme->partialComposer('header.*', function (View $view) {
                $headerTopBackgroundColor = theme_option('header_top_background_color', '#fff');
                $headerTopTextColor = theme_option('header_top_text_color', '#010f1c');
                $headerMainBackgroundColor = theme_option('header_main_background_color', 'transparent');
                $headerMainTextColor = theme_option('header_main_text_color', '#fff');

                $view->with(compact(
                    'headerTopBackgroundColor',
                    'headerTopTextColor',
                    'headerMainBackgroundColor',
                    'headerMainTextColor'
                ));
            });
        },
    ],
];
