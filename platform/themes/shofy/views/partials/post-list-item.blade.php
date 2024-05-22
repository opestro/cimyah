<div class="tp-blog-list-item d-md-flex d-lg-block d-xl-flex">
    <div class="tp-blog-list-thumb">
        <a href="{{ $post->url }}">
            {{ RvMedia::image($post->image, $post->name, useDefaultImage: true) }}
        </a>
    </div>
    <div class="tp-blog-list-content">
        <div class="tp-blog-grid-content">
            <div class="tp-blog-grid-meta">
                <span>
                    <span>
                        <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M15 8.5C15 12.364 11.864 15.5 8 15.5C4.136 15.5 1 12.364 1 8.5C1 4.636 4.136 1.5 8 1.5C11.864 1.5 15 4.636 15 8.5Z"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <path d="M10.5972 10.7259L8.42715 9.43093C8.04915 9.20693 7.74115 8.66793 7.74115 8.22693V5.35693" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    {{ Theme::formatDate($post->created_at) }}
                </span>
                @if ($post->author)
                    <span>
                        <span>
                            <x-core::icon name="ti ti-user" />
                        </span>
                        {{ $post->author->name }}
                    </span>
                @endif
            </div>
            <h3 class="tp-blog-grid-title">
                <a href="{{ $post->url }}">{{ $post->name }}</a>
            </h3>
            <p>{{ Str::words($post->description, 30) }}</p>

            <div class="tp-blog-grid-btn">
                <a href="{{ $post->url }}" class="tp-link-btn-3">
                    {{ __('Read More') }}
                    <span>
                        <svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 7.5L1 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M9.9502 1.47541L16.0002 7.49941L9.9502 13.5244" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>
