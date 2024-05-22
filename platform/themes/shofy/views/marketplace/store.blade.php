@php
    Theme::set('breadcrumbStyle', 'none');
    Theme::layout('full-width');

    $categories = ProductCategoryHelper::getProductCategoriesWithUrl();
    $categoriesRequest = (array) request()->input('categories', []);
    $categoryId = Arr::get($categoriesRequest, 0);
    $coverImage = $store->cover_image;

    Theme::set('pageTitle', $store->name);
@endphp

<div class="bb-shop-detail">
    @include(MarketplaceHelper::viewPath('includes.store-detail-banner'))

    <div class="container">
        <ul class="bb-shop-nav-tabs nav nav-tabs" id="storeTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">
                    <x-core::icon name="ti ti-home" />
                    {{ __('Home') }}
                </button>
            </li>
            @if ($store->content)
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="about-tab" data-bs-toggle="tab" data-bs-target="#about-tab-pane" type="button" role="tab" aria-controls="about-tab-pane" aria-selected="false">
                        <x-core::icon name="ti ti-info-circle" />
                        {{ __('About the store') }}
                    </button>
                </li>
            @endif
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">
                    <x-core::icon name="ti ti-message" />
                    {{ __('Send message') }}
                </button>
            </li>
            {!! apply_filters('marketplace_vendor_public_nav', null, $store) !!}
        </ul>
        <div class="bb-shop-tab-content tab-content" id="storeTabContent">
            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <div class="tp-shop-main-wrapper row position-relative">
                    <div class="col-xl-3 col-lg-4">
                        @include(MarketplaceHelper::viewPath('includes.store-filters'))
                    </div>
                    <div class="col-xl-9 col-lg-8">
                        @include(EcommerceHelper::viewPath('includes.product-filters-top'))

                        <div class="bb-product-items-wrapper">
                            @include(MarketplaceHelper::viewPath('stores.items'))
                        </div>
                    </div>
                </div>
            </div>

            @if ($store->content)
                <div class="tab-pane fade" id="about-tab-pane" role="tabpanel" aria-labelledby="about-tab" tabindex="0">
                    {!! BaseHelper::clean($store->content) !!}
                </div>
            @endif

            <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                @include(MarketplaceHelper::viewPath('includes.contact'))
            </div>
        </div>
    </div>
</div>
