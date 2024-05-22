@if ($categories->isNotEmpty())
    <div class="tp-sidebar-widget widget_categories mb-35">
        @if ($config['name'])
            <h3 class="tp-sidebar-widget-title">{!! BaseHelper::clean($config['name']) !!}</h3>
        @endif
        <div class="tp-sidebar-widget-content">
            <ul>
                @foreach ($categories as $category)
                    <li>
                        <a href="{{ $category->url }}" title="{{ $category->name }}">
                            {{ $category->name }}
                            @if ($category->posts_count)
                                <span>({{ $category->posts_count }})</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
