@php
    $layout = request()->query('layout', theme_option('blog_posts_layout', 'grid'));
    $layout = in_array($layout, ['grid', 'list']) ? $layout : 'grid';
    Theme::layout('full-width');
@endphp

<section class="tp-blog-grid-area pb-120">
    <div class="container">
        <div class="row">
            <div class="col-xl-9 col-lg-8">
                <div class="tp-blog-grid-wrapper">
                    @if ($posts->isNotEmpty())
                        <div class="tp-blog-grid-top d-flex justify-content-between mb-40">
                            <div class="tp-blog-grid-result">
                                <p>{{ __('Showing :from to :to of :total results', ['from' => $posts->firstItem(), 'to' => $posts->lastItem(), 'total' => $posts->total()]) }}</p>
                            </div>
                            <div class="tp-blog-grid-tab tp-tab">
                                <nav class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a @class(['nav-link', 'active' => $layout === 'grid']) id="nav-grid-tab" type="button" role="tab" aria-controls="nav-grid" aria-selected="true" href="{{ request()->fullUrlWithQuery(['layout' => 'grid']) }}">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M16.3328 6.01317V2.9865C16.3328 2.0465 15.9061 1.6665 14.8461 1.6665H12.1528C11.0928 1.6665 10.6661 2.0465 10.6661 2.9865V6.0065C10.6661 6.95317 11.0928 7.3265 12.1528 7.3265H14.8461C15.9061 7.33317 16.3328 6.95317 16.3328 6.01317Z"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                            <path
                                                d="M16.3328 15.18V12.4867C16.3328 11.4267 15.9061 11 14.8461 11H12.1528C11.0928 11 10.6661 11.4267 10.6661 12.4867V15.18C10.6661 16.24 11.0928 16.6667 12.1528 16.6667H14.8461C15.9061 16.6667 16.3328 16.24 16.3328 15.18Z"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                            <path
                                                d="M7.33281 6.01317V2.9865C7.33281 2.0465 6.90614 1.6665 5.84614 1.6665H3.1528C2.0928 1.6665 1.66614 2.0465 1.66614 2.9865V6.0065C1.66614 6.95317 2.0928 7.3265 3.1528 7.3265H5.84614C6.90614 7.33317 7.33281 6.95317 7.33281 6.01317Z"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                            <path
                                                d="M7.33281 15.18V12.4867C7.33281 11.4267 6.90614 11 5.84614 11H3.1528C2.0928 11 1.66614 11.4267 1.66614 12.4867V15.18C1.66614 16.24 2.0928 16.6667 3.1528 16.6667H5.84614C6.90614 16.6667 7.33281 16.24 7.33281 15.18Z"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </a>
                                    <a @class(['nav-link', 'active' => $layout === 'list']) id="nav-list-tab" type="button" role="tab" aria-controls="nav-list" aria-selected="false" href="{{ request()->fullUrlWithQuery(['layout' => 'list']) }}">
                                        <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15 7.11133H1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M15 1H1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M15 13.2222H1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                </nav>
                            </div>
                        </div>

                        @include(Theme::getThemeNamespace("views.partials.posts-$layout"), compact('posts'))

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="tp-blog-pagination mt-30">
                                    {{ $posts->links(Theme::getThemeNamespace('partials.pagination')) }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center tp-error-content">
                            <p>{{ __("Looks like we don't have any posts matching your search.") }}</p>
                            <a href="{{ get_blog_page_url() }}" class="tp-error-btn">{{ __('Back to Blog') }}</a>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-xl-3 col-lg-4">
                <div class="tp-sidebar-wrapper tp-sidebar-ml--24">
                    {!! dynamic_sidebar('blog_sidebar') !!}
                </div>
            </div>
        </div>
    </div>
</section>
