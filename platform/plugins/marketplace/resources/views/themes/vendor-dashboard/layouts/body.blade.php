<header class="header--mobile">
    <div class="header__left">
        <button class="ps-drawer-toggle">
            <x-core::icon name="ti ti-menu-2" />
        </button>
    </div>
    <div class="header__center">
        <a
            class="ps-logo"
            href="{{ route('marketplace.vendor.dashboard') }}"
        >
            @php $logo = theme_option('logo_vendor_dashboard', theme_option('logo')); @endphp
            @if ($logo)
                <img
                    src="{{ RvMedia::getImageUrl($logo) }}"
                    alt="{{ theme_option('site_title') }}"
                >
            @endif
        </a>
    </div>
    <div class="header__right">
        <a class="header__site-link" href="{{ route('customer.logout') }}">
            <x-core::icon name="ti ti-logout" />
        </a>
    </div>
</header>
<aside class="ps-drawer--mobile">
    <div class="ps-drawer__header">
        <h4 class="fs-3 mb-0">Menu</h4>
        <button class="ps-drawer__close">
            <x-core::icon name="ti ti-x" />
        </button>
    </div>
    <div class="ps-drawer__content">
        @include(MarketplaceHelper::viewPath('vendor-dashboard.layouts.menu'))
    </div>
</aside>
<div class="ps-site-overlay"></div>
<main class="ps-main">
    <div class="ps-main__sidebar">
        <div class="ps-sidebar">
            <div class="ps-sidebar__top">
                <div class="ps-block--user-wellcome">
                    <div class="ps-block__left">
                        <img
                            src="{{ auth('customer')->user()->store->logo_url }}"
                            alt="{{ auth('customer')->user()->store->name }}"
                            class="avatar avatar-lg"
                        />
                    </div>
                    <div class="ps-block__right">
                        <p>{{ __('Hello') }}, {{ auth('customer')->user()->name }}</p>
                        <small>{{ __('Joined on :date', ['date' => auth('customer')->user()->created_at->translatedFormat('M d, Y')]) }}</small>
                    </div>
                    <div class="ps-block__action">
                        <a href="{{ route('customer.logout') }}">
                            <x-core::icon name="ti ti-logout" />
                        </a>
                    </div>
                </div>
                <div class="ps-block--earning-count"><small>{{ __('Balance') }}</small>
                    <h3>{{ format_price(auth('customer')->user()->balance) }}</h3>
                </div>
            </div>
            <div class="ps-sidebar__content">
                <div class="ps-sidebar__center">
                    @include(MarketplaceHelper::viewPath('vendor-dashboard.layouts.menu'))
                </div>
                <div class="ps-sidebar__footer">
                    <div class="ps-copyright">
                        @php $logo = theme_option('logo_vendor_dashboard', theme_option('logo')); @endphp
                        @if ($logo)
                            <a href="{{ BaseHelper::getHomepageUrl() }}" title="{{ $siteTitle = theme_option('site_title') }}">
                                <img
                                    src="{{ RvMedia::getImageUrl($logo) }}"
                                    alt="{{ $siteTitle }}"
                                    height="40"
                                >
                            </a>
                        @endif
                        <p>{!! BaseHelper::clean(str_replace('%Y', Carbon\Carbon::now()->year, theme_option('copyright'))) !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div
        class="ps-main__wrapper"
        id="vendor-dashboard"
    >
        <header class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fs-1 mb-0 text-truncate me-3">{{ page_title()->getTitle(false) }}</h3>
            <div class="d-flex align-items-center gap-4">
                @if (is_plugin_active('language'))
                    {!! apply_filters('marketplace_vendor_dashboard_language_switcher', view(MarketplaceHelper::viewPath('vendor-dashboard.partials.language-switcher'))->render()) !!}
                @endif

                @php($customer = auth('customer')->user())

                <div class="d-none d-md-inline-block">
                    @if ($customer?->store)
                        <a href="{{ $customer->store->url }}" target="_blank" class="text-uppercase">
                            <x-core::icon name="ti ti-building-store" />
                            {{ __('View your store') }}
                        </a>
                    @endif

                    <a href="{{ BaseHelper::getHomepageUrl() }}" target="_blank" class="text-uppercase">
                        <span>{{ __('Go to homepage') }}</span>
                        <x-core::icon name="ti ti-arrow-right" />
                    </a>
                </div>
            </div>
        </header>

        <div id="app">
            @yield('content')
        </div>
    </div>
</main>
