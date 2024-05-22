<div @class(['tp-blog-grid-item p-relative mb-30', $class ?? null])>
    <div class="tp-blog-grid-thumb w-img fix mb-30">
        <a href="{{ $post->url }}">
            {{ RvMedia::image($post->image, $post->name, useDefaultImage: true) }}
        </a>
    </div>
    <div class="tp-blog-grid-content">
        <div class="tp-blog-grid-meta">
            <span>
                <span>
                    <x-core::icon name="ti ti-clock" />
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
        <h3 class="tp-blog-grid-title text-truncate">
            <a href="{{ $post->url }}" title="{{ $post->name }}">{{ $post->name }}</a>
        </h3>
        <p>{{ Str::words($post->description, 16) }}</p>

        <div class="tp-blog-grid-btn">
            <a href="{{ $post->url }}" class="tp-link-btn-3">
                {{ __('Read More') }}
                <span>
                    <svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 7.5L1 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M9.9502 1.47541L16.0002 7.49941L9.9502 13.5244" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
            </a>
        </div>
    </div>
</div>
