<div
    id="header-sticky-2"
    class="tp-header-sticky-area"
    {!! Theme::partial('header.sticky-data') !!}
>
    <div class="container">
        <div class="tp-mega-menu-wrapper p-relative">
            <div class="row align-items-center">
                <div class="col-xl-3 col-lg-3 col-md-3 col-6">
                    {!! Theme::partial('header.logo') !!}
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 d-none d-md-block">
                    <div class="tp-header-sticky-menu main-menu menu-style-1">
                        <nav id="mobile-menu">
                            {!! Menu::renderMenuLocation('main-menu', ['view' => 'main-menu']) !!}
                        </nav>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-6">
                    {!! Theme::partial('header.actions', ['class' => 'ml-50 justify-content-end']) !!}
                </div>
            </div>
        </div>
    </div>
</div>
