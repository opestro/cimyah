<div class="tp-product-gadget-sidebar mb-40">
    @php
        $category = $products->first()->categories->whereIn('id', $categoryIds)->first();
    @endphp

    @if($category)
        <div class="tp-product-gadget-categories p-relative fix mb-10" @if($shortcode->border_color) style="border-color: {{ $shortcode->border_color }}" @endif>
            @if ($image = ($shortcode->image ?: $category->image))
                <div class="tp-product-gadget-thumb">
                    {{ RvMedia::image($image, $category->name, attributes: ['loading' => false]) }}
                </div>
            @endif
            <h3 class="tp-product-gadget-categories-title">
                @if ($shortcode->title)
                    {{ $shortcode->title }}
                @else
                    <a href="{{ $category->url }}">{{ $category->name }}</a>
                @endif
            </h3>

            @if ($children = $category->activeChildren)
                <div class="tp-product-gadget-categories-list">
                    <ul>
                        @foreach ($children as $child)
                            <li><a href="{{ $child->url }}">{{ $child->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($actionLabel = $shortcode->action_label)
                <div class="tp-product-gadget-btn">
                    <a href="{{ $shortcode->action_url ?: $category->url }}" class="tp-link-btn">
                        {{ $actionLabel }}
                        <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.9998 6.19656L1 6.19656" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M8.75674 0.975394L14 6.19613L8.75674 11.4177" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    @endif

    @if ($ads)
        <div class="tp-product-gadget-banner">
            <div class="tp-product-gadget-banner-slider-active swiper-container">
                <div class="swiper-wrapper">
                    @foreach ($ads as $ad)
                        <div
                            class="tp-product-gadget-banner-item swiper-slide include-bg"
                        >
                            <div class="tp-product-gadget-banner-content position-relative">
                                {!! Theme::partial('shortcodes.ads.includes.item', ['item' => $ad]) !!}

                                @php
                                    $title = $ad->getMetaData('title', true);
                                    $subtitle = $ad->getMetaData('subtitle', true);
                                @endphp

                                @if ($title || $subtitle)
                                    <div class="align-items-center content-overplace d-flex flex-column justify-content-center position-absolute">
                                        @if ($subtitle)
                                            <span class="tp-product-gadget-banner-price">{{ $subtitle }}</span>
                                        @endif

                                        @if ($title)
                                            <h3 class="tp-product-gadget-banner-title">
                                                @if ($ad->url)
                                                    <a href="{{ $ad->click_url }}" @if($ad->open_in_new_tab) target="_blank" @endif>
                                                @endif
                                                    {!! BaseHelper::clean($title) !!}
                                                @if ($ad->url)
                                                    </a>
                                                @endif
                                            </h3>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="tp-product-gadget-banner-slider-dot tp-swiper-dot"></div>
            </div>
        </div>
    @endif
</div>
