<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Supports\Enum;

class OrderHistoryActionEnum extends Enum
{
    public const CANCEL_ORDER = 'cancel_order';

    public const CANCEL_SHIPMENT = 'cancel_shipment';

    public const CONFIRM_ORDER = 'confirm_order';

    public const CONFIRM_PAYMENT = 'confirm_payment';

    public const CREATE_ORDER = 'create_order';

    public const CREATE_ORDER_FROM_ADMIN_PAGE = 'create_order_from_admin_page';

    public const CREATE_ORDER_FROM_PAYMENT_PAGE = 'create_order_from_payment_page';

    public const CREATE_ORDER_FROM_SEEDER = 'create_order_from_seeder';

    public const CREATE_SHIPMENT = 'create_shipment';

    public const MARK_ORDER_AS_COMPLETED = 'mark_order_as_completed';

    public const REFUND = 'refund';

    public const RETURN_ORDER = 'return_order';

    public const SEND_ORDER_CONFIRMATION_EMAIL = 'send_order_confirmation_email';

    public const UPDATE_COD_STATUS = 'update_cod_status';

    public const UPDATE_SHIPPING_STATUS = 'update_shipping_status';

    public const UPDATE_STATUS = 'update_status';
}
