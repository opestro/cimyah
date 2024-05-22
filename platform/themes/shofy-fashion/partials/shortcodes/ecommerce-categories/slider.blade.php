<section class="tp-category-area pb-95 pt-95">
    <div class="container">
        {!! Theme::partial('section-title', ['shortcode' => $shortcode, 'class' => 'text-center mb-40']) !!}

        <div class="row">
            <div class="col-xl-12">
                <div class="tp-category-slider-2">
                    <div class="tp-category-slider-active-2 swiper-container mb-50">
                        <div class="swiper-wrapper">
                            @foreach ($categories as $category)
                                <div class="tp-category-item-2 p-relative z-index-1 text-center swiper-slide">
                                    <div class="tp-category-thumb-2">
                                        <a href="{{ $category->url }}">
                                            {{ RvMedia::image($category->image, $category->name, 'medium', useDefaultImage: true) }}
                                        </a>
                                    </div>
                                    <div class="tp-category-content-2">
                                        <span>
                                            @if ($category->products_count === 1)
                                                {{ __('1 product') }}
                                            @else
                                                {{ __(':count products', ['count' => number_format($category->products_count)]) }}
                                            @endif
                                        </span>
                                        <h3 class="tp-category-title-2">
                                            <a href="{{ $category->url }}">{{ $category->name }}</a>
                                        </h3>
                                        <div class="tp-category-btn-2">
                                            <a href="{{ $category->url }}" class="tp-btn tp-btn-border">
                                                {{ __('Shop now') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="swiper-scrollbar tp-swiper-scrollbar tp-swiper-scrollbar-drag"></div>
                </div>
            </div>
        </div>
    </div>
</section>
