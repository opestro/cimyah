<div id="tp-bottom-menu-sticky" class="tp-mobile-menu d-lg-none">
    <div class="container">
        <div @class(['row', 'row-cols-5' => is_plugin_active('ecommerce'), 'row-cols-2' => ! is_plugin_active('ecommerce')])>
            @if (is_plugin_active('ecommerce'))
                <div class="col">
                    <div class="text-center tp-mobile-item">
                        <a href="{{ route('public.products') }}" class="tp-mobile-item-btn">
                            <x-core::icon name="ti ti-shopping-bag" />
                            <span>{{ __('Store') }}</span>
                        </a>
                    </div>
                </div>
            @endif
            <div class="col">
                <div class="text-center tp-mobile-item">
                    <button class="tp-mobile-item-btn tp-search-open-btn">
                        <x-core::icon name="ti ti-search" />
                        <span>{{ __('Search') }}</span>
                    </button>
                </div>
            </div>
            @if (is_plugin_active('ecommerce'))
                @if (EcommerceHelper::isWishlistEnabled())
                    <div class="col">
                        <div class="text-center tp-mobile-item">
                            <a href="{{ route('public.wishlist') }}" class="tp-mobile-item-btn">
                                <x-core::icon name="ti ti-heart" />
                                <span>{{ __('Wishlist') }}</span>
                            </a>
                        </div>
                    </div>
                @endif
                <div class="col">
                    <div class="text-center tp-mobile-item">
                        <a
                            href="{{ auth('customer')->check() ? route('customer.overview') : route('customer.login') }}"
                            class="tp-mobile-item-btn"
                            @auth('customer')
                                title="{{ auth('customer')->user()->name }}"
                            @endauth
                        >
                            <x-core::icon name="ti ti-user" />
                            <span>{{ __('Account') }}</span>
                        </a>
                    </div>
                </div>
            @endif
            <div class="col">
                <div class="text-center tp-mobile-item">
                    <button class="tp-mobile-item-btn tp-offcanvas-open-btn">
                        <x-core::icon name="ti ti-menu-2" />
                        <span>{{ __('Menu') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
