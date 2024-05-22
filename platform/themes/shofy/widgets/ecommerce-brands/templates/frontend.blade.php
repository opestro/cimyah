@if($brands->isNotEmpty())
    <section class="tp-brand-area pb-40">
        @switch($config['style'])
            @case('slider')
                <div class="tp-brand-slider p-relative">
                    <div class="tp-brand-slider-active swiper-container">
                        <div class="swiper-wrapper">
                            @foreach($brands as $brand)
                                <div class="tp-brand-item swiper-slide text-center">
                                    <a href="{{ $brand->url }}">
                                        {{ RvMedia::image($brand->logo, $brand->name, attributes: ['loading' => false]) }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tp-brand-slider-arrow">
                        <button class="tp-brand-slider-button-prev">
                            <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 1L1 7L7 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <button class="tp-brand-slider-button-next">
                            <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 1L7 7L1 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </div>

                @break

            @case('grid')
                <div class="tp-brand-grid">
                    <div class="row">
                        @foreach($brands as $brand)
                            <div class="col-lg-2 col-md-3 col-6">
                                <div class="tp-brand-item text-center">
                                    <a href="{{ $brand->url }}">
                                        {{ RvMedia::image($brand->logo, $brand->name, attributes: ['loading' => false]) }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @break
        @endswitch
    </section>
@endif
