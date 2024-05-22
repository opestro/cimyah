@php
    $title = $shortcode->title;
    $subtitle = $shortcode->subtitle;
    $centered ??= false;
@endphp

@if($title || $subtitle)
    <div @class(['tp-section-title-wrapper-5 mb-50', 'text-center' => $centered])>
        @if($subtitle)
            <span class="tp-section-title-pre-5">
                {!! BaseHelper::clean($subtitle) !!}
                    {!! Theme::partial('section-title-shape') !!}
            </span>
        @endif
        @if($title)
            <h3 class="section-title tp-section-title-5">
                @include(Theme::getThemeNamespace('partials.section-title-inner'))
            </h3>
        @endif
    </div>
@endif
