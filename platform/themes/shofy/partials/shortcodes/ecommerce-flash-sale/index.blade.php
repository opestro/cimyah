@php
    $style = in_array($shortcode->style, range(1, 2)) ? $shortcode->style : 1;
@endphp

{!! Theme::partial("shortcodes.ecommerce-flash-sale.style-$style", compact('shortcode', 'flashSale')) !!}
