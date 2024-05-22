@php
    $iconImage = $category->icon_image;
    $icon = $category->icon;
@endphp

@if ($iconImage || $icon)
    <span>
        @if ($iconImage)
            <img src="{{ RvMedia::getImageUrl($iconImage) }}" alt="{{ $category->name }}" width="18" height="18">
        @elseif ($icon)
            {!! BaseHelper::renderIcon($icon) !!}
        @endif
    </span>
@endif

{{ $category->name }}
