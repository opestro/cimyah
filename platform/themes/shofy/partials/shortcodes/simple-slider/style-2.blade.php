<section class="tp-slider-area p-relative z-index-1">
    <div class="tp-slider-active-2 swiper-container"
         data-loop="{{ $shortcode->is_loop == 'yes' }}"
         data-autoplay="{{ $shortcode->is_autoplay == 'yes' }}"
         data-autoplay-speed="{{ in_array($shortcode->autoplay_speed, [2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000]) ? $shortcode->autoplay_speed : 5000 }}"
    >
        <div class="swiper-wrapper">
            @foreach ($sliders as $slider)
                @php
                    $title = $slider->title;
                    $description = $slider->description;
                @endphp

                <div class="tp-slider-item-2 tp-slider-height-2 p-relative swiper-slide grey-bg-5 d-flex align-items-end">
                    <div class="tp-slider-2-shape">
                        @if($shape = $shortcode->shape_1)
                            {{ RvMedia::image($shape, $slider->title, attributes: ['class' => 'tp-slider-2-shape-1', 'loading' => false]) }}
                        @endif
                    </div>
                    <div class="container">
                        @if($title || $description)
                            <div class="row align-items-center">
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <div class="tp-slider-content-2">
                                        @if($description)
                                            <span @if($fontFamily = $shortcode->font_family_of_description) style="--tp-ff-oregano: '{{ $fontFamily }}'" @endif>
                                                {!! BaseHelper::clean($description) !!}
                                            </span>
                                        @endif
                                        @if ($title)
                                            <h3 class="tp-slider-title-2">{!! BaseHelper::clean($title) !!}</h3>
                                        @endif
                                        @if($buttonLabel = $slider->getMetaData('button_label', true))
                                            <div class="tp-slider-btn-2">
                                                <a href="{{ $slider->link }}" class="tp-btn tp-btn-border">
                                                    {!! BaseHelper::clean($buttonLabel) !!}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <div class="tp-slider-thumb-2-wrapper p-relative">
                                        <div class="tp-slider-thumb-2-shape">
                                            @if($shape = $shortcode->shape_2)
                                                {{ RvMedia::image($shape, $slider->title, attributes: ['class' => 'tp-slider-thumb-2-shape-1', 'loading' => false]) }}
                                            @endif
                                            @if($shape = $shortcode->shape_3)
                                                {{ RvMedia::image($shape, $slider->title, attributes: ['class' => 'tp-slider-thumb-2-shape-1', 'loading' => false]) }}
                                            @endif
                                        </div>
                                        <div class="tp-slider-thumb-2 text-end">
                                            <span class="tp-slider-thumb-2-gradient"></span>
                                            @php $slider->title = $title; @endphp
                                            @include(Theme::getThemeNamespace('partials.shortcodes.simple-slider.includes.image', compact('slider')))
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="tp-slider-thumb-2-wrapper p-relative">
                                <div class="tp-slider-thumb-2">
                                    <span class="tp-slider-thumb-2-gradient"></span>
                                    @include(Theme::getThemeNamespace('partials.shortcodes.simple-slider.includes.image', compact('slider')))
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="tp-swiper-dot tp-slider-2-dot"></div>
    </div>
</section>
