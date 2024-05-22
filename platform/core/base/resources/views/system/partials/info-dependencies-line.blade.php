<ul>
    @if (is_array($item->dependencies))
        @foreach ($item->dependencies as $dependencyName => $dependencyVersion)
            <li class="py-1">{{ $dependencyName }}: <x-core::badge
                    color="primary"
                    :label="$dependencyVersion"
                /></li>
        @endforeach
    @else
        <li><x-core::badge
                color="primary"
                :label="$dependencyVersion"
            /></li>
    @endif
</ul>
