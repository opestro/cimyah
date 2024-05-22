<section class="tp-brand-area pt-30 pb-30">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="tp-brand-slider p-relative">
                    <div class="tp-brand-slider-active swiper-container">
                        <div class="swiper-wrapper">
                            @switch($shortcode->type)
                                @case('custom')
                                    @foreach($tabs as $tab)
                                        @continue(! $tab['image'])

                                        <div class="tp-brand-item swiper-slide text-center">
                                            <a href="{{ $tab['url'] }}">
                                                {{ RvMedia::image($tab['image'], $tab['name']) }}
                                            </a>
                                        </div>
                                    @endforeach

                                    @break

                                @case('brands')
                                    @foreach($brands as $brand)
                                        <div class="tp-brand-item swiper-slide text-center">
                                            <a href="{{ $brand->url }}">
                                                {{ RvMedia::image($brand->image, $brand->name) }}
                                            </a>
                                        </div>
                                    @endforeach

                                    @break
                            @endswitch
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
            </div>
        </div>
    </div>
</section>
