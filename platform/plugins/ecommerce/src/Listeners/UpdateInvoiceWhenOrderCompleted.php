<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Ecommerce\Enums\InvoiceStatusEnum;
use Botble\Ecommerce\Events\OrderCompletedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateInvoiceWhenOrderCompleted implements ShouldQueue
{
    public function handle(OrderCompletedEvent $event): void
    {
        $event->order->invoice()->update(['status' => InvoiceStatusEnum::COMPLETED]);
    }
}
