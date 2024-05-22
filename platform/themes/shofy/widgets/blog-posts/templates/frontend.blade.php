@if ($posts->isNotEmpty())
    <div class="tp-sidebar-widget mb-35">
        @if ($config['name'])
            <h3 class="tp-sidebar-widget-title">{{ $config['name'] }}</h3>
        @endif
        <div class="tp-sidebar-widget-content">
            <div class="tp-sidebar-blog-item-wrapper">
                @foreach ($posts as $post)
                    <div class="tp-sidebar-blog-item d-flex align-items-center">
                        <div class="tp-sidebar-blog-thumb">
                            <a href="{{ $post->url }}">
                                {{ RvMedia::image($post->image, $post->name, 'thumb', useDefaultImage: true) }}
                            </a>
                        </div>
                        <div class="tp-sidebar-blog-content">
                            <div class="tp-sidebar-blog-meta">
                                <span>{{ Theme::formatDate($post->created_at) }}</span>
                            </div>
                            <h3 class="tp-sidebar-blog-title">
                                <a href="{{ $post->url }}">{!! BaseHelper::clean($post->name) !!}</a>
                            </h3>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
