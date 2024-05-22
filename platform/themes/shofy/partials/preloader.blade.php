<div id="loading">
    <div id="loading-center">
        <div id="loading-center-absolute">
            <div class="tp-preloader-content">
                <div class="tp-preloader-logo">
                    <div class="tp-preloader-circle">
                        <svg width="190" height="190" viewBox="0 0 380 380" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle stroke="#D9D9D9" cx="190" cy="190" r="180" stroke-width="6" stroke-linecap="round"></circle>
                            <circle stroke="red" cx="190" cy="190" r="180" stroke-width="6" stroke-linecap="round"></circle>
                        </svg>
                    </div>
                    <img src="{{ RvMedia::getImageUrl(theme_option('preloader_icon')) }}" alt="{{ theme_option('site_title') }}">
                </div>
                <h3 class="tp-preloader-title">{{ theme_option('site_title') }}</h3>
                <p class="tp-preloader-subtitle">{{ __('Loading') }}</p>
            </div>
        </div>
    </div>
</div>
