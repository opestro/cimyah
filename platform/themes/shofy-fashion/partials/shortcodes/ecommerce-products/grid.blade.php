<section class="tp-seller-area pb-30 pt-30">
    <div class="container">
        {!! Theme::partial('section-title', ['shortcode' => $shortcode, 'class' => 'text-center mb-40']) !!}

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

        @if(($buttonLabel = $shortcode->button_label) && ($buttonUrl = $shortcode->button_url))
            <div class="tp-seller-more text-center mt-10">
                <a href="{{ $buttonUrl }}" class="tp-btn tp-btn-border tp-btn-border-sm">
                    {!! BaseHelper::clean($buttonLabel) !!}
                </a>
            </div>
        @endif
    </div>
</section>
