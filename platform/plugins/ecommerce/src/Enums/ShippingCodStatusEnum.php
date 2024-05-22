<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static ShippingCodStatusEnum PENDING()
 * @method static ShippingCodStatusEnum COMPLETED()
 */
class ShippingCodStatusEnum extends Enum
{
    public const PENDING = 'pending';

    public const COMPLETED = 'completed';

    public static $langPath = 'plugins/ecommerce::shipping.cod_statuses';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::PENDING => 'warning',
            self::COMPLETED => 'success',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
