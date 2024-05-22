<div class="tp-coupon-area pb-120">
    <div class="container">
        <div class="row g-3">
            @foreach ($coupons as $coupon)
                <div class="col-xl-4">
                    <div class="tp-coupon-item p-relative">
                        <div class="tp-coupon-item-left d-sm-flex align-items-center">
                            <div class="tp-coupon-thumb">
                                <x-core::icon name="ti ti-discount-2" />
                            </div>
                            <div class="tp-coupon-content">
                                <div class="tp-coupon-status mb-10 d-flex align-items-center">
                                    <h4>
                                        {{ __('Coupon') }}
                                        <span class="text-danger">{{ $coupon->type_option === 'percentage' ? $coupon->value . '%' : format_price($coupon->value) }}</span> - <span @class(['active' => ! $coupon->isExpired()])>{{ $coupon->isExpired() ? __('Expired') : __('Active') }}</span>
                                    </h4>
                                    <div class="tp-coupon-info-details">
                                        <span>
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    fill-rule="evenodd"
                                                    clip-rule="evenodd"
                                                    d="M9 1.5C4.99594 1.5 1.75 4.74594 1.75 8.75C1.75 12.7541 4.99594 16 9 16C13.0041 16 16.25 12.7541 16.25 8.75C16.25 4.74594 13.0041 1.5 9 1.5ZM0.25 8.75C0.25 3.91751 4.16751 0 9 0C13.8325 0 17.75 3.91751 17.75 8.75C17.75 13.5825 13.8325 17.5 9 17.5C4.16751 17.5 0.25 13.5825 0.25 8.75ZM9 7.75C9.55229 7.75 10 8.19771 10 8.75V11.95C10 12.5023 9.55229 12.95 9 12.95C8.44771 12.95 8 12.5023 8 11.95V8.75C8 8.19771 8.44771 7.75 9 7.75ZM9 4.5498C8.44771 4.5498 8 4.99752 8 5.5498C8 6.10209 8.44771 6.5498 9 6.5498H9.008C9.56028 6.5498 10.008 6.10209 10.008 5.5498C10.008 4.99752 9.56028 4.5498 9.008 4.5498H9Z"
                                                    fill="currentColor"
                                                ></path>
                                            </svg>
                                        </span>
                                        <div class="tp-coupon-info-tooltip transition-3">
                                            <div>{!! BaseHelper::clean($coupon->description ?: get_discount_description($coupon)) !!}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tp-coupon-date">
                                    <button data-bb-toggle="copy-coupon" data-value="{{ $coupon->code }}" data-copied-message="{{ __('Copied!') }}"><span>{{ $coupon->code }}</span></button>
                                </div>
                                @if ($coupon->end_date && ! $coupon->isExpired())
                                    <div class="tp-coupon-countdown" data-countdown="" data-date="{{ $coupon->end_date }}">
                                        <div class="tp-coupon-countdown-inner">
                                            <ul>
                                                <li><span data-days="">0</span> Day</li>
                                                <li><span data-hours="">0</span> Hrs</li>
                                                <li><span data-minutes="">0</span> Min</li>
                                                <li><span data-seconds="">0</span> Sec</li>
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
