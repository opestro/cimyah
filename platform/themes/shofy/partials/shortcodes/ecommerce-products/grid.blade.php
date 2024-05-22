<section class="tp-product-arrival-area pt-30 pb-30">
    <div class="container">
        {!! Theme::partial('section-title', compact('shortcode')) !!}

        @if($shortcode->with_sidebar)
            <div class="row">
                <div class="col-xl-4 col-lg-5">
                    @include(Theme::getThemeNamespace('partials.shortcodes.ecommerce-products.partials.sidebar'))
                </div>
                <div class="col-xl-8 col-lg-7">
                    @endif

                    @include(Theme::getThemeNamespace('views.ecommerce.includes.product-items'), ['itemsPerRow' => $shortcode->with_sidebar ? 3 : 4])

                    @if($shortcode->with_sidebar)
                </div>
            </div>
        @endif
    </div>
</section>
