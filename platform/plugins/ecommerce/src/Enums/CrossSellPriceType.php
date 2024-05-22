<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Supports\Enum;

class CrossSellPriceType extends Enum
{
    public const FIXED = 'fixed';

    public const PERCENT = 'percent';

    protected static $langPath = 'plugins/ecommerce::products.cross_sell_price_type';
}
