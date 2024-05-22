@php
    $ads = collect($ads);
    $hasFourth = $ads->skip(3)->isNotEmpty();
@endphp

<section class="tp-banner-area">
    <div class="container">
        @if($hasFourth)
            <div class="row">
                <div class="col-xl-6 col-lg-7">
        @endif
                <div class="row">
                    @foreach($ads->take(3) as $ad)
                        <div @class(['col-xl-12' => $loop->first, 'col-md-6 col-sm-6' => ! $loop->first])>
                            <div @class(['tp-banner-item-4 tp-banner-height-4 fix p-relative z-index-1', 'mb-25' => $loop->first]) style="background-color: #F3F7FF">
                                <div
                                    class="tp-banner-thumb-4 include-bg black-bg transition-3"
                                >
                                    {!! Theme::partial('shortcodes.ads.includes.item', ['item' => $ad]) !!}
                                </div>
                                <div class="tp-banner-content-4">
                                    @if($subtitle = $ad->getMetaData('subtitle', true))
                                        <span>{!! BaseHelper::clean(nl2br($subtitle)) !!}</span>
                                    @endif
                                    @if($title = $ad->getMetaData('title', true))
                                        <h3 class="tp-banner-title-4">
                                            @if ($ad->url)
                                                <a href="{{ $ad->click_url }}" @if($ad->open_in_new_tab) target="_blank" @endif>
                                            @endif
                                                {!! BaseHelper::clean(nl2br($title)) !!}</a>
                                            @if ($ad->url)
                                                </a>
                                            @endif
                                        </h3>
                                    @endif
                                    @if(($buttonLabel = $ad->getMetaData('button_label', true)) && $loop->first)
                                        <div class="tp-banner-btn-4">
                                            @if ($ad->url)
                                                <a href="{{ $ad->click_url }}" @if($ad->open_in_new_tab) target="_blank" @endif class="tp-btn tp-btn-border">
                                            @endif
                                                {!! BaseHelper::clean($buttonLabel) !!}
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
                        </div>
                    @endforeach
                </div>
            </div>

            @if($hasFourth)
                @php
                    $ad = $ads->skip(3)->last();
                @endphp

                <div class="col-xl-6 col-lg-5">
                    <div class="tp-banner-full tp-banner-full-height fix p-relative z-index-1">
                        <div
                            class="tp-banner-full-thumb include-bg black-bg transition-3"
                        >
                            {!! Theme::partial('shortcodes.ads.includes.item', ['item' => $ad]) !!}
                        </div>
                        <div class="tp-banner-full-content">
                            @if($subtitle = $ad->getMetaData('subtitle', true))
                                <span>{!! BaseHelper::clean(nl2br($subtitle)) !!}</span>
                            @endif
                            @if($title = $ad->getMetaData('title', true))
                                <h3 class="tp-banner-full-title">
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
                                <div class="tp-banner-full-btn">
                                    @if ($ad->url)
                                        <a href="{{ $ad->click_url }}" @if($ad->open_in_new_tab) target="_blank" @endif class="tp-btn tp-btn-border">
                                    @endif
                                        {!! BaseHelper::clean($buttonLabel) !!}
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
                </div>
            @endif
        @if($hasFourth)
                </div>
            </div>
        @endif
</section>
