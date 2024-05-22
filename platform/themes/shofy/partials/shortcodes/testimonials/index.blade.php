@php
    $style = in_array($shortcode->style, [1, 2, 3]) ? $shortcode->style : 1
@endphp

{!! Theme::partial("shortcodes.testimonials.style-$style", compact('shortcode', 'testimonials')) !!}
