<section class="tp-category-area pt-110 pb-110">
    <div class="container">
        {!! Theme::partial('section-title', ['shortcode' => $shortcode, 'centered' => true]) !!}
        <div class="row">
            <div class="col-xl-12">
                <div class="tp-category-slider-5">
                    <div class="tp-category-slider-active-5 swiper-container mb-50">
                        <div class="swiper-wrapper">
                            @foreach($categories as $category)
                                <div class="tp-category-item-5 p-relative z-index-1 fix swiper-slide">
                                    <a href="{{ $category->url }}">
                                        <div
                                            class="tp-category-thumb-5 include-bg"
                                            @if($category->image)
                                                data-background="{{ RvMedia::getImageUrl($category->image) }}"
                                            @endif
                                        ></div>
                                        <div class="tp-category-content-5">
                                            <h3 class="tp-category-title-5">{{ $category->name }}</h3>
                                            <span>
                                                @if ($category->products_count === 1)
                                                    {{ __('1 product') }}
                                                @else
                                                    {{ __(':count products', ['count' => number_format($category->products_count)]) }}
                                                @endif
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tp-category-5-swiper-scrollbar tp-swiper-scrollbar"></div>
                </div>
            </div>
        </div>
    </div>
</section>
