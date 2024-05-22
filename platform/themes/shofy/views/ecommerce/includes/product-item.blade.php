@php
    $style = theme_option('ecommerce_product_item_style', 1);
    $style = in_array($style, [1, 2, 3, 4, 5]) ? $style : 1;

    $layout ??= 'grid';
@endphp

@include(Theme::getThemeNamespace("views.ecommerce.includes.product.style-$style.$layout"))
