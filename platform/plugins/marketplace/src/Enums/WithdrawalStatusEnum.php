<?php

namespace Botble\Marketplace\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static WithdrawalStatusEnum PENDING()
 * @method static WithdrawalStatusEnum PROCESSING()
 * @method static WithdrawalStatusEnum COMPLETED()
 * @method static WithdrawalStatusEnum CANCELED()
 * @method static WithdrawalStatusEnum REFUSED()
 */
class WithdrawalStatusEnum extends Enum
{
    public const PENDING = 'pending';

    public const PROCESSING = 'processing';

    public const COMPLETED = 'completed';

    public const CANCELED = 'canceled';

    public const REFUSED = 'refused';

    public static $langPath = 'plugins/marketplace::withdrawal.statuses';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::PENDING => 'warning',
            self::PROCESSING => 'info',
            self::COMPLETED => 'success',
            self::CANCELED => 'danger',
            self::REFUSED => 'secondary',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
