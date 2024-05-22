<header>
    {!! Theme::partial('header.top', ['colorMode' => 'light', 'headerTopClass' => 'container-fluid pl-85 pr-85', 'showUserMenu' => true]) !!}

    <div
        id="header-sticky"
        @class(['tp-header-area tp-header-style-transparent-white tp-header-sticky tp-header-transparent has-dark-logo tp-header-height', 'header-main' => ! Theme::get('hasSlider')])
        {!! Theme::partial('header.sticky-data') !!}
    >
        <div class="tp-header-bottom-3 pl-85 pr-85" style="background-color: {{ $headerMainBackgroundColor }}; color: {{ $headerMainTextColor }}">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-xl-2 col-lg-2 col-6">
                        {!! Theme::partial('header.logo', ['hasLogoLight' => true]) !!}
                    </div>
                    <div class="col-xl-8 col-lg-8 d-none d-lg-block">
                        <div class="main-menu menu-style-3 menu-style-4 p-relative">
                            <nav class="tp-main-menu-content">
                                {!! Menu::renderMenuLocation('main-menu', ['view' => 'main-menu']) !!}
                            </nav>
                        </div>
                        @if(is_plugin_active('ecommerce'))
                            <div class="tp-category-menu-wrapper d-none">
                                <nav class="tp-category-menu-content">
                                    <ul>
                                        {!! Theme::partial('header.categories-dropdown') !!}
                                    </ul>
                                </nav>
                            </div>
                        @endif
                    </div>
                    <div class="col-xl-2 col-lg-2 col-6">
                        {!! Theme::partial('header.actions', ['class' => 'justify-content-end ml-50', 'showSearchButton' => true]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
