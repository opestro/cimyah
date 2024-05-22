<section class="tp-slider-area p-relative z-index-1">
    <div
        class="tp-slider-active tp-slider-variation swiper-container"
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
                    @class(['tp-slider-item tp-slider-height d-flex align-items-center swiper-slide', 'is-light' => $slider->getMetaData('is_light', true)])
                    style="background-color: {{ $slider->getMetaData('background_color', true) }}"
                >
                    <div class="tp-slider-shape d-none d-sm-block">
                        @foreach(range(1, 4) as $i)
                            @if($shape = $shortcode->{"shape_$i"})
                                {{ RvMedia::image($shape, $slider->title, attributes: ['class' => "tp-slider-shape-$i", 'loading' => false]) }}
                            @endif
                        @endforeach
                    </div>
                    <div class="container">
                        @if ($title || $description)
                            <div class="row align-items-center">
                                <div class="col-xl-5 col-lg-6 col-md-6">
                                    <div class="tp-slider-content p-relative z-index-1">
                                        @if($subtitle)
                                            <span>{!! BaseHelper::clean($subtitle) !!}</span>
                                        @endif
                                        @if ($title)
                                            <h3 class="tp-slider-title">{!! BaseHelper::clean($title) !!}</h3>
                                        @endif
                                        @if($description)
                                            <p @if($fontFamily = $shortcode->font_family_of_description) style="--tp-ff-oregano: '{{ $fontFamily }}'" @endif>
                                                {!! BaseHelper::clean($description) !!}
                                            </p>
                                        @endif
                                        @if($buttonLabel = $slider->getMetaData('button_label', true))
                                            <div class="tp-slider-btn">
                                                <a href="{{ $slider->link }}" class="tp-btn tp-btn-2 tp-btn-white">
                                                    {!! BaseHelper::clean($buttonLabel) !!}
                                                    <svg width="17" height="14" viewBox="0 0 17 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M16 6.99976L1 6.99976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M9.9502 0.975414L16.0002 6.99941L9.9502 13.0244" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xl-7 col-lg-6 col-md-6">
                                    <div class="tp-slider-thumb text-end">
                                        @include(Theme::getThemeNamespace('partials.shortcodes.simple-slider.includes.image', compact('slider')))
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="tp-slider-thumb text-end">
                                @include(Theme::getThemeNamespace('partials.shortcodes.simple-slider.includes.image', compact('slider')))
                            </div>
                        @endif
                    </div>
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
