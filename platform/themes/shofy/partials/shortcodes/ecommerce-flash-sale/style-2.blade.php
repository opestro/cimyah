@php
    $title = $shortcode->title;
    $subtitle = $shortcode->subtitle;
@endphp

<section
    class="tp-deal-area pt-135 pb-140 p-relative z-index-1 fix scene"
    @if($shortcode->background_color)
        style="background-color: {{ $shortcode->background_color }}"
    @endif
    @if($shortcode->background_image)
        style="background-image: url({{ RvMedia::getImageUrl($shortcode->background_image) }}); background-size: cover;"
    @endif
>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-7">
                <div class="tp-deal-content text-center">
                    @if($title || $subtitle)
                        @if($subtitle)
                            <span class="tp-deal-title-pre">
                                {!! BaseHelper::clean($subtitle) !!}
                                {!! Theme::partial('section-title-shape') !!}
                            </span>
                        @endif
                        @if($title)
                            <h3 class="tp-deal-title">
                                {!! BaseHelper::clean($title) !!}
                            </h3>
                        @endif
                    @endif

                    <div class="tp-deal-countdown">
                        <div class="tp-product-countdown" data-countdown data-date="{{ $flashSale->end_date }}">
                            <div class="tp-product-countdown-inner">
                                <ul>
                                    <li><span data-days>0</span> {{ __('Days') }}</li>
                                    <li><span data-hours>0</span> {{ __('Hours') }}</li>
                                    <li><span data-minutes>0</span> {{ __('Mins') }}</li>
                                    <li><span data-seconds>0</span> {{ __('Secs') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
