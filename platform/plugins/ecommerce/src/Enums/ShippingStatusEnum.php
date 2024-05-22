<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static ShippingStatusEnum NOT_APPROVED()
 * @method static ShippingStatusEnum APPROVED()
 * @method static ShippingStatusEnum ARRANGE_SHIPMENT()
 * @method static ShippingStatusEnum READY_TO_BE_SHIPPED_OUT()
 * @method static ShippingStatusEnum PICKING()
 * @method static ShippingStatusEnum PENDING()
 * @method static ShippingStatusEnum DELAY_PICKING()
 * @method static ShippingStatusEnum PICKED()
 * @method static ShippingStatusEnum NOT_PICKED()
 * @method static ShippingStatusEnum DELIVERING()
 * @method static ShippingStatusEnum DELIVERED()
 * @method static ShippingStatusEnum NOT_DELIVERED()
 * @method static ShippingStatusEnum AUDITED()
 * @method static ShippingStatusEnum CANCELED()
 */
class ShippingStatusEnum extends Enum
{
    public const NOT_APPROVED = 'not_approved';

    public const APPROVED = 'approved';

    public const PENDING = 'pending';

    public const ARRANGE_SHIPMENT = 'arrange_shipment';

    public const READY_TO_BE_SHIPPED_OUT = 'ready_to_be_shipped_out';

    public const PICKING = 'picking';

    public const DELAY_PICKING = 'delay_picking';

    public const PICKED = 'picked';

    public const NOT_PICKED = 'not_picked';

    public const DELIVERING = 'delivering';

    public const DELIVERED = 'delivered';

    public const NOT_DELIVERED = 'not_delivered';

    public const AUDITED = 'audited';

    public const CANCELED = 'canceled';

    public static $langPath = 'plugins/ecommerce::shipping.statuses';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::NOT_APPROVED, self::PENDING, self::DELAY_PICKING, self::APPROVED => 'warning',
            self::PICKING, self::READY_TO_BE_SHIPPED_OUT, self::DELIVERING, self::ARRANGE_SHIPMENT => 'info',
            self::NOT_PICKED, self::CANCELED, self::NOT_DELIVERED => 'danger',
            self::DELIVERED, self::AUDITED => 'success',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
