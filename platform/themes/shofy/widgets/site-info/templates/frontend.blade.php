<div class="col-xl-4 col-lg-3 col-md-4 col-sm-6">
    <div class="tp-footer-widget footer-col-1 mb-50">
        <div class="tp-footer-widget-content">
            <div class="tp-footer-logo">
                <a href="{{ BaseHelper::getHomepageUrl() }}">
                    {{ RvMedia::image($config['logo'] ?: theme_option('logo'), theme_option('site_title'), attributes: $attributes) }}
                </a>
            </div>
            <p class="tp-footer-desc">{{ $config['about'] }}</p>
            @if($config['show_social_links'] && $socialLinks = Theme::getSocialLinks())
                <div class="tp-footer-social">
                    @foreach($socialLinks as $socialLink)
                        @continue(! $socialLink->getUrl() || ! $socialLink->getIconHtml())

                        <a {!! $socialLink->getAttributes() !!}>{{ $socialLink->getIconHtml() }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
