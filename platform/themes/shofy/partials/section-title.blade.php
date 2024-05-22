@php
    $title ??= $shortcode->title;
    $subtitle ??= $shortcode->subtitle;
@endphp

@if($title || $subtitle)
    <div @class(['tp-section-title-wrapper', $class ?? null])>
        @if($subtitle)
            <span class="tp-section-title-pre">
                {!! BaseHelper::clean($subtitle) !!}
            </span>
        @endif
        @if($title)
            <h3 class="section-title tp-section-title">
                @include(Theme::getThemeNamespace('partials.section-title-inner'))
            </h3>
        @endif
    </div>
@endif
