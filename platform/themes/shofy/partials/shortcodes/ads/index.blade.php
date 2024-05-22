@php
    $style = in_array($shortcode->style, [1, 2, 3, 4]) ? $shortcode->style : 1;
@endphp

{!! Theme::partial("shortcodes.ads.style-$style", compact('shortcode', 'ads')) !!}
