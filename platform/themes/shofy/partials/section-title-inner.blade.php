@if(theme_option('section_title_shape_decorated', 'style-1') === 'style-3')
    @php
        $title = preg_replace('/(\w+)/', '<span>$1</span>', $title, 1);
    @endphp

    {!! BaseHelper::clean($title) !!}
@else
    {!! BaseHelper::clean($title) !!}
    {!! Theme::partial('section-title-shape') !!}
@endif
