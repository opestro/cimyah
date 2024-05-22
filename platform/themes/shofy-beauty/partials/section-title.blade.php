@php
    $title = $shortcode->title;
    $subtitle = $shortcode->subtitle;
@endphp

@if($title || $subtitle)
    <div class="tp-section-title-wrapper-3 mb-45">
        @if($subtitle)
            <span class="tp-section-title-pre-3">
                {!! BaseHelper::clean($subtitle) !!}
            </span>
        @endif
        @if($title)
            <h3 class="section-title tp-section-title-3">
                @include(Theme::getThemeNamespace('partials.section-title-inner'))
            </h3>
        @endif
    </div>
@endif
