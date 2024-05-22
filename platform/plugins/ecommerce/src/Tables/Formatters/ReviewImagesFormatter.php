<?php

namespace Botble\Ecommerce\Tables\Formatters;

use Botble\Base\Facades\Html;
use Botble\Media\Facades\RvMedia;
use Botble\Table\Formatter;

class ReviewImagesFormatter implements Formatter
{
    public function format($value, $row): string
    {
        if (! is_array($value)) {
            return '&mdash;';
        }

        $count = count($value);

        if ($count == 0) {
            return '&mdash;';
        }

        $galleryID = 'images-group-' . $row->getKey();

        $value = array_values($value);

        $html = Html::tag(
            'a',
            Html::image(
                RvMedia::getImageUrl($value[0], 'thumb'),
                RvMedia::getImageUrl($value[0]),
                [
                    'width' => 60,
                    'class' => 'm-1 img-thumbnail',
                ]
            )->toHtml(),
            [
                'href' => RvMedia::getImageUrl($value[0]),
                'data-bb-lightbox',
            ]
        );

        if (isset($value[1])) {
            if ($count == 2) {
                $html .= Html::image(
                    RvMedia::getImageUrl($value[1], 'thumb'),
                    RvMedia::getImageUrl($value[1]),
                    [
                        'width' => 60,
                        'class' => 'm-1 img-thumbnail',
                        'href' => RvMedia::getImageUrl($value[1]),
                        'data-bb-lightbox' => $galleryID,
                    ]
                );
            } elseif ($count > 2) {
                $html .= Html::tag(
                    'a',
                    Html::image(
                        RvMedia::getImageUrl($value[1], 'thumb'),
                        RvMedia::getImageUrl($value[1]),
                        [
                            'width' => 60,
                            'class' => 'm-1 img-thumbnail',
                            'src' => RvMedia::getImageUrl($value[1]),
                        ]
                    )->toHtml() . Html::tag('span', '+' . ($count - 2))->toHtml(),
                    [
                        'class' => 'more-review-images',
                        'href' => RvMedia::getImageUrl($value[1]),
                        'data-bb-lightbox' => $galleryID,
                    ]
                );
            }
        }

        if ($count > 2) {
            foreach ($value as $index => $image) {
                if ($index > 1) {
                    $html .= Html::image(
                        RvMedia::getImageUrl($image, 'thumb'),
                        RvMedia::getImageUrl($image),
                        [
                            'width' => 60,
                            'class' => 'd-none',
                            'href' => RvMedia::getImageUrl($image),
                            'data-bb-lightbox' => $galleryID,
                        ]
                    );
                }
            }
        }

        return $html;
    }
}
