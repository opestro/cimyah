<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static DiscountTypeOptionEnum AMOUNT()
 * @method static DiscountTypeOptionEnum PERCENTAGE()
 * @method static DiscountTypeOptionEnum SHIPPING()
 * @method static DiscountTypeOptionEnum SAME_PRICE()
 */
class DiscountTypeOptionEnum extends Enum
{
    public const AMOUNT = 'amount';

    public const PERCENTAGE = 'percentage';

    public const SHIPPING = 'shipping';

    public const SAME_PRICE = 'same-price';

    public static $langPath = 'plugins/ecommerce::discount.enums.type-options';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::AMOUNT, self::PERCENTAGE, self::SHIPPING, self::SAME_PRICE => 'info',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
