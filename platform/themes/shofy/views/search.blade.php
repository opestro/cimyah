@php
    Theme::set('pageTitle', __('Search result for: ":query"', ['query' => BaseHelper::stringify(request()->input('q'))]));
    Theme::layout('full-width');
@endphp

@include(Theme::getThemeNamespace('views.loop'))
