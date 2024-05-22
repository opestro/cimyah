@php
    $title = $shortcode->title;
    $subtitle = $shortcode->subtitle;
@endphp

<section class="tp-testimonial-area pt-30 pb-30">
    <div class="container">
        @if($title || $subtitle)
            <div class="row">
                <div class="col-xl-12">
                    <div class="tp-section-title-wrapper-3 mb-45 text-center">
                        @if($subtitle)
                            <span class="tp-section-title-pre-3">{!! BaseHelper::clean($subtitle) !!}</span>
                        @endif
                        @if($title = $shortcode->title)
                            <h3 class="section-title tp-section-title-3">{!! BaseHelper::clean($title) !!}</h3>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-xl-12">
                <div class="tp-testimonial-slider-3">
                    <div class="tp-testimoinal-slider-active-3 swiper-container">
                        <div class="swiper-wrapper">
                            @foreach($testimonials as $testimonial)
                                <div class="tp-testimonial-item-3 swiper-slide grey-bg-7 p-relative z-index-1">
                                    <div class="tp-testimonial-shape-3">
                                        <img class="tp-testimonial-shape-3-quote" src="{{ Theme::asset()->url('images/testimonial-quote.png') }}" alt="{{ $testimonial->name }}" />
                                    </div>
                                    <div class="tp-testimonial-rating tp-testimonial-rating-3">
                                        <span><x-core::icon name="ti ti-star" /></span>
                                        <span><x-core::icon name="ti ti-star" /></span>
                                        <span><x-core::icon name="ti ti-star" /></span>
                                        <span><x-core::icon name="ti ti-star" /></span>
                                        <span><x-core::icon name="ti ti-star" /></span>
                                    </div>
                                    <div class="tp-testimonial-content-3">
                                        <p>
                                            {!! BaseHelper::clean($testimonial->content) !!}
                                        </p>
                                    </div>
                                    <div class="tp-testimonial-user-wrapper-3 d-flex">
                                        <div class="tp-testimonial-user-3 d-flex align-items-center">
                                            <div class="tp-testimonial-avater-3 mr-10">
                                                {{ RvMedia::image($testimonial->image, $testimonial->name) }}
                                            </div>
                                            <div class="tp-testimonial-user-3-info tp-testimonial-user-translate">
                                                <h3 class="tp-testimonial-user-3-title">{{ $testimonial->name }}</h3>
                                                <span class="tp-testimonial-3-designation">{{ $testimonial->company }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tp-testimoinal-slider-dot-3 tp-swiper-dot-border text-center mt-50"></div>
                </div>
            </div>
        </div>
    </div>
</section>
