<?php

namespace Botble\Ecommerce\ValueObjects;

class CheckoutOrderData
{
    public function __construct(
        public array $shipping,
        public array $sessionCheckoutData,
        public float $shippingAmount,
        public float $rawTotal,
        public float $orderAmount,
        public float $promotionDiscountAmount,
        public float $couponDiscountAmount,
        public ?string $defaultShippingMethod = null,
        public ?string $defaultShippingOption = null,
    ) {
    }
}
