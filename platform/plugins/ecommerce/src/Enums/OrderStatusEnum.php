<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static OrderStatusEnum PENDING()
 * @method static OrderStatusEnum PROCESSING()
 * @method static OrderStatusEnum COMPLETED()
 * @method static OrderStatusEnum CANCELED()
 * @method static OrderStatusEnum PARTIAL_RETURNED()
 * @method static OrderStatusEnum RETURNED()
 */
class OrderStatusEnum extends Enum
{
    public const PENDING = 'pending';

    public const PROCESSING = 'processing';

    public const COMPLETED = 'completed';

    public const CANCELED = 'canceled';

    public const PARTIAL_RETURNED = 'partial_returned';

    public const RETURNED = 'returned';

    public static $langPath = 'plugins/ecommerce::order.statuses';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::PENDING => 'warning',
            self::PROCESSING => 'info',
            self::COMPLETED => 'success',
            self::CANCELED, self::RETURNED, self::PARTIAL_RETURNED => 'danger',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color, icon: $this->getIcon());
    }

    public function getIcon(): string
    {
        return match ($this->value) {
            self::PENDING => 'ti ti-clock',
            self::PROCESSING => 'ti ti-refresh',
            self::COMPLETED => 'ti ti-circle-check',
            self::CANCELED => 'ti ti-circle-x',
            self::PARTIAL_RETURNED, self::RETURNED => 'ti ti-reload',
            default => 'ti ti-circle',
        };
    }
}
