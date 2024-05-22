<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Illuminate\Support\HtmlString;

/**
 * @method static ProductTypeEnum PHYSICAL()
 * @method static ProductTypeEnum DIGITAL()
 */
class ProductTypeEnum extends Enum
{
    public const PHYSICAL = 'physical';

    public const DIGITAL = 'digital';

    public static $langPath = 'plugins/ecommerce::products.types';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::PHYSICAL => 'info',
            self::DIGITAL => 'primary',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }

    public function toIcon(): string
    {
        if (! EcommerceHelper::isEnabledSupportDigitalProducts()) {
            return '';
        }

        $icon = match ($this->value) {
            self::PHYSICAL => 'ti ti-package',
            self::DIGITAL => 'ti ti-book-download',
            default => 'ti ti-camera',
        };

        return BaseHelper::renderIcon($icon);
    }
}
