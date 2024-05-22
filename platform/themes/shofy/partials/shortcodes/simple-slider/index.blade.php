@php
    $style = in_array($shortcode->style, [1, 2, 3, 4, 5, 'full-width']) ? $shortcode->style : 1;
    $sliders->loadMissing('metadata');

    $shortcode->font_family_of_description = $shortcode->font_family_of_description ?: theme_option('tp_cursive_font');
@endphp

@if($shortcode->customize_font_family_of_description && $shortcode->font_family_of_description !== theme_option('tp_primary_font'))
    {!! BaseHelper::googleFonts('https://fonts.googleapis.com/' . sprintf('css2?family=%s:wght@400&display=swap', urlencode($shortcode->font_family_of_description))) !!}
@endif

{!! Theme::partial("shortcodes.simple-slider.style-$style", compact('sliders', 'shortcode')) !!}
