@if (function_exists('get_galleries') && $galleries->isNotEmpty())
    <div class="gallery-wrap">
        @foreach ($galleries as $gallery)
            <div class="gallery-item">
                <div class="img-wrap">
                    <a href="{{ $gallery->url }}" class="d-inline-block">
                        {{ RvMedia::image($gallery->image, $gallery->name, 'medium') }}
                    </a>
                </div>
                <div class="gallery-detail">
                    <div class="gallery-title"><a href="{{ $gallery->url }}">{{ $gallery->name }}</a></div>
                    @if (trim($gallery->user->name))
                        <div class="gallery-author">
                            {{ __('By :name', ['name' => $gallery->user->name]) }}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    <div style="clear: both"></div>
@endif
