@if (! empty($sliders))
    <div class="simple-slider-wrapper">
        <div
            class="owl-slider owl-carousel carousel--nav inside"
            data-owl-auto="true"
            data-owl-loop="true"
            data-owl-speed="7000"
            data-owl-gap="0"
            data-owl-nav="false"
            data-owl-dots="true"
            data-owl-item="1"
            data-owl-item-xs="1"
            data-owl-item-sm="1"
            data-owl-item-md="1"
            data-owl-item-lg="1"
            data-owl-duration="1000"
            data-owl-mousedrag="on"
        >
            @foreach ($sliders as $slider)
                <div class="slider-item">
                    @if ($slider->link)
                        <a
                            class="slider-item-overlay"
                            href="{{ $slider->link }}"
                        >
                    @endif
                    <img
                        src="{{ RvMedia::getImageUrl($slider->image) }}"
                        alt="{{ $slider->title }}"
                    >
                    @if ($slider->link)
                        </a>
                    @endif
                    @if ($slider->title || $slider->description)
                        <header class="slider-item-header">
                            @if ($slider->title)
                                <h2 class="slider-item-title">{{ $slider->title }}</h2>
                            @endif
                            @if ($slider->description)
                                <span class="slider-item-description">{{ $slider->description }}</span>
                            @endif
                        </header>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif
