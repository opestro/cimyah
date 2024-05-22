@php
    Theme::asset()->container('footer')->usePath()->add('nice-select', 'js/nice-select.js');
@endphp

<div class="tp-shop-top mb-45">
    @if(products_listing_layout() === 'no-sidebar')
        <form action="{{ URL::current() }}" method="GET" class="bb-product-form-filter">
            <input type="hidden" name="sort-by" value="{{ BaseHelper::stringify(request()->query('sort-by')) }}">
            <input type="hidden" name="per-page" value="{{ BaseHelper::stringify(request()->query('per-page')) }}">
            <input type="hidden" name="layout" value="{{ BaseHelper::stringify(request()->query('layout')) }}">
            <input type="hidden" name="page" value="{{ BaseHelper::stringify(request()->query('page')) }}">
        </form>
    @endif

    <div class="row">
        <div class="col-xl-6">
            <div class="tp-shop-top-left d-flex align-items-center">
                <div class="tp-shop-top-tab tp-tab">
                    <ul class="nav nav-tabs" id="productTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button @class(['nav-link', 'active' => request()->query('layout', get_product_layout()) === 'grid']) data-value="grid" id="grid-tab" data-bb-toggle="change-product-filter-layout" type="button" role="tab" aria-controls="grid-tab-pane" aria-selected="true">
                                <x-core::icon name="ti ti-layout-grid" />
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button @class(['nav-link', 'active' => request()->query('layout', get_product_layout()) === 'list']) data-value="list" id="list-tab" data-bb-toggle="change-product-filter-layout" type="button" role="tab" aria-controls="list-tab-pane" aria-selected="false">
                                <x-core::icon name="ti ti-layout-list" />
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="tp-shop-top-result">
                    <p>{{ __('Showing :from - :to of :total products', ['from' => $products->firstItem() ?: 0, 'to' => $products->lastItem() ?: 0, 'total' => $products->total()]) }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="tp-shop-top-right d-sm-flex align-items-center justify-content-xl-end">
                <div class="tp-shop-top-select">
                    <select name="sort-by" data-nice-select>
                        @foreach (EcommerceHelper::getSortParams() as $key => $value)
                            <option value="{{ $key }}" @selected(request()->input('sort-by') === $key)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="tp-shop-top-select sort-by">
                    <select name="per-page" data-nice-select>
                        @foreach (EcommerceHelper::getShowParams() as $key => $value)
                            <option value="{{ $key }}" @selected($key === request()->integer('per-page', theme_option('number_of_products_per_page', 12)))>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="tp-shop-top-filter d-lg-none">
                    <button type="button" class="tp-filter-btn" data-bb-toggle="toggle-filter-sidebar">
                        <span>
                            <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.9998 3.45001H10.7998" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M3.8 3.45001H1" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M6.5999 5.9C7.953 5.9 9.0499 4.8031 9.0499 3.45C9.0499 2.0969 7.953 1 6.5999 1C5.2468 1 4.1499 2.0969 4.1499 3.45C4.1499 4.8031 5.2468 5.9 6.5999 5.9Z"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-miterlimit="10"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <path d="M15.0002 11.15H12.2002" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M5.2 11.15H1" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M9.4002 13.6C10.7533 13.6 11.8502 12.5031 11.8502 11.15C11.8502 9.79691 10.7533 8.70001 9.4002 8.70001C8.0471 8.70001 6.9502 9.79691 6.9502 11.15C6.9502 12.5031 8.0471 13.6 9.4002 13.6Z"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-miterlimit="10"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </span>
                        {{ __('Filter') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
