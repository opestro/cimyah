@php
    Theme::layout('full-width');
@endphp

<section class="tp-shop-area">
    <div class="container position-relative pt-50 pb-50">
        {!! dynamic_sidebar('products_listing_top_sidebar') !!}

        @include(Theme::getThemeNamespace('views.ecommerce.includes.products-listing'))

        {!! dynamic_sidebar('products_listing_bottom_sidebar') !!}
    </div>
</section>
