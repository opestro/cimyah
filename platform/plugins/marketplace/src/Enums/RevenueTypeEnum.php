<?php

namespace Botble\Marketplace\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static RevenueTypeEnum ADD_AMOUNT()
 * @method static RevenueTypeEnum SUBTRACT_AMOUNT()
 * @method static RevenueTypeEnum ORDER_RETURN()
 */
class RevenueTypeEnum extends Enum
{
    public const ADD_AMOUNT = 'add-amount';

    public const SUBTRACT_AMOUNT = 'subtract-amount';

    public const ORDER_RETURN = 'order-return';

    public static $langPath = 'plugins/marketplace::revenue.types';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::ADD_AMOUNT => 'info',
            self::SUBTRACT_AMOUNT => 'danger',
            self::ORDER_RETURN => 'warning',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }

    public static function adjustValues(): array
    {
        return [
            self::ADD_AMOUNT,
            self::SUBTRACT_AMOUNT,
        ];
    }

    public static function adjustLabels(): array
    {
        $result = [];

        foreach (static::adjustValues() as $value) {
            $result[$value] = static::getLabel($value);
        }

        return $result;
    }
}
