<section
    class="tp-product-offer grey-bg-2"
    @if($shortcode->background_color)
        style="background-color: {{ $shortcode->background_color }}"
    @endif
    @if($shortcode->background_image)
        style="background-image: url({{ RvMedia::getImageUrl($shortcode->background_image) }}); background-size: cover;"
    @endif
>
    <div class="container">
        <div class="row align-items-center mb-40">
            <div class="col-xl-4 col-md-5 col-sm-6">
                {!! Theme::partial('section-title', ['shortcode' => $shortcode, 'title' => $shortcode->title ?: $flashSale->name]) !!}
            </div>
            <div class="col-xl-8 col-md-7 col-sm-6">
                @if($buttonLabel = $shortcode->button_label)
                    <div class="tp-product-offer-more-wrapper d-flex justify-content-sm-end p-relative z-index-1">
                        <div class="tp-product-offer-more text-sm-end">
                            <a href="{{ $shortcode->button_url ?: route('public.products') }}" class="tp-btn tp-btn-2 tp-btn-blue">
                                {!! BaseHelper::clean($buttonLabel) !!}
                                <svg width="17" height="14" viewBox="0 0 17 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16 6.99976L1 6.99976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9.9502 0.975414L16.0002 6.99941L9.9502 13.0244" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                            <span class="tp-product-offer-more-border"></span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="tp-product-offer-slider fix">
                    <div class="tp-product-offer-slider-active swiper-container">
                        <div class="swiper-wrapper">
                            @foreach($flashSale->products as $product)
                                @include(Theme::getThemeNamespace('views.ecommerce.includes.product-item'), ['class' => 'tp-product-offer-item swiper-slide mb-0', 'withCountdown' => true, 'endDate' => $flashSale->end_date])
                            @endforeach
                        </div>
                        <div class="tp-deals-slider-dot tp-swiper-dot text-center mt-40"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
