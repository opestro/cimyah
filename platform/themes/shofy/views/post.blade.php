@php
    Theme::layout('full-width');
    Theme::set('breadcrumbStyle', 'without-title');

    $relatedPosts = get_related_posts($post->getKey(), 3);
    Theme::set('pageTitle', $post->name);
@endphp

<section @class(['tp-postbox-details-area', 'pb-120' => $relatedPosts->isEmpty()])>
    <div class="container">
        <div class="row">
            <div class="col-xl-9">
                <div class="tp-postbox-details-top">
                    @if ($post->categories->isNotEmpty())
                        <div class="tp-postbox-details-category">
                            @foreach ($post->categories as $category)
                                <span>
                                    <a href="{{ $category->url }}">{{ $category->name }}</a>@if (!$loop->last), @endif
                                </span>
                            @endforeach
                        </div>
                    @endif
                    <h1 class="tp-postbox-details-title">{{ $post->name }}</h1>
                    <div class="tp-postbox-details-meta mb-50">
                        @if ($post->author)
                            <span data-meta="author">
                                <x-core::icon name="ti ti-user" />
                                {{ __('By :name', ['name' => $post->author->name]) }}
                            </span>
                        @endif
                        <span>
                            <x-core::icon name="ti ti-clock" />
                            {{ Theme::formatDate($post->created_at) }}
                        </span>
                        <span>
                            <x-core::icon name="ti ti-eye" />
                            {{ number_format($post->views) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-9 col-lg-8">
                <div class="tp-postbox-details-main-wrapper">
                    <div class="tp-postbox-details-content">
                        <div class="ck-content">{!! BaseHelper::clean($post->content) !!}</div>

                        <div class="tp-postbox-details-share-wrapper">
                            <div class="row">
                                <div class="col-xl-8 col-lg-6">
                                    <div class="tp-postbox-details-tags tagcloud">
                                        <span>{{ __('Tags:') }}</span>
                                        @foreach ($post->tags as $tag)
                                            <a href="{{ $tag->url }}">{{ $tag->name }}</a>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6">
                                    <div class="tp-postbox-details-share text-md-end">
                                        <span>{{ __('Share:') }}</span>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $post->url }}"><x-core::icon name="ti ti-brand-facebook" /></a>
                                        <a href="https://x.com/intent/tweet?text={{ $post->name }}&url={{ $post->url }}"><x-core::icon name="ti ti-brand-x" /></a>
                                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $post->url }}"><x-core::icon name="ti ti-brand-linkedin" /></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, null, $post) !!}
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4">
                <div class="tp-sidebar-wrapper tp-sidebar-ml--24">
                    {!! dynamic_sidebar('blog_sidebar') !!}
                </div>
            </div>
        </div>
    </div>

    @if ($relatedPosts->isNotEmpty())
        <div class="tp-postbox-related-area pt-115 pb-90 mt-50" style="background-color: #F4F7F9">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="tp-postbox-related">
                            <h3 class="tp-postbox-related-title">{{ __('Related Articles') }}</h3>

                            <div class="row">
                                @foreach ($relatedPosts as $relatedPost)
                                    <div class="col-md-6 col-lg-4">
                                        @include(Theme::getThemeNamespace('views.partials.post-grid-item'), ['post' => $relatedPost, 'class' => 'tp-blog-grid-style2'])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>
