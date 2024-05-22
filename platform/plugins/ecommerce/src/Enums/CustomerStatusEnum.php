<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static CustomerStatusEnum ACTIVATED()
 * @method static CustomerStatusEnum LOCKED()
 */
class CustomerStatusEnum extends Enum
{
    public const ACTIVATED = 'activated';

    public const LOCKED = 'locked';

    public static $langPath = 'plugins/ecommerce::customer.statuses';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::ACTIVATED => 'info',
            self::LOCKED => 'warning',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
