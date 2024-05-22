<header>
    <div @class(['tp-header-area tp-header-height', 'tp-header-style-primary' => ! Theme::get('isHomePage')])>
        {!! Theme::partial('header.top', ['colorMode' => 'light', 'headerTopClass' => 'container', 'showUserMenu' => true]) !!}

        <div
            id="header-sticky"
            class="tp-header-bottom-2 tp-header-sticky" {!! Theme::partial('header.sticky-data') !!}
            style="background-color: {{ $headerMainBackgroundColor }}; color: {{ $headerMainTextColor }}"
        >
            <div class="container">
                <div class="tp-mega-menu-wrapper p-relative">
                    <div class="row align-items-center">
                        <div class="col-xl-2 col-lg-5 col-md-5 col-sm-4 col-6">
                            {!! Theme::partial('header.logo') !!}
                        </div>
                        <div class="col-xl-5 d-none d-xl-block">
                            <div class="main-menu menu-style-2">
                                <nav class="tp-main-menu-content">
                                    {!! Menu::renderMenuLocation('main-menu', ['view' => 'main-menu']) !!}
                                </nav>
                            </div>
                            @if(is_plugin_active('ecommerce'))
                                <div class="tp-category-menu-wrapper d-none">
                                    <nav class="tp-category-menu-content">
                                        {!! Theme::partial('header.categories-dropdown') !!}
                                    </nav>
                                </div>
                            @endif
                        </div>
                        @if(is_plugin_active('ecommerce'))
                            <div class="col-xl-5 col-lg-7 col-md-7 col-sm-8 col-6">
                                <div class="tp-header-bottom-right d-flex align-items-center justify-content-end pl-30">
                                    <div class="tp-header-search-2 d-none d-sm-block">
                                        <x-plugins-ecommerce::fronts.ajax-search>
                                            <x-plugins-ecommerce::fronts.ajax-search.input />

                                            <button type="submit" title="Search">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M9 17C13.4183 17 17 13.4183 17 9C17 4.58172 13.4183 1 9 1C4.58172 1 1 4.58172 1 9C1 13.4183 4.58172 17 9 17Z"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    />
                                                    <path d="M18.9999 19L14.6499 14.65" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        </x-plugins-ecommerce::fronts.ajax-search>
                                    </div>
                                    {!! Theme::partial('header.actions', ['class' => 'ml-30']) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
