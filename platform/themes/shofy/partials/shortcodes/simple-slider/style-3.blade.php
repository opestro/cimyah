@php
    Theme::set('hasSlider', true);
@endphp

<section class="tp-slider-area p-relative z-index-1">
    <div class="tp-slider-active-3 swiper-container"
         data-loop="{{ $shortcode->is_loop == 'yes' }}"
         data-autoplay="{{ $shortcode->is_autoplay == 'yes' }}"
         data-autoplay-speed="{{ in_array($shortcode->autoplay_speed, [2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000]) ? $shortcode->autoplay_speed : 5000 }}"
    >
        <div class="swiper-wrapper">
            @foreach ($sliders as $slider)
                @php
                    $title = $slider->title;
                    $description = $slider->description;

                    $tabletImage = $slider->getMetaData('tablet_image', true) ?: $slider->image;
                    $mobileImage = $slider->getMetaData('mobile_image', true) ?: $tabletImage;
                @endphp

                <div class="tp-slider-item-3 tp-slider-height-3 p-relative swiper-slide black-bg d-flex align-items-center">
                    <div
                        class="tp-slider-thumb-3 include-bg"
                        @if($slider->image)
                            data-background="{{ RvMedia::getImageUrl($slider->image, $title) }}"
                        @endif
                        @if ($tabletImage) data-tablet-background="{{ RvMedia::getImageUrl($tabletImage) }}" @endif
                        @if ($mobileImage) data-mobile-background="{{ RvMedia::getImageUrl($mobileImage) }}" @endif
                    ></div>
                    @if($title || $description)
                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-xl-6 col-lg-6 col-md-8">
                                    <div class="tp-slider-content-3">
                                        @if($description)
                                            <span @if($fontFamily = $shortcode->font_family_of_description) style="--tp-ff-oregano: '{{ $fontFamily }}'" @endif>
                                                {!! BaseHelper::clean($description) !!}
                                            </span>
                                        @endif
                                        @if ($title)
                                            <h3 class="tp-slider-title-3">{!! BaseHelper::clean($title) !!}</h3>
                                        @endif
                                        @if($buttonLabel = $slider->getMetaData('button_label', true))
                                            <div class="tp-slider-btn-3">
                                                <a href="{{ $slider->link }}" class="tp-btn tp-btn-border tp-btn-border-white">
                                                    {!! BaseHelper::clean($buttonLabel) !!}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="tp-swiper-dot tp-slider-3-dot d-sm-none"></div>
        <div class="tp-slider-arrow-3 d-none d-sm-block">
            <button type="button" class="tp-slider-3-button-prev">
                <svg width="22" height="42" viewBox="0 0 22 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 0.999999L1 21L21 41" stroke="currentColor" stroke-opacity="0.3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
            <button type="button" class="tp-slider-3-button-next">
                <svg width="22" height="42" viewBox="0 0 22 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 0.999999L21 21L1 41" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>
</section>
