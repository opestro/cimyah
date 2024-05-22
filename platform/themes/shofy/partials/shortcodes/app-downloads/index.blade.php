<section class="tp-cta-area fix pt-50 p-relative z-index-1">
    <div class="tp-cta-inner p-relative pt-80 pb-55">
        @php
            $shapeLeft = $shortcode->shape_image_left;
            $shapeRight = $shortcode->shape_image_right;
        @endphp

        @if($shapeLeft || $shapeRight)
            <div class="tp-cta-shape">
                @if($shapeLeft)
                    {{ RvMedia::image($shapeLeft, 'shape-left', attributes: ['class' => 'tp-cta-shape-1']) }}
                @endif
                @if($shapeRight)
                    {{ RvMedia::image($shapeRight, 'shape-right', attributes: ['class' => 'tp-cta-shape-2']) }}
                @endif
            </div>
        @endif

        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-7">
                    <div class="tp-cta-wrapper p-relative z-index-1">
                        @if($title = $shortcode->title)
                            <h3 class="tp-cta-title">
                                {!! BaseHelper::clean($title) !!}
                            </h3>
                        @endif
                        <div class="tp-cta-btn-wrapper d-flex flex-wrap">
                            @if(($googleLabel = $shortcode->google_label) && ($googleUrl = $shortcode->google_url))
                                <div class="tp-app-btn mb-30">
                                    <a href="{{ $googleUrl }}" class="d-flex align-items-center google-btn">
                                        <div class="app-icon mr-10">
                                        <span>
                                            @if(($icon = $shortcode->google_icon) && BaseHelper::hasIcon($icon))
                                                {!! BaseHelper::renderIcon($icon) !!}
                                            @else
                                                <svg width="22" height="24" viewBox="0 0 22 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M16.0244 7.91774L13.4801 10.6447L3.16459 0.187162C6.21506 2.0179 11.0875 4.95113 11.0875 4.95113L16.0244 7.91774ZM21.6008 11.2727C21.2282 11.0666 18.6192 9.48973 17.2574 8.66061L14.4692 11.6465L17.5887 14.8096C18.9737 13.9756 21.1591 12.6626 21.5641 12.4181C22.1667 12.0538 22.1116 11.5603 21.6008 11.2727ZM13.5076 12.6768L3.72583 23.1488C6.83156 21.2797 11.0875 18.7205 11.0875 18.7205L16.3464 15.5574L13.5076 12.6768ZM1.20276 0.210599C0.753662 -0.244648 0 0.0868497 0 0.739721V23.2604C0 23.9228 0.772922 24.25 1.21644 23.7751L12.5176 11.6765L1.20276 0.210599Z"
                                                    fill="currentColor"
                                                />
                                            </svg>
                                            @endif
                                        </span>
                                        </div>
                                        <div class="app-content">
                                            <span>{{ __('Get it on') }}</span>
                                            <p>{{ $googleLabel }}</p>
                                        </div>
                                    </a>
                                </div>
                            @endif

                            @if(($appleLabel = $shortcode->apple_label) && ($appleUrl = $shortcode->apple_url))
                                <div class="tp-app-btn mb-30">
                                    <a href="{{ $appleUrl }}" class="d-flex align-items-center apple-btn">
                                        <div class="app-icon mr-10">
                                            <span>
                                                @if(($icon = $shortcode->apple_icon) && BaseHelper::hasIcon($icon))
                                                    {!! BaseHelper::renderIcon($icon) !!}
                                                @else
                                                    <svg width="20" height="24" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            fill-rule="evenodd"
                                                            clip-rule="evenodd"
                                                            d="M18.4945 20.5917C19.1995 19.5117 19.4623 18.9598 20 17.7478C16.0332 16.2358 15.4 10.5599 19.319 8.38794C18.1241 6.87597 16.4394 6 14.8503 6C13.7033 6 12.9147 6.30001 12.2098 6.576C11.6124 6.804 11.0747 7.00798 10.4056 7.00798C9.68874 7.00798 9.05548 6.78 8.38638 6.54C7.65754 6.27601 6.89286 6 5.93701 6C4.15673 6 2.25698 7.09198 1.05021 8.96394C-0.646428 11.6039 -0.347721 16.5478 2.38841 20.7717C3.36816 22.2837 4.68245 23.9756 6.39104 23.9996C7.10793 24.0116 7.57391 23.7957 8.08768 23.5677C8.67314 23.3037 9.30639 23.0157 10.4176 23.0157C11.5287 23.0037 12.1501 23.3037 12.7355 23.5677C13.2373 23.7957 13.6914 24.0116 14.3963 23.9996C16.1288 23.9756 17.5148 22.1037 18.4945 20.5917Z"
                                                            fill="currentColor"
                                                        ></path>
                                                        <path
                                                            fill-rule="evenodd"
                                                            clip-rule="evenodd"
                                                            d="M14.6006 0C14.7918 1.31997 14.2541 2.62796 13.5492 3.53994C12.7964 4.52393 11.4821 5.29189 10.2156 5.24389C9.98862 3.97192 10.5741 2.66394 11.291 1.78795C12.0915 0.827972 13.4416 0.0839983 14.6006 0Z"
                                                            fill="currentColor"
                                                        ></path>
                                                    </svg>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="app-content">
                                            <span>{{ __('Get it on') }}</span>
                                            <p>{{ $appleLabel }}</p>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @if($screenshot = $shortcode->screenshot)
                    <div class="col-lg-6">
                        <div class="tp-cta-thumb">
                            <span class="tp-cta-thumb-mobile"></span>
                            {{ RvMedia::image($screenshot, 'mobile-screenshot', attributes: ['loading' => false]) }}
                        </div>
                        <span class="tp-cta-thumb-gradient"></span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
