@php
    Theme::set('pageTitle', $category->name);
    Theme::layout('full-width');
@endphp

@include(Theme::getThemeNamespace('views.loop'))
