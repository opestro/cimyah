@php
    Theme::set('pageTitle', $tag->name);
@endphp

<section class="tp-shop-area">
    <div class="container position-relative">
        {!! dynamic_sidebar('products_by_tag_top_sidebar') !!}

        @include(Theme::getThemeNamespace('views.ecommerce.includes.products-listing'))

        {!! dynamic_sidebar('products_by_tag_bottom_sidebar') !!}
    </div>
</section>
