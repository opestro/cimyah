@php
    Theme::set('pageTitle', $category->name);
@endphp

<section class="tp-shop-area">
    <div class="container position-relative">
        {!! dynamic_sidebar('products_by_category_top_sidebar') !!}

        @include(Theme::getThemeNamespace('views.ecommerce.includes.products-listing'))

        {!! dynamic_sidebar('products_by_category_bottom_sidebar') !!}
    </div>
</section>
