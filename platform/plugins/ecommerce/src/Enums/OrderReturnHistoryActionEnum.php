<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Supports\Enum;

class OrderReturnHistoryActionEnum extends Enum
{
    public const CREATED = 'created';

    public const APPROVED = 'approved';

    public const REJECTED = 'rejected';

    public const MARK_AS_COMPLETED = 'mark_as_completed';

    protected static $langPath = 'plugins/ecommerce::order.order_return_action';
}
