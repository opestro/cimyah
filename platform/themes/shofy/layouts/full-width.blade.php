@extends(Theme::getThemeNamespace('layouts.base'))

@section('content')
    {!! Theme::breadcrumb()->render(Theme::getThemeNamespace('partials.breadcrumbs')) !!}

    {!! Theme::content() !!}
@endsection
