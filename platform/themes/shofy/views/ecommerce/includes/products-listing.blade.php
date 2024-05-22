@php
    Theme::asset()->container('footer')->usePath()->add('range-slider', 'js/range-slider.js');
    $listingLayout = products_listing_layout();
@endphp

<div @class(['row', 'flex-row-reverse' => $listingLayout === 'right-sidebar'])>
    <div @class(['col-xl-3 col-lg-4' => $listingLayout !== 'no-sidebar', 'col-12' => $listingLayout === 'no-sidebar'])>
        @include(Theme::getThemeNamespace('views.ecommerce.includes.filters-sidebar'))
    </div>

    @if ($listingLayout !== 'no-sidebar')
        <div class="col-xl-9 col-lg-8">
            @endif
            <div class="tp-shop-main-wrapper">
                @include(EcommerceHelper::viewPath('includes.product-filters-top'))

                <div class="bb-product-items-wrapper tp-shop-item-primary">
                    @include(Theme::getThemeNamespace('views.ecommerce.includes.product-items'))
                </div>
            </div>
            @if ($listingLayout !== 'no-sidebar')
        </div>
    @endif
</div>
