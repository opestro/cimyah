<section class="tp-best-area pt-30 pb-30">
    <div class="container">
        {!! Theme::partial('section-title', ['shortcode' => $shortcode, 'class' => 'text-center mb-40']) !!}

        <div class="row">
            <div class="col-xl-12">
                <div class="tp-best-slider">
                    <div class="tp-best-slider-active swiper-container mb-10">
                        <div class="swiper-wrapper">
                            @foreach($products as $product)
                                <div class="tp-best-item-4 swiper-slide">
                                    @include(Theme::getThemeNamespace('views.ecommerce.includes.product-item'), ['class' => 'swiper-slide'])
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tp-best-swiper-scrollbar tp-swiper-scrollbar"></div>
                </div>
            </div>
        </div>
    </div>
</section>
