<section class="tp-featured-slider-area fix pt-95 pb-120" @style(["background-color: $shortcode->background_color" => $shortcode->background_color])>
    <div class="container">
        {!! Theme::partial('section-title', ['shortcode' => $shortcode, 'class' => 'mb-50']) !!}

        <div class="tp-featured-slider">
            <div class="tp-featured-slider-active swiper-container">
                <div class="swiper-wrapper">
                    @foreach($products as $product)
                        <div class="tp-featured-item swiper-slide white-bg p-relative z-index-1">
                            <div class="tp-featured-thumb include-bg" data-background="{{ RvMedia::getImageUrl($product->image) }}"></div>
                            <div class="tp-featured-content">
                                <h3 class="tp-featured-title">
                                    <a href="{{ $product->url }}">
                                        {{ $product->name }}
                                    </a>
                                </h3>

                                @include(EcommerceHelper::viewPath('includes.product-price'), [
                                    'priceWrapperClassName' => 'tp-featured-price-wrapper',
                                    'priceClassName' => 'tp-featured-price new-price',
                                    'priceOriginalWrapperClassName' => '',
                                    'priceOriginalClassName' => 'tp-featured-price old-price',
                                ])

                                @include(Theme::getThemeNamespace('views.ecommerce.includes.product.style-1.rating'))

                                <div class="tp-featured-btn">
                                    <a href="{{ $product->url }}" class="tp-btn tp-btn-border tp-btn-border-sm">
                                        Shop Now
                                        <svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16 7.49988L1 7.49988" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M9.9502 1.47554L16.0002 7.49954L9.9502 13.5245" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if(count($products) > 1)
                <div class="tp-featured-slider-arrow mt-45">
                    <button class="tp-featured-slider-button-prev">
                        <svg width="33" height="16" viewBox="0 0 33 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.97974 7.97534L31.9797 7.97534" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M8.02954 0.999999L0.999912 7.99942L8.02954 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <button class="tp-featured-slider-button-next">
                        <svg width="33" height="16" viewBox="0 0 33 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M30.9795 7.97534L0.979492 7.97534" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M24.9297 0.999999L31.9593 7.99942L24.9297 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    </div>
</section>
