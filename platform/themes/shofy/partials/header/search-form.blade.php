<x-plugins-ecommerce::fronts.ajax-search class="tp-header-search">
    <div class="tp-header-search-wrapper d-flex align-items-center">
        <div class="tp-header-search-box">
            <x-plugins-ecommerce::fronts.ajax-search.input />
        </div>
        <div class="tp-header-search-category">
            <div class="product-category-label">
                <span>{{ __('All Categories') }}</span>
                <x-core::icon name="ti ti-chevron-down" />
            </div>
            <x-plugins-ecommerce::fronts.ajax-search.categories-dropdown />
        </div>
        <div class="tp-header-search-btn">
            <button type="submit" title="{{ __('Search') }}">
                <x-core::icon name="ti ti-search" />
            </button>
        </div>
    </div>
</x-plugins-ecommerce::fronts.ajax-search>
