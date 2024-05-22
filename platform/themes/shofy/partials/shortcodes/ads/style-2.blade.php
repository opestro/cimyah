<section class="tp-banner-area mt-20">
    <div @class(['container-fluid tp-gx-40' => $shortcode->full_width, 'container' => ! $shortcode->full_width])>
        <div class="row tp-gx-20">
            @foreach($ads as $ad)
                @php
                    $countAds = count($ads);
                @endphp

                <div
                    class="
                        @if($countAds > 2)
                            col-xl-4
                        @elseif($countAds > 1)
                            @if($loop->first)
                                col-xl-8 col-lg-7
                            @else
                                col-xl-4 col-lg-5
                            @endif
                        @else
                            col-xl-12
                        @endif
                    "
                >
                    <div class="tp-banner-item-2 p-relative z-index-1 grey-bg-2 mb-20 fix">
                        <div
                            class="tp-banner-thumb-2 include-bg transition-3"
                        >
                            {!! Theme::partial('shortcodes.ads.includes.item', ['item' => $ad]) !!}
                        </div>
                        @if($title = $ad->getMetaData('title', true))
                            <h3 class="tp-banner-title-2">
                                @if ($ad->url)
                                    <a href="{{ $ad->click_url }}" @if($ad->open_in_new_tab) target="_blank" @endif>
                                @endif
                                    {!! BaseHelper::clean(nl2br($title)) !!}</a>
                                @if ($ad->url)
                                    </a>
                                @endif
                            </h3>
                        @endif
                        @if($buttonLabel = $ad->getMetaData('button_label', true))
                            <div class="tp-banner-btn-2">
                                @if ($ad->url)
                                    <a href="{{ $ad->click_url }}" @if($ad->open_in_new_tab) target="_blank" @endif class="tp-btn tp-btn-border tp-btn-border-sm">
                                @endif
                                    {{ $buttonLabel }}
                                    <svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 7.49988L1 7.49988" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M9.9502 1.47554L16.0002 7.49954L9.9502 13.5245" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                @if ($ad->url)
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
