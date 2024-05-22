<section
    class="tp-category-area pt-115 pb-105 tp-category-plr-85"
    @style(["background-color: $shortcode->background_color;" => $shortcode->background_color])
>
    <div class="container-fluid">
        {!! Theme::partial('section-title', ['shortcode' => $shortcode, 'class' => 'text-center mb-40']) !!}

        <div class="tp-category-slider-4">
            <div class="tp-category-slider-active-4 swiper-container mb-70">
                <div class="swiper-wrapper">
                    @foreach($products as $product)
                        @include(Theme::getThemeNamespace('views.ecommerce.includes.product-item'), ['class' => 'swiper-slide'])
                    @endforeach
                </div>
            </div>
            <div class="tp-category-swiper-scrollbar tp-swiper-scrollbar"></div>
        </div>
    </div>
</section>
