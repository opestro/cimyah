@php
    $style = in_array($shortcode->style, [1, 2]) ? $shortcode->style : 1;
@endphp

{!! Theme::partial("shortcodes.galleries.style-$style", compact('galleries', 'shortcode')) !!}
