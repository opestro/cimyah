<div class="tp-sidebar-widget mb-35">
    @if ($config['name'])
        <h3 class="tp-sidebar-widget-title">{!! BaseHelper::clean($config['name']) !!}</h3>
    @endif
    <div class="tp-sidebar-widget-content">
        <div class="tp-sidebar-about">
            @if ($config['author_avatar'])
                <div class="tp-sidebar-about-thumb mb-25">
                    @if ($config['author_url'])
                        <a href="{{ $config['author_url'] }}">
                            {{ RvMedia::image($config['author_avatar'], $config['author_name']) }}
                        </a>
                    @else
                        {{ RvMedia::image($config['author_avatar'], $config['author_name']) }}
                    @endif
                </div>
            @endif
            <div class="tp-sidebar-about-content">
                <h3 class="tp-sidebar-about-title">
                    @if ($config['author_url'])
                        <a href="{{ $config['author_url'] }}">{{ $config['author_name'] }}</a>
                    @else
                        {{ $config['author_name'] }}
                    @endif
                </h3>
                @if ($config['author_role'])
                    <span class="tp-sidebar-about-designation">{{ $config['author_role'] }}</span>
                @endif
                @if ($config['author_description'])
                    <p>{{ $config['author_description'] }}</p>
                @endif
                @if ($config['author_signature'])
                    <div class="tp-sidebar-about-signature">
                        {{ RvMedia::image($config['author_signature'], $config['author_name']) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
