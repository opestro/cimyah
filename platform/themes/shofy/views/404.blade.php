@php
    SeoHelper::setTitle(__('Page not found') . ' - ' . theme_option('site_title'));
    Theme::fireEventGlobalAssets();
@endphp

@extends(Theme::getThemeNamespace('layouts.base'))

@section('content')
    <section class="tp-error-area pt-110 pb-110">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8 col-md-10">
                    <div class="text-center tp-error-content">
                        <div class="tp-error-thumb">
                            <img src="{{ theme_option('404_page_image') ? RvMedia::getImageUrl(theme_option('404_page_image')) : Theme::asset()->url('images/404.png') }}" alt="{{ theme_option('site_title') }}">
                        </div>

                        <h3 class="tp-error-title">{{ __('Oops! Page not found') }}</h3>
                        <p>{{ __("Whoops, this is embarrassing. Looks like the page you were looking for wasn't found.") }}</p>

                        <a href="{{ BaseHelper::getHomepageUrl() }}" class="tp-error-btn">{{ __('Back to Home') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
