@if ($config['image'])
    <div class="col-md-6">
        <div class="tp-footer-payment text-md-end">
            <p>
                @if($url = $config['url'])
                    <a href="{{ $url }}">
                        <img src="{{ RvMedia::getImageUrl($config['image']) }}" alt="{{ theme_option('site_title') }}">
                    </a>
                @else
                    <img src="{{ RvMedia::getImageUrl($config['image']) }}" alt="{{ theme_option('site_title') }}">
                @endif
            </p>
        </div>
    </div>
@endif
