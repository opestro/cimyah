@php
    $dataForFilter = EcommerceHelper::dataForFilter($category ?? null);
    [$categories, $brands, $tags, $rand, $categoriesRequest, $urlCurrent, $categoryId, $maxFilterPrice] = $dataForFilter;
@endphp

<div class="bb-shop-sidebar">
    <form action="{{ URL::current() }}" data-action="route('public.products')" method="GET" class="bb-product-form-filter">
        <input type="hidden" name="sort-by" value="{{ BaseHelper::stringify(request()->query('sort-by')) }}">
        <input type="hidden" name="per-page" value="{{ BaseHelper::stringify(request()->query('per-page')) }}">
        <input type="hidden" name="layout" value="{{ BaseHelper::stringify(request()->query('layout')) }}">
        <input type="hidden" name="page" value="{{ BaseHelper::stringify(request()->query('page')) }}">

        {!! apply_filters('theme_ecommerce_products_filter_before', null, $dataForFilter) !!}

        @include(EcommerceHelper::viewPath('includes.filters.price'))

        @include(EcommerceHelper::viewPath('includes.filters.categories'))

        @if (EcommerceHelper::isEnabledFilterProductsByBrands())
            @include(EcommerceHelper::viewPath('includes.filters.brands'))
        @endif

        @if (EcommerceHelper::isEnabledFilterProductsByTags())
            @include(EcommerceHelper::viewPath('includes.filters.tags'))
        @endif

        @if (EcommerceHelper::isEnabledFilterProductsByAttributes())
            @include(EcommerceHelper::viewPath('includes.filters.attributes'))
        @endif

        {!! apply_filters('theme_ecommerce_products_filter_after', null, $dataForFilter) !!}
    </form>
</div>
