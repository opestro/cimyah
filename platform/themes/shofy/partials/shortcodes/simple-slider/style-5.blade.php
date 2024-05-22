<section class="tp-slider-area p-relative z-index-1 fix">
    <div class="tp-slider-active-5 swiper-container"
         data-loop="{{ $shortcode->is_loop == 'yes' }}"
         data-autoplay="{{ $shortcode->is_autoplay == 'yes' }}"
         data-autoplay-speed="{{ in_array($shortcode->autoplay_speed, [2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000]) ? $shortcode->autoplay_speed : 5000 }}"
    >
        <div class="swiper-wrapper">
            @foreach($sliders as $slider)
                @php
                    $title = $slider->title;
                    $buttonLabel = $slider->getMetaData('button_label', true);
                @endphp

                <div class="tp-slider-item-5 scene tp-slider-height-5 swiper-slide d-flex align-items-center" style="background-color: #F3F3F3">
                    <div class="tp-slider-shape-5">
                        @foreach(range(1, 4) as $i)
                            @if($shape = $shortcode->{"shape_$i"})
                                <div class="tp-slider-shape-5-{{ $i }}">
                                    {{ RvMedia::image($shape, $slider->title, attributes: ['class' => 'layer', 'data-depth' => '0.2', 'loading' => false]) }}
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="container">
                        @if($title || $buttonLabel)
                            <div class="row align-items-center">
                                <div class="col-xxl-7 col-xl-7 col-lg-6">
                                    <div class="tp-slider-content-5 p-relative z-index-1">
                                        @if($title)
                                            <h3 class="tp-slider-title-5">
                                                {!! BaseHelper::clean($title) !!}
                                            </h3>
                                        @endif

                                        @if($buttonLabel)
                                            <div class="tp-slider-btn-5">
                                                <a href="{{ $slider->link }}" class="tp-btn-green">
                                                    {!! BaseHelper::clean($buttonLabel) !!}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xxl-5 col-xl-5 col-lg-6">
                                    <div class="tp-slider-thumb-wrapper-5 p-relative">
                                        @if($shape = $shortcode->shape_5)
                                            <div class="tp-slider-thumb-shape-5 one d-none d-sm-block">
                                                {{ RvMedia::image($shape, $slider->title, attributes: ['data-depth' => '0.1', 'class' => 'layer offer', 'loading' => false]) }}
                                            </div>
                                        @endif
                                        <div class="tp-slider-thumb-5 main-img">
                                            @php $sliderAttributes = ['data-depth' => '0.2', 'class' => 'layer', 'loading' => false]; @endphp
                                            @include(Theme::getThemeNamespace('partials.shortcodes.simple-slider.includes.image', compact('slider')))
                                            <span class="tp-slider-thumb-5-gradient"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="tp-slider-thumb-5 main-img">
                                @php $sliderAttributes = ['data-depth' => '0.2', 'class' => 'layer']; @endphp
                                @include(Theme::getThemeNamespace('partials.shortcodes.simple-slider.includes.image', compact('slider')))
                                <span class="tp-slider-thumb-5-gradient"></span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
