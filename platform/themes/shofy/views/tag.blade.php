@php
    Theme::set('pageTitle', $tag->name);
    Theme::layout('full-width');
@endphp

@include(Theme::getThemeNamespace('views.loop'))
