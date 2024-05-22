@php
    $style = in_array($shortcode->style, ['grid', 'slider']) ? $shortcode->style : 'grid';
@endphp

{!! Theme::partial("shortcodes.ecommerce-categories.$style", compact('shortcode', 'categories')) !!}
