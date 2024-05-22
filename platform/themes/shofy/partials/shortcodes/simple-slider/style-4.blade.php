@php
    Theme::set('hasSlider', true);
@endphp

<section class="tp-slider-area p-relative z-index-1 fix">
    <div class="tp-slider-active-4 khaki-bg"
         data-loop="{{ $shortcode->is_loop == 'yes' }}"
         data-autoplay="{{ $shortcode->is_autoplay == 'yes' }}"
         data-autoplay-speed="{{ in_array($shortcode->autoplay_speed, [2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000]) ? $shortcode->autoplay_speed : 5000 }}"
    >
        @foreach ($sliders as $slider)
            @php
                $title = $slider->title;
                $description = $slider->description;
            @endphp

            <div class="tp-slider-item-4 tp-slider-height-4 p-relative khaki-bg d-flex align-items-center">
                <div class="tp-slider-thumb-4">
                    @include(Theme::getThemeNamespace('partials.shortcodes.simple-slider.includes.image', compact('slider')))
                    <div class="tp-slider-thumb-4-shape">
                        <span class="tp-slider-thumb-4-shape-1"></span>
                        <span class="tp-slider-thumb-4-shape-2"></span>
                    </div>
                </div>

                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-6 col-lg-6 col-md-8">
                            <div class="tp-slider-content-4 p-relative z-index-1">
                                @if($description)
                                    <span @if($fontFamily = $shortcode->font_family_of_description) style="--tp-ff-oregano: '{{ $fontFamily }}'" @endif>
                                        {!! BaseHelper::clean($description) !!}
                                    </span>
                                @endif
                                @if ($title)
                                    <h3 class="tp-slider-title-4">{!! BaseHelper::clean($title) !!}</h3>
                                @endif
                                @if($buttonLabel = $slider->getMetaData('button_label', true))
                                    <div class="tp-slider-btn-4">
                                        <a href="{{ $slider->url }}" class="tp-btn tp-btn-border tp-btn-border-white">
                                            {!! BaseHelper::clean($buttonLabel) !!}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="tp-slider-arrow-4"></div>

    <div class="tp-slider-nav-wrapper d-none">
        <div class="container">
            <div class="tp-slider-nav">
                <div class="tp-slider-nav-active">
                    @foreach ($sliders as $slider)
                        <div class="tp-slider-nav-item d-flex align-items-center"></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
