@php
    $colorMode ??= 'light';
    $showUserMenu ??= false;
    $announcements = apply_filters('announcement_display_html', null);
    $currencies = collect();
    $hasCurrencies = false;
    if (is_plugin_active('ecommerce')) {
        $currencies = get_all_currencies();
        $hasCurrencies = $currencies->count() > 1;
    }
@endphp

<div
    @class(['p-relative z-index-11 d-none d-md-block', 'tp-header-top-border' => $hasCurrencies || $announcements, 'tp-header-top-2' => $colorMode === 'light', 'tp-header-top black-bg' => $colorMode !== 'light'])
    style="background-color: {{ theme_option('header_top_background_color', $headerTopBackgroundColor) }}; color: {{ $headerTopTextColor }}"
>
    <div @class([$headerTopClass ?? null])>
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <div class="position-relative">
                {!! $announcements !!}
            </div>
            <div>
                <div @class(['tp-header-top-right d-flex align-items-center justify-content-end', 'tp-header-top-black' => $colorMode === 'light'])>
                    <div class="tp-header-top-menu d-none d-lg-flex align-items-center justify-content-end">
                        {!! Theme::partial('language-switcher', ['type' => 'desktop']) !!}
                        @if ($hasCurrencies)
                            <div class="tp-header-top-menu-item tp-header-currency">
                                <span class="tp-header-currency-toggle" id="tp-header-currency-toggle">
                                    {{ get_application_currency()->title }}
                                    <x-core::icon name="ti ti-chevron-down" />
                                </span>
                                {!! Theme::partial('currency-switcher') !!}
                            </div>
                        @endif

                        @if ($showUserMenu && is_plugin_active('ecommerce'))
                            @auth('customer')
                                <div class="tp-header-top-menu-item tp-header-setting">
                                    <span class="tp-header-setting-toggle" id="tp-header-setting-toggle">
                                        {{ auth('customer')->user()->name }}
                                        <x-core::icon name="ti ti-chevron-down" />
                                    </span>
                                    <ul>
                                        <li>
                                            <a href="{{ route('customer.overview') }}">{{ __('My Profile') }}</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('customer.orders') }}">{{ __('Orders') }}</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('customer.logout') }}">{{ __('Logout') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <div class="tp-header-top-menu-item tp-header-setting">
                                    <a href="{{ route('customer.login') }}">{{ __('Login') }}</a>
                                </div>
                                <div class="tp-header-top-menu-item tp-header-setting">
                                    <a href="{{ route('customer.register') }}">{{ __('Register') }}</a>
                                </div>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
