<ul class="menu">
    @foreach (DashboardMenu::getAll('vendor') as $item)
        @continue(! $item['name'])
        <li>
            <a
                href="{{ $item['url'] }}"
                @class(['active' => $item['active']])
            >
                <x-core::icon :name="$item['icon']" />

                {{ $item['name'] }}
            </a>
        </li>
    @endforeach
</ul>
