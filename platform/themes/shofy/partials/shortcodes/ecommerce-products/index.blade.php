@php
    $style = in_array($shortcode->style, ['grid', 'slider', 'simple', 'slider-full-width']) ? $shortcode->style : 'grid';
@endphp

{!! Theme::partial("shortcodes.ecommerce-products.$style", compact('shortcode', 'products', 'ads', 'categoryIds')) !!}
