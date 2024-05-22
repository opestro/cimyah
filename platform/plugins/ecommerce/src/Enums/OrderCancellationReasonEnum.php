<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Supports\Enum;

class OrderCancellationReasonEnum extends Enum
{
    public const CHANGED_MIND = 'change-mind';

    public const FOUND_BETTER_PRICE = 'found-better-price';

    public const PRODUCT_OUT_OF_STOCK = 'out-of-stock';

    public const SHIPPING_DELAYS = 'shipping-delays';

    public const INCORRECT_ADDRESS = 'incorrect-address';

    public const CUSTOMER_REQUESTED_CANCELLATION = 'customer-requested';

    public const PRODUCT_NOT_AS_DESCRIBED = 'not-as-described';

    public const PAYMENT_ISSUES_OR_DECLINED_TRANSACTION = 'payment-issues';

    public const UNFORESEEN_CIRCUMSTANCES_OR_EMERGENCIES = 'unforeseen-circumstances';

    public const TECHNICAL_ISSUES_DURING_CHECKOUT_PROCESS = 'technical-issues';

    public const OTHER = 'other';

    public static $langPath = 'plugins/ecommerce::order.cancellation_reasons';
}
