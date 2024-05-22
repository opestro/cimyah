<section class="tp-slider-area p-relative z-index-1">
    <div
        class="tp-slider-full-width tp-slider-active tp-slider-variation swiper-container"
        data-loop="{{ $shortcode->is_loop == 'yes' }}"
        data-autoplay="{{ $shortcode->is_autoplay == 'yes' }}"
        data-autoplay-speed="{{ in_array($shortcode->autoplay_speed, [2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000]) ? $shortcode->autoplay_speed : 5000 }}"
    >
        <div class="swiper-wrapper">
            @foreach ($sliders as $slider)
                @php
                    $title = $slider->title;
                    $subtitle = $slider->getMetaData('subtitle', true);
                    $description = $slider->description;
                @endphp

                <div
                    @class(['tp-slider-item swiper-slide', 'is-light' => $slider->getMetaData('is_light', true)])
                    style="background-color: {{ $slider->getMetaData('background_color', true) }}"
                >
                    @if($slider->link)
                        <a class="tp-slider-thumb text-end" href="{{ $slider->link }}">
                            @include(Theme::getThemeNamespace('partials.shortcodes.simple-slider.includes.image', compact('slider')))
                        </a>
                    @else
                        <div class="tp-slider-thumb text-end">
                            @include(Theme::getThemeNamespace('partials.shortcodes.simple-slider.includes.image', compact('slider')))
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        @if(count($sliders) > 1)
            <div class="tp-slider-arrow tp-swiper-arrow d-none d-lg-block">
                <button type="button" class="tp-slider-button-prev">
                    <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 13L1 7L7 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <button type="button" class="tp-slider-button-next">
                    <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 13L7 7L1 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            <div class="tp-slider-dot tp-swiper-dot"></div>
        @endif
    </div>
</section>
