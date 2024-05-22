@php
    $title = $shortcode->title;
    $subtitle = $shortcode->subtitle;
@endphp

<section class="tp-instagram-area tp-instagram-style-4 pt-30 pb-20">
    <div class="container-fluid pl-20 pr-20">
        @if($title || $subtitle)
            <div class="row">
                <div class="col-xl-12">
                    <div class="tp-section-title-wrapper-4 mb-50 text-center">
                        @if($title)
                            <h3 class="section-title tp-section-title-4">
                                {!! BaseHelper::clean($title) !!}
                            </h3>
                        @endif
                        @if($subtitle)
                            <p>{!! BaseHelper::clean($subtitle) !!}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        <div class="tp-gallery-slider swiper-container">
            <div class="swiper-wrapper">
                @foreach($galleries as $gallery)
                    <div class="swiper-slide">
                        <div class="tp-instagram-item-2 w-img">
                            {{ RvMedia::image($gallery->image, $gallery->name, 'medium') }}
                            <div class="tp-instagram-icon-2">
                                <a href="{{ $gallery->url }}">
                                    {{ $gallery->name }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
