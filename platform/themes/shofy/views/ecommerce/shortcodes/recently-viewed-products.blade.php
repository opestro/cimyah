@if ($products->isNotEmpty())
    {!! Theme::partial('shortcodes.ecommerce-products.slider', compact('shortcode', 'products')) !!}
@endif
