<div class="tp-product-banner-area pt-30 pb-30">
    <div class="container">
        <div class="tp-product-banner-slider fix">
            <div class="tp-product-banner-slider-active swiper-container">
                <div class="swiper-wrapper">
                    @foreach($ads as $ad)
                        @php
                            $title = $ad->getMetaData('title', true);
                            $subtitle = $ad->getMetaData('subtitle', true);
                            $buttonLabel = $ad->getMetaData('button_label', true)
                        @endphp
                        <div @class(['tp-product-banner-inner theme-bg p-relative z-index-1 fix swiper-slide', 'has-content' => $title || $subtitle])>
                            @if($title || $subtitle)
                                <div class="row align-items-center">
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="tp-product-banner-content p-relative z-index-1">
                                            @if($subtitle)
                                                <span class="tp-product-banner-subtitle">{!! BaseHelper::clean(nl2br($subtitle)) !!}</a></span>
                                            @endif
                                            @if($title)
                                                <h3 class="tp-product-banner-title">{!! BaseHelper::clean(nl2br($title)) !!}</a></h3>
                                            @endif
                                            @if($buttonLabel && $ad->url)
                                                <div class="tp-product-banner-btn">
                                                    <a href="{{ $ad->click_url }}" @if($ad->open_in_new_tab) target="_blank" @endif class="tp-btn tp-btn-2">
                                                        {{ $buttonLabel }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="tp-product-banner-thumb-wrapper p-relative">
                                            <div class="tp-product-banner-thumb-shape">
                                                <div class="tp-product-banner-thumb text-end p-relative z-index-1">
                                                    {!! Theme::partial('shortcodes.ads.includes.item', ['item' => $ad]) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <a href="{{ $ad->click_url }}" @if($ad->open_in_new_tab) target="_blank" @endif>
                                    {!! Theme::partial('shortcodes.ads.includes.item', ['item' => $ad]) !!}
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="tp-product-banner-slider-dot tp-swiper-dot"></div>
            </div>
        </div>
    </div>
</div>
