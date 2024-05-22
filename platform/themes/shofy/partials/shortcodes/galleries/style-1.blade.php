<div class="tp-instagram-area pb-70">
    <div class="container">
        {!! Theme::partial('section-title', ['shortcode' => $shortcode, 'class' => 'text-center mb-40']) !!}

        <div class="tp-gallery-slider swiper-container">
            <div class="swiper-wrapper">
                @foreach($galleries as $gallery)
                    <div class="swiper-slide">
                        <div class="tp-instagram-item p-relative z-index-1 fix mb-30 w-img">
                            {{ RvMedia::image($gallery->image, $gallery->name, 'medium') }}
                            <div class="tp-instagram-icon">
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
</div>
