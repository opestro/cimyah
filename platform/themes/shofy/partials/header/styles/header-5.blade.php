<header>
    {!! Theme::partial('header.top', ['headerTopClass' => 'container-fluid pl-40 pr-40']) !!}

    <div
        id="header-sticky"
        class="tp-header-area p-relative tp-header-sticky tp-header-height"
        {!! Theme::partial('header.sticky-data') !!}
    >
        <div class="tp-header-5 pl-25 pr-25" style="background-color: {{ $headerMainBackgroundColor }}; color: {{ $headerMainTextColor }}">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-xxl-2 col-xl-3 col-6">
                        <div class="tp-header-left-5 d-flex align-items-center">
                            <div class="tp-header-hamburger-5 mr-15 d-none d-xl-block">
                                <button class="tp-hamburger-btn-2 tp-hamburger-toggle">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </button>
                            </div>
                            <div class="tp-header-hamburger-5 mr-15 d-xl-none">
                                <button class="tp-hamburger-btn-2 tp-offcanvas-open-btn">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </button>
                            </div>
                            {!! Theme::partial('header.logo', ['defaultIsDark' => false]) !!}
                        </div>
                    </div>
                    <div class="col-xxl-4 col-xl-6 d-none d-xl-block">
                        <div class="main-menu">
                            <nav class="tp-main-menu-content">
                                {!! Menu::renderMenuLocation('main-menu', ['view' => 'main-menu']) !!}
                            </nav>
                        </div>
                    </div>
                    @if(is_plugin_active('ecommerce'))
                        <div class="col-xxl-4 d-none d-xxl-block">
                            <div class="tp-header-search-5">
                                <x-plugins-ecommerce::fronts.ajax-search>
                                    <div class="tp-header-search-input-box-5">
                                        <div class="tp-header-search-input-5">
                                            <x-plugins-ecommerce::fronts.ajax-search.input />
                                            <span class="tp-header-search-icon-5">
                                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M8.11111 15.2222C12.0385 15.2222 15.2222 12.0385 15.2222 8.11111C15.2222 4.18375 12.0385 1 8.11111 1C4.18375 1 1 4.18375 1 8.11111C1 12.0385 4.18375 15.2222 8.11111 15.2222Z"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    />
                                                    <path d="M16.9995 17L13.1328 13.1333" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                        </div>
                                        <x-plugins-ecommerce::fronts.ajax-search.categories-dropdown />
                                        <button type="submit">{{ __('Search') }}</button>
                                    </div>
                                </x-plugins-ecommerce::fronts.ajax-search>
                            </div>
                        </div>

                        <div class="col-xxl-2 col-xl-3 col-6">
                            <div class="tp-header-right-5 d-flex align-items-center justify-content-end">
                                <div class="tp-header-login-5 d-none d-lg-block">
                                    <a href="{{ auth('customer')->check() ? route('customer.overview') : route('customer.login') }}" class="d-flex align-items-center">
                                        <div class="tp-header-login-icon-5">
                                            <span>
                                                <svg width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M8.00029 9C10.2506 9 12.0748 7.20914 12.0748 5C12.0748 2.79086 10.2506 1 8.00029 1C5.75 1 3.92578 2.79086 3.92578 5C3.92578 7.20914 5.75 9 8.00029 9Z"
                                                        stroke="currentColor"
                                                        stroke-width="1.5"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    />
                                                    <path d="M15 17C15 13.904 11.8626 11.4 8 11.4C4.13737 11.4 1 13.904 1 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="tp-header-login-content-5">
                                            <p>
                                            <span>
                                                @auth('customer')
                                                    {{ Str::limit(auth('customer')->user()->email, 25) }}
                                                @else
                                                    {{ __('Hello') }}
                                                @endauth
                                            </span>
                                                <br>
                                                @auth('customer')
                                                    {{ __('Hello, :name', ['name' => Str::limit(auth('customer')->user()->name, 15)]) }}
                                                @else
                                                    {{ __('Login') }}
                                                @endauth
                                            </p>
                                        </div>
                                    </a>
                                </div>

                                <div class="tp-header-action-5 d-flex align-items-center ml-20">
                                    @if(is_plugin_active('ecommerce'))
                                        @if (EcommerceHelper::isWishlistEnabled())
                                            <div class="tp-header-action-item-5 d-none d-sm-block">
                                                <a href="{{ route('public.wishlist') }}">
                                                    <svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            fill-rule="evenodd"
                                                            clip-rule="evenodd"
                                                            d="M9.20125 16.0348C11.0291 14.9098 12.7296 13.5858 14.2722 12.0865C15.3567 11.0067 16.1823 9.69033 16.6858 8.23822C17.5919 5.42131 16.5335 2.19649 13.5717 1.24212C12.0151 0.740998 10.315 1.02741 9.00329 2.01177C7.69109 1.02861 5.99161 0.742297 4.43489 1.24212C1.47305 2.19649 0.40709 5.42131 1.31316 8.23822C1.81666 9.69033 2.64228 11.0067 3.72679 12.0865C5.26938 13.5858 6.96983 14.9098 8.79771 16.0348L8.99568 16.1579L9.20125 16.0348Z"
                                                            stroke="currentColor"
                                                            stroke-width="1.5"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                        ></path>
                                                        <path d="M5.85156 4.41306C4.95446 4.69963 4.31705 5.50502 4.2374 6.45262" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                    <span class="tp-header-action-badge-5">{{ Cart::instance('wishlist')->count() }}</span>
                                                </a>
                                            </div>
                                        @endif

                                        @if (EcommerceHelper::isCartEnabled())
                                            <div class="tp-header-action-item-5">
                                                <button type="button" class="cartmini-open-btn" data-bb-toggle="open-mini-cart" data-url="{{ route('public.ajax.cart-content') }}">
                                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            fill-rule="evenodd"
                                                            clip-rule="evenodd"
                                                            d="M5.31165 17H12.6964C15.4091 17 17.4901 16.0781 16.899 12.3676L16.2107 7.33907C15.8463 5.48764 14.5912 4.77907 13.49 4.77907H4.48572C3.36828 4.77907 2.18607 5.54097 1.76501 7.33907L1.07673 12.3676C0.574694 15.659 2.59903 17 5.31165 17Z"
                                                            stroke="currentColor"
                                                            stroke-width="1.5"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                        ></path>
                                                        <path
                                                            d="M5.19048 4.59622C5.19048 2.6101 6.90163 1.00003 9.01244 1.00003V1.00003C10.0289 0.99598 11.0052 1.37307 11.7254 2.04793C12.4457 2.72278 12.8506 3.6398 12.8506 4.59622V4.59622"
                                                            stroke="currentColor"
                                                            stroke-width="1.5"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                        ></path>
                                                        <path d="M6.38837 8.34478H6.42885" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M11.5466 8.34478H11.5871" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                    <span class="tp-header-action-badge-5">{{ Cart::instance('cart')->count() }}</span>
                                                </button>
                                            </div>
                                        @endif
                                    @endif

                                    <div class="tp-header-action-item-5 d-none d-sm-block d-xxl-none">
                                        <button type="button" class="tp-search-open-btn">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M8.11111 15.2222C12.0385 15.2222 15.2222 12.0385 15.2222 8.11111C15.2222 4.18375 12.0385 1 8.11111 1C4.18375 1 1 4.18375 1 8.11111C1 12.0385 4.18375 15.2222 8.11111 15.2222Z"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                ></path>
                                                <path d="M16.9995 17L13.1328 13.1333" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if(is_plugin_active('ecommerce'))
            <div class="tp-header-side-menu tp-side-menu-5" style="display: none">
                <nav class="tp-category-menu-content">
                    {!! Theme::partial('header.categories-dropdown') !!}
                </nav>
            </div>
        @endif
    </div>
</header>
