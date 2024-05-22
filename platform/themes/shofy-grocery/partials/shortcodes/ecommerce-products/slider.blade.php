<section class="tp-best-seller-area pt-110 pb-60">
    <div class="container">
        @if($shortcode->with_sidebar && $ads->isNotEmpty())
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-5 col-sm-7">
                    <div class="tp-best-banner-5 p-relative mr-20">
                        <div class="tp-best-banner-slider-active-5 swiper-container">
                            <div class="swiper-wrapper">
                                @foreach($ads as $ad)
                                    <div class="tp-best-banner-item-5 p-relative fix swiper-slide">
                                        @if ($ad->url)
                                            <a href="{{ $ad->click_url }}" @if($ad->open_in_new_tab) target="_blank" @endif>
                                        @endif
                                            <div class="tp-best-banner-thumb-5 include-bg grey-bg">
                                                {!! Theme::partial('shortcodes.ads.includes.item', ['item' => $ad]) !!}
                                            </div>
                                        @if ($ad->url)
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tp-best-banner-slider-dot-5 tp-swiper-dot"></div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-8 col-md-7">
            @endif
                    <div class="tp-best-slider-wrapper-5">
                        {!! Theme::partial('section-title', compact('shortcode')) !!}

                        <div class="tp-best-slider-5 p-relative">
                            <div
                                class="tp-best-slider-active-5 swiper-container"
                                data-item-per-row="3"
                            >
                                <div class="swiper-wrapper">
                                    @foreach($products as $product)
                                        <div class="tp-best-item-5 swiper-slide">
                                            @include(Theme::getThemeNamespace('views.ecommerce.includes.product-item'), ['class' => 'swiper-slide'])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @if(count($products) > 3)
                                <div class="tp-best-slider-arrow-5 d-none d-sm-block">
                                    <button type="submit" class="tp-best-slider-5-button-prev">
                                        <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M7 13L1 7L7 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                    <button type="button" class="tp-best-slider-5-button-next">
                                        <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 13L7 7L1 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="tp-best-slider-dot-5 tp-swiper-dot mt-15 text-center d-sm-none"></div>
                    </div>

            @if($shortcode->with_sidebar && $ads->isNotEmpty())
                </div>
            </div>
           @endif
    </div>
</section>
