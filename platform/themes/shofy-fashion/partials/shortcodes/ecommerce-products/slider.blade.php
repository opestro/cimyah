<section class="tp-trending-area pt-140 pb-150">
    <div class="container">
        @if($shortcode->with_sidebar && $ads)
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-6">
                    @endif
                    <div class="tp-trending-wrapper">
                        {!! Theme::partial('section-title', ['shortcode' => $shortcode, 'class' => 'mb-50']) !!}

                        <div class="tp-trending-slider">
                            <div
                                class="tp-trending-slider-active swiper-container"
                                data-items-per-view="{{ $shortcode->with_sidebar ? 2 : 4 }}"
                            >
                                <div class="swiper-wrapper">
                                    @foreach($products as $product)
                                        <div class="tp-trending-item swiper-slide">
                                            @include(Theme::getThemeNamespace('views.ecommerce.includes.product-item'))
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tp-trending-slider-dot tp-swiper-dot text-center mt-45"></div>
                        </div>
                    </div>
                    @if($shortcode->with_sidebar && $ads)
                </div>

                <div class="col-xl-4 col-lg-5 col-md-8 col-sm-10">
                    @foreach($ads as $ad)
                        @php
                            $title = $ad->getMetaData('title', true);
                            $buttonLabel = $shortcode->action_label ?: $ad->getMetaData('button_label', true);
                            $buttonUrl = $shortcode->action_url ?: $ad->click_url;
                        @endphp

                        <div class="tp-trending-banner p-relative ml-35">
                            <div class="tp-trending-banner-thumb w-img include-bg">
                                {!! Theme::partial('shortcodes.ads.includes.item', ['item' => $ad]) !!}
                            </div>
                            <div class="tp-trending-banner-content">
                                @if ($title)
                                    <h3 class="tp-trending-banner-title">
                                        @if ($ad->url)
                                            <a href="{{ $ad->click_url }}" @if($ad->open_in_new_tab) target="_blank" @endif>
                                                {!! BaseHelper::clean($title) !!}
                                            </a>
                                        @else
                                            {!! BaseHelper::clean(nl2br($title)) !!}
                                        @endif
                                    </h3>
                                @endif
                                @if($buttonLabel)
                                    <div class="tp-trending-banner-btn">
                                        <a href="{{ $buttonUrl }}" class="tp-btn tp-btn-border tp-btn-border-white tp-btn-border-white-sm">
                                            {!! BaseHelper::clean($buttonLabel) !!}
                                            <svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M16 7.5L1 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M9.9502 1.47541L16.0002 7.49941L9.9502 13.5244" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
    </div>
</section>
