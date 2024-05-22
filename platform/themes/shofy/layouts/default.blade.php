@extends(Theme::getThemeNamespace('layouts.base'))

@section('content')
    {!! Theme::breadcrumb()->render(Theme::getThemeNamespace('partials.breadcrumbs')) !!}

    <section class="tp-page-area pb-80 pt-50">
        <div class="container">
            {!! Theme::content() !!}
        </div>
    </section>
@endsection
