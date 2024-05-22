@php
    $hasLogoLight ??= false;
    $defaultIsDark ??= true;

    $logo = theme_option('logo');
    $logoLight = theme_option('logo_light');

    $height = theme_option('logo_height', 35);
    $attributes = [
        'style' => sprintf('height: %s', is_numeric($height) ? "{$height}px" : $height),
        'loading' => false,
    ];
@endphp

@if ($logo || $logoLight)
    <div class="logo">
        <a href="{{ BaseHelper::getHomepageUrl() }}">
            @if ($hasLogoLight)
                {{ RvMedia::image($logoLight ?: $logo, theme_option('site_title'), attributes: ['class' => 'logo-light', ...$attributes]) }}
                {{ RvMedia::image($logo ?: $logoLight, theme_option('site_title'), attributes: ['class' => 'logo-dark', ...$attributes]) }}
            @else
                {{ RvMedia::image($defaultIsDark ? $logo : $logoLight, theme_option('site_title'), attributes: $attributes) }}
            @endif
        </a>
    </div>
@endif
