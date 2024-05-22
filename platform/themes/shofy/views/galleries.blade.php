@php
    Theme::layout('full-width');
    Theme::set('pageTitle', __('Galleries'));
@endphp

<section class="pb-100">
    <div class="container">
        @if (isset($galleries) && $galleries->isNotEmpty())
            <div class="row row-cols-4 g-3">
                @foreach($galleries as $gallery)
                    <div class="col">
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
        @else
            <div class="text-center">
                <h3>{{ __('No galleries found') }}</h3>
            </div>
        @endif
    </div>
</section>
