<section class="tp-banner-area pt-30 pb-30" @if(BaseHelper::isRtlEnabled()) dir="ltr" @endif>
    <div class="container">
        <div class="row">
            @foreach($ads as $ad)
                @php($countAds = count($ads))
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
                    <div @class(['tp-banner-item tp-banner-height p-relative mb-30 z-index-1 fix', 'tp-banner-item-sm' => $countAds > 2])>
                        <div class="tp-banner-thumb include-bg transition-3" >
                            {!! Theme::partial('shortcodes.ads.includes.item', ['item' => $ad]) !!}
                        </div>

                        <div class="tp-banner-content">
                            @if($subtitle = $ad->getMetaData('subtitle', true))
                                <span>{!! BaseHelper::clean(nl2br($subtitle)) !!}</span>
                            @endif
                            @if($title = $ad->getMetaData('title', true))
                                <h3 class="tp-banner-title">
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
                                <div class="tp-banner-btn">
                                    @if ($ad->url)
                                        <a href="{{ $ad->click_url }}" @if($ad->open_in_new_tab) target="_blank" @endif class="tp-link-btn">
                                    @endif
                                        {{ $buttonLabel }}
                                        <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13.9998 6.19656L1 6.19656" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M8.75674 0.975394L14 6.19613L8.75674 11.4177" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    @if ($ad->url)
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
