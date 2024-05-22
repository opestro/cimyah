@if ($categories->isNotEmpty())
    <div class="bb-product-filter">
        <h4 class="bb-product-filter-title">{{ __('Categories') }}</h4>

        <div class="bb-product-filter-content">
            @include(EcommerceHelper::viewPath('includes.filters.categories-list'))
        </div>
    </div>
@endif
