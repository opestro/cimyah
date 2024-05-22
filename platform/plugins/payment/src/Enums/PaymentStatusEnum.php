<?php

namespace Botble\Payment\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static PaymentStatusEnum PENDING()
 * @method static PaymentStatusEnum COMPLETED()
 * @method static PaymentStatusEnum REFUNDING()
 * @method static PaymentStatusEnum REFUNDED()
 * @method static PaymentStatusEnum FRAUD()
 * @method static PaymentStatusEnum FAILED()
 */
class PaymentStatusEnum extends Enum
{
    public const PENDING = 'pending';

    public const COMPLETED = 'completed';

    public const REFUNDING = 'refunding';

    public const REFUNDED = 'refunded';

    public const FRAUD = 'fraud';

    public const FAILED = 'failed';

    public static $langPath = 'plugins/payment::payment.statuses';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::PENDING, self::REFUNDING => 'warning',
            self::COMPLETED => 'success',
            self::REFUNDED => 'info',
            self::FRAUD, self::FAILED => 'danger',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
