@php
    $style = in_array($shortcode->style, [1, 2, 3, 4]) ? $shortcode->style : 1;
@endphp

{!! Theme::partial("shortcodes.site-features.style-$style", compact('shortcode', 'tabs')) !!}
