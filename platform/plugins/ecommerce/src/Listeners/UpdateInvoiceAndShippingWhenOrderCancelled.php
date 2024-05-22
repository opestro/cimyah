<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Ecommerce\Enums\InvoiceStatusEnum;
use Botble\Ecommerce\Enums\ShippingStatusEnum;
use Botble\Ecommerce\Events\OrderCancelledEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateInvoiceAndShippingWhenOrderCancelled implements ShouldQueue
{
    public function handle(OrderCancelledEvent $event): void
    {
        $event->order->invoice()->update(['status' => InvoiceStatusEnum::CANCELED]);
        $event->order->shipment()->update(['status' => ShippingStatusEnum::CANCELED]);
    }
}
