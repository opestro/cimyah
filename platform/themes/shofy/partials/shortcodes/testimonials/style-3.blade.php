<section class="tp-testimonial-area pt-30 pb-30">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="tp-testimonial-slider-wrapper-5">
                    @php
                        $title = $shortcode->title;
                        $subtitle = $shortcode->subtitle;
                    @endphp

                    @if($title || $subtitle)
                        <div class="row">
                            <div class="col-xl-7 offset-xl-3">
                                <div class="tp-section-title-wrapper-5 mb-45">
                                    @if($subtitle)
                                        <span class="tp-section-title-pre-5">
                                            {!! BaseHelper::clean($subtitle) !!}
                                            {!! Theme::partial('section-title-shape') !!}
                                        </span>
                                    @endif
                                    @if($title)
                                        <h3 class="section-title tp-section-title-5">
                                            {!! BaseHelper::clean($title) !!}
                                        </h3>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="tp-testimonial-slider-5 p-relative">
                        <div class="tp-testimonial-slider-active-5 swiper-container pb-15">
                            <div class="swiper-wrapper">
                                @foreach($testimonials as $testimonial)
                                    <div class="tp-testimonial-item-5 d-md-flex swiper-slide white-bg">
                                        <div class="tp-testimonial-avater-wrapper-5 p-relative">
                                            <div class="tp-avater-rounded mr-60">
                                                <div class="tp-testimonial-avater-5">
                                                    {{ RvMedia::image($testimonial->image, $testimonial->name) }}
                                                </div>
                                            </div>
                                            <span class="quote-icon">
                                                <img src="{{ Theme::asset()->url('images/testimonial-quote.png') }}" alt="quote">
                                            </span>
                                        </div>

                                        <div class="tp-testimonial-content-5">
                                            <div class="tp-testimonial-rating tp-testimonial-rating-5">
                                                <span><x-core::icon name="ti ti-star" /></span>
                                                <span><x-core::icon name="ti ti-star" /></span>
                                                <span><x-core::icon name="ti ti-star" /></span>
                                                <span><x-core::icon name="ti ti-star" /></span>
                                                <span><x-core::icon name="ti ti-star" /></span>
                                            </div>

                                            <p>{!! BaseHelper::clean($testimonial->content) !!}.</p>

                                            <div class="tp-testimonial-user-5-info">
                                                <h3 class="tp-testimonial-user-5-title">{{ $testimonial->name }}</h3>
                                                <span class="tp-testimonial-user-5-designation">{{ $testimonial->company }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tp-testimonial-arrow-5">
                            <button type="button" class="tp-testimonial-slider-5-button-prev">
                                <svg width="33" height="16" viewBox="0 0 33 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.10059 7.97559L32.1006 7.97559" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M8.15039 0.999999L1.12076 7.99942L8.15039 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                            <button type="button" class="tp-testimonial-slider-5-button-next">
                                <svg width="33" height="16" viewBox="0 0 33 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M31.1006 7.97559L1.10059 7.97559" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M25.0508 0.999999L32.0804 7.99942L25.0508 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
