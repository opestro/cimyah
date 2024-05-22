<div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
    <div class="tp-footer-widget footer-col-4 mb-50">
        <h4 class="tp-footer-widget-title">{{ $config['name'] }}</h4>
        <div class="tp-footer-widget-content">
            @if($config['phone'])
                <div class="tp-footer-talk mb-20">
                    <span>{{ $config['phone_label'] }}</span>
                    <h4><a href="tel:{{ $config['phone'] }}">{{ $config['phone'] }}</a></h4>
                </div>
            @endif
            <div class="tp-footer-contact">
                @if($config['email'])
                    <div class="tp-footer-contact-item d-flex align-items-start">
                        <div class="tp-footer-contact-icon">
                        <span>
                            <svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 5C1 2.2 2.6 1 5 1H13C15.4 1 17 2.2 17 5V10.6C17 13.4 15.4 14.6 13 14.6H5" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M13 5.40039L10.496 7.40039C9.672 8.05639 8.32 8.05639 7.496 7.40039L5 5.40039" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M1 11.4004H5.8" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M1 8.19922H3.4" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        </div>
                        <div class="tp-footer-contact-content">
                            <p>{!! Html::mailto($config['email']) !!}</p>
                        </div>
                    </div>
                @endif
                @if($config['address'])
                    <div class="tp-footer-contact-item d-flex align-items-start">
                        <div class="tp-footer-contact-icon">
                            <span>
                                <svg width="17" height="20" viewBox="0 0 17 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.50001 10.9417C9.99877 10.9417 11.2138 9.72668 11.2138 8.22791C11.2138 6.72915 9.99877 5.51416 8.50001 5.51416C7.00124 5.51416 5.78625 6.72915 5.78625 8.22791C5.78625 9.72668 7.00124 10.9417 8.50001 10.9417Z" stroke="currentColor" stroke-width="1.5"/>
                                    <path d="M1.21115 6.64496C2.92464 -0.887449 14.0841 -0.878751 15.7889 6.65366C16.7891 11.0722 14.0406 14.8123 11.6313 17.126C9.88298 18.8134 7.11704 18.8134 5.36006 17.126C2.95943 14.8123 0.210885 11.0635 1.21115 6.64496Z" stroke="currentColor" stroke-width="1.5"/>
                                </svg>
                            </span>
                        </div>
                        <div class="tp-footer-contact-content">
                            <p>
                                <a href="https://maps.google.com/?q={{ $config['address'] }}" target="_blank">
                                    {{ $config['address'] }}
                                </a>
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
