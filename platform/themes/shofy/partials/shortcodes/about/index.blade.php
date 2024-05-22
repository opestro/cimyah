<section class="tp-about-area pt-120 pb-120">
    <div class="container">
        <div class="row">
            <div class="col-xl-5 col-lg-6">
                @if($shortcode->image_1 || $shortcode->image_2)
                    <div class="tp-about-thumb-wrapper p-relative mr-35">
                        @if($shortcode->image_1)
                            <div class="tp-about-thumb m-img">
                                {!! RvMedia::image($shortcode->image_1, $shortcode->title) !!}
                            </div>
                        @endif
                        @if($shortcode->image_2)
                            <div class="tp-about-thumb-2">
                                {!! RvMedia::image($shortcode->image_2, $shortcode->title) !!}
                            </div>
                        @endif
                    </div>
                @endif
            </div>
            <div class="col-xl-7 col-lg-6">
                <div class="tp-about-wrapper pl-80 pt-75 pr-60">
                    <div class="tp-section-title-wrapper-4 mb-50">
                        @if($shortcode->subtitle)
                            <span class="tp-section-title-pre-4">{!! BaseHelper::clean($shortcode->subtitle) !!}</span>
                        @endif
                        @if($shortcode->title)
                            <h3 class="section-title tp-section-title-4 fz-50">{!! BaseHelper::clean($shortcode->title) !!}</h3>
                        @endif
                    </div>
                    <div class="tp-about-content pl-120">
                        @if($shortcode->description)
                            <p>{!! BaseHelper::clean($shortcode->description) !!}</p>
                        @endif

                        @if($shortcode->action_label)
                            <div class="tp-about-btn">
                                <a href="{{ $shortcode->action_url }}" class="tp-btn">
                                    {!! BaseHelper::clean($shortcode->action_label) !!}
                                    <svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 7.49976L1 7.49976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M9.9502 1.47541L16.0002 7.49941L9.9502 13.5244" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
