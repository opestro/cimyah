<header>
    <div @class(['tp-header-area p-relative z-index-11', 'tp-header-style-primary' => ! Theme::get('isHomePage')])>
        {!! Theme::partial('header.top', ['colorMode' => 'dark', 'headerTopClass' => 'container']) !!}

        <div class="tp-header-main tp-header-sticky" style="background-color: {{ $headerMainBackgroundColor }}; color: {{ $headerMainTextColor }}">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
                        {!! Theme::partial('header.logo') !!}
                    </div>

                    <div class="col-xl-6 col-lg-7 d-none d-lg-block pl-70">
                        @if(is_plugin_active('ecommerce'))
                            {!! Theme::partial('header.search-form') !!}
                        @endif
                    </div>
                    <div class="col-xl-4 col-lg-3 col-md-8 col-6">
                        <div class="tp-header-main-right d-flex align-items-center justify-content-end">
                            @if(is_plugin_active('ecommerce'))
                                <div class="tp-header-login d-none d-lg-block">
                                    <a href="{{ auth('customer')->check() ? route('customer.overview') : route('customer.login') }}" class="d-flex align-items-center">
                                        <div class="tp-header-login-icon">
                                            <span style="border-color: {{ theme_option('header_border_color', 'rgba(1, 15, 28, 0.1)') }};">
                                                @auth('customer')
                                                    <img src="{{ auth('customer')->user()->avatar_url }}" alt="{{ auth('customer')->user()->name }}">
                                                @else
                                                    <svg width="17" height="21" viewBox="0 0 17 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="8.57894" cy="5.77803" r="4.77803" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1.00002 17.2014C0.998732 16.8655 1.07385 16.5337 1.2197 16.2311C1.67736 15.3158 2.96798 14.8307 4.03892 14.611C4.81128 14.4462 5.59431 14.336 6.38217 14.2815C7.84084 14.1533 9.30793 14.1533 10.7666 14.2815C11.5544 14.3367 12.3374 14.4468 13.1099 14.611C14.1808 14.8307 15.4714 15.27 15.9291 16.2311C16.2224 16.8479 16.2224 17.564 15.9291 18.1808C15.4714 19.1419 14.1808 19.5812 13.1099 19.7918C12.3384 19.9634 11.5551 20.0766 10.7666 20.1304C9.57937 20.2311 8.38659 20.2494 7.19681 20.1854C6.92221 20.1854 6.65677 20.1854 6.38217 20.1304C5.59663 20.0773 4.81632 19.9641 4.04807 19.7918C2.96798 19.5812 1.68652 19.1419 1.2197 18.1808C1.0746 17.8747 0.999552 17.5401 1.00002 17.2014Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                @endauth
                                            </span>
                                        </div>
                                        <div class="tp-header-login-content d-none d-xl-block">
                                            <span style="border-color: {{ theme_option('header_border_color', 'rgba(1, 15, 28, 0.1)') }};">
                                                @auth('customer')
                                                    {{ Str::limit(auth('customer')->user()->email, 25) }}
                                                @else
                                                    {{ __('Hello, :name', ['name' => __('Guest')]) }}
                                                @endauth
                                            </span>
                                            <h5 class="tp-header-login-title" @auth('customer') title="{{ $name = auth('customer')->user()->name }}" @endauth>
                                                @auth('customer')
                                                    {{ __('Hello, :name', ['name' => Str::limit($name, 15)]) }}
                                                @else
                                                    {{ __('Login / Register') }}
                                                @endauth
                                            </h5>
                                        </div>
                                    </a>
                                </div>
                            @endif

                            {!! Theme::partial('header.actions', ['class' => 'ml-50']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="tp-header-bottom tp-header-bottom-border d-none d-lg-block"
            style="background-color: {{ theme_option('header_menu_background_color', '#fff') }}; color: {{ theme_option('header_menu_text_color', '#010f1c') }}; border-color: {{ theme_option('header_border_color', 'rgba(1, 15, 28, 0.1)') }};"
        >
            <div class="container">
                <div class="tp-mega-menu-wrapper p-relative">
                    <div class="row align-items-center">
                        @if (($enabledHeaderCategoriesDropdownOnDesktop = is_plugin_active('ecommerce') && theme_option('enabled_header_categories_dropdown', 'yes') === 'yes'))
                            <div class="col-xl-3 col-lg-3">
                                <div class="tp-header-category tp-category-menu tp-header-category-toggle">
                                    <button
                                        class="tp-category-menu-btn tp-category-menu-toggle"
                                        data-bb-toggle="init-categories-dropdown"
                                        data-bb-target=".tp-category-menu-content"
                                        data-url="{{ route('public.ajax.categories-dropdown') }}"
                                    >
                                        <span>
                                            <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M0 1C0 0.447715 0.447715 0 1 0H15C15.5523 0 16 0.447715 16 1C16 1.55228 15.5523 2 15 2H1C0.447715 2 0 1.55228 0 1ZM0 7C0 6.44772 0.447715 6 1 6H17C17.5523 6 18 6.44772 18 7C18 7.55228 17.5523 8 17 8H1C0.447715 8 0 7.55228 0 7ZM1 12C0.447715 12 0 12.4477 0 13C0 13.5523 0.447715 14 1 14H11C11.5523 14 12 13.5523 12 13C12 12.4477 11.5523 12 11 12H1Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                        {{ __('All Categories') }}
                                    </button>
                                    <nav class="tp-category-menu-content"></nav>
                                </div>
                            </div>
                        @endif

                        <div @class(['col-xl-6 col-lg-6' => $enabledHeaderCategoriesDropdownOnDesktop, 'col-xl-9 col-lg-9' => ! $enabledHeaderCategoriesDropdownOnDesktop])>
                            <div class="main-menu menu-style-1">
                                <nav class="tp-main-menu-content">
                                    {!! Menu::renderMenuLocation('main-menu', ['view' => 'main-menu']) !!}
                                </nav>
                            </div>
                        </div>

                        @if ($hotline = theme_option('hotline'))
                            <div class="col-xl-3 col-lg-3">
                                <div class="tp-header-contact d-flex align-items-center justify-content-end">
                                    <div class="tp-header-contact-icon">
                                        <span>
                                            <x-core::icon name="ti ti-phone" />
                                        </span>
                                    </div>
                                    <div class="tp-header-contact-content">
                                        <h5>{{ __('Hotline:') }}</h5>
                                        <p>
                                            <a href="tel:{{ $hotline }}">
                                                {{ $hotline }}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

{!! Theme::partial('header.sticky') !!}
