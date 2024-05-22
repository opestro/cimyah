@if ($tags->isNotEmpty())
    <div class="tp-sidebar-widget mb-35">
        @if ($config['name'])
            <h3 class="tp-sidebar-widget-title">{!! BaseHelper::clean($config['name']) !!}</h3>
        @endif

        <div class="tp-sidebar-widget-content tagcloud">
            @foreach ($tags as $tag)
                <a href="{{ $tag->url }}" title="{{ $tag->name }}">{{ $tag->name }}</a>
            @endforeach
        </div>
    </div>
@endif
