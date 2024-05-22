<div @class(['discount', 'is-discount-disabled' => $item->isExpired()])>
    @if ($item->isExpired())
        <span class="discount-expired show">{{ trans('plugins/ecommerce::discount.expired') }}</span>
    @endif
    <div class="discount-inner">
        <p class="discount-code">
            @if ($isCoupon)
                <span class="text-uppercase">{{ trans('plugins/ecommerce::discount.coupon_code') }}</span>:
                <b>{{ $item->code }}</b>
            @else
                <span class="text-uppercase">{{ trans('plugins/ecommerce::discount.discount_promotion') }}</span>:
                {{ $item->title }}
            @endif
        </p>
        <p class="discount-desc">
            {!! BaseHelper::clean(get_discount_description($item)) !!}
        </p>
        @if ($isCoupon)
            <p @class(['discount-desc' => $item->isExpired(), 'discount-text-color' => ! $item->isExpired()])>
                ({{ trans('plugins/ecommerce::discount.coupon_code') }}
                <strong>
                    @if ($item->can_use_with_promotion)
                        {{ trans('plugins/ecommerce::discount.can') }}
                    @else
                        {{ trans('plugins/ecommerce::discount.cannot') }}
                    @endif
                </strong>
                {{ trans('plugins/ecommerce::discount.use_with_promotion') }}).
            </p>
        @endif
    </div>
</div>
