<ul {!! $options !!}>
    @foreach ($menu_nodes->loadMissing('metadata') as $key => $node)
        <li>
            <a href="{{ url($node->url) }}">
                @if ($iconImage = $node->getMetaData('icon_image', true))
                    <img src="{{ RvMedia::getImageUrl($iconImage) }}" alt="{{ $node->title }}" width="14" height="14" />
                @elseif ($node->icon_font)
                    <i class="{{ trim($node->icon_font) }}"></i>
                @endif
                {{ $node->title }}
            </a>
        </li>
    @endforeach
</ul>
