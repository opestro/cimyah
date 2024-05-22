<?php

namespace Botble\SimpleSlider\Support;

use Botble\SimpleSlider\Forms\SimpleSliderItemForm;

class SimpleSliderSupport
{
    public static function registerResponsiveImageSizes(): void
    {
        SimpleSliderItemForm::extend(function (SimpleSliderItemForm $form) {
            $form
                ->addAfter('image', 'tablet_image', 'mediaImage', [
                    'label' => __('Tablet Image'),
                    'help_block' => [
                        'text' => __(
                            'For devices with width from 768px to 1200px, if empty, will use the image from the desktop.'
                        ),
                    ],
                    'metadata' => true,
                ])
                ->addAfter('tablet_image', 'mobile_image', 'mediaImage', [
                    'label' => __('Mobile Image'),
                    'help_block' => [
                        'text' => __(
                            'For devices with width less than 768px, if empty, will use the image from the tablet.'
                        ),
                    ],
                    'metadata' => true,
                ]);

            return $form;
        }, 127);
    }
}
