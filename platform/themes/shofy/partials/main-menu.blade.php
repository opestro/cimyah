<ul {!! $options !!}>
    @foreach ($menu_nodes->loadMissing('metadata') as $key => $row)
        <li @class(['has-dropdown' => $row->has_child])>
            <a href="{{ url($row->url) }}" @if ($row->target !== '_self') target="{{ $row->target }}" @endif>
                @if ($iconImage = $row->getMetaData('icon_image', true))
                    <img src="{{ RvMedia::getImageUrl($iconImage) }}" alt="{{ $row->title }}" width="12" height="12"/>
                @elseif ($row->icon_font)
                    <i class="{{ trim($row->icon_font) }}"></i>
                @endif

                {{ $row->title }}

                @if ($row->has_child)
                    <x-core::icon name="ti ti-chevron-down" />
                @endif
            </a>

            @if ($row->has_child)
                {!! Menu::generateMenu(['menu' => $menu, 'menu_nodes' => $row->child, 'view' => 'main-menu', 'options' => ['class' => 'tp-submenu']]) !!}
            @endif
        </li>
    @endforeach
</ul>
