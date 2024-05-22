<div class="offcanvas__area offcanvas__radius">
    <div class="offcanvas__wrapper">
        <div class="offcanvas__close">
            <button class="offcanvas__close-btn offcanvas-close-btn" title="Search">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 1L1 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M1 1L11 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
        <div class="offcanvas__content">
            <div class="offcanvas__top mb-70 d-flex justify-content-between align-items-center">
                <div class="offcanvas__logo logo">
                    {!! Theme::partial('header.logo') !!}
                </div>
            </div>
            @if (is_plugin_active('ecommerce') && theme_option('enabled_header_categories_dropdown_on_mobile', 'yes') === 'yes')
                <div class="pb-40 offcanvas__category">
                    <button class="tp-offcanvas-category-toggle">
                        <x-core::icon name="ti ti-menu-2" />
                        {{ __('All Categories') }}
                    </button>
                    <div class="tp-category-mobile-menu"></div>
                </div>
            @endif

            <div class="mb-40 tp-main-menu-mobile fix d-xl-none"></div>

            @if ($hotline = theme_option('hotline'))
                <div class="offcanvas__btn">
                    <a href="tel:{{ $hotline }}" class="tp-btn-2 tp-btn-border-2">
                        {{ __('Contact Us') }}
                    </a>
                </div>
            @endif
        </div>
        <div class="offcanvas__bottom">
            <div class="offcanvas__footer d-flex align-items-center justify-content-between">
                @if (is_plugin_active('ecommerce') && ($currencies = get_all_currencies()) && $currencies->count() > 1)
                    <div class="offcanvas__currency-wrapper currency">
                        <span class="offcanvas__currency-selected-currency tp-currency-toggle" id="tp-offcanvas-currency-toggle">
                            {{ __('Currency: :currency', ['currency' => get_application_currency()->title]) }}
                        </span>
                        {!! Theme::partial('currency-switcher', ['class' => 'offcanvas__currency-list tp-currency-list']) !!}
                    </div>
                @endif

                {!! Theme::partial('language-switcher', ['type' => 'mobile']) !!}
            </div>
        </div>
    </div>
</div>
