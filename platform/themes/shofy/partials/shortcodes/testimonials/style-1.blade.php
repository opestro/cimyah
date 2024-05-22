<section class="tp-testimonial-area grey-bg-7 pt-130 pb-135">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="tp-testimonial-slider p-relative z-index-1">
                    <div class="tp-testimonial-shape">
                        <span class="tp-testimonial-shape-gradient"></span>
                    </div>
                    @if($title = $shortcode->title)
                        <h3 class="tp-testimonial-section-title text-center">
                            {!! BaseHelper::clean($title) !!}
                        </h3>
                    @endif
                    <div class="row justify-content-center">
                        <div class="col-xl-8 col-lg-8 col-md-10">
                            <div class="tp-testimonial-slider-active swiper-container">
                                <div class="swiper-wrapper">
                                    @foreach($testimonials as $testimonial)
                                        <div class="tp-testimonial-item text-center mb-20 swiper-slide">
                                            <div class="tp-testimonial-rating">
                                                <span><x-core::icon name="ti ti-star" /></span>
                                                <span><x-core::icon name="ti ti-star" /></span>
                                                <span><x-core::icon name="ti ti-star" /></span>
                                                <span><x-core::icon name="ti ti-star" /></span>
                                                <span><x-core::icon name="ti ti-star" /></span>
                                            </div>
                                            <div class="tp-testimonial-content">
                                                <p>“{!! BaseHelper::clean($testimonial->content) !!}”</p>
                                            </div>
                                            <div class="tp-testimonial-user-wrapper d-flex align-items-center justify-content-center">
                                                <div class="tp-testimonial-user d-flex align-items-center">
                                                    <div class="tp-testimonial-avater mr-10">
                                                        {{ RvMedia::image($testimonial->image, $testimonial->name) }}
                                                    </div>
                                                    <div class="tp-testimonial-user-info tp-testimonial-user-translate">
                                                        <h3 class="tp-testimonial-user-title">{{ $testimonial->name }}</h3>
                                                        <span class="tp-testimonial-designation">{{ $testimonial->company }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tp-testimonial-arrow d-none d-md-block">
                        <button class="tp-testimonial-slider-button-prev">
                            <svg width="17" height="14" viewBox="0 0 17 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1.061 6.99959L16 6.99959" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M7.08618 1L1.06079 6.9995L7.08618 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <button class="tp-testimonial-slider-button-next">
                            <svg width="17" height="14" viewBox="0 0 17 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.939 6.99959L1 6.99959" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M9.91382 1L15.9392 6.9995L9.91382 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                    <div class="tp-testimonial-slider-dot tp-swiper-dot text-center mt-30 tp-swiper-dot-style-darkRed d-md-none"></div>
                </div>
            </div>
        </div>
    </div>
</section>
