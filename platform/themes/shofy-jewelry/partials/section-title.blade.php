@php
    $title = $shortcode->title;
    $subtitle = $shortcode->subtitle;
@endphp

@if($title || $subtitle)
    <div @class(['tp-section-title-wrapper-4', $class ?? null])>
        @if($subtitle)
            <span class="tp-section-title-pre-4">
                {!! BaseHelper::clean($subtitle) !!}
                {!! Theme::partial('section-title-shape') !!}
            </span>
        @endif
        @if($title)
            <h3 class="section-title tp-section-title-4">
                @include(Theme::getThemeNamespace('partials.section-title-inner'))
            </h3>
        @endif
    </div>
@endif

