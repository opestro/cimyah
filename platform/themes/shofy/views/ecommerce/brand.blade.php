@php
    Theme::set('pageTitle', $brand->name);
@endphp

<section class="tp-shop-area">
    <div class="container position-relative">
        {!! dynamic_sidebar('products_by_brand_top_sidebar') !!}

        @include(Theme::getThemeNamespace('views.ecommerce.includes.products-listing'))

        {!! dynamic_sidebar('products_by_brand_bottom_sidebar') !!}
    </div>
</section>
