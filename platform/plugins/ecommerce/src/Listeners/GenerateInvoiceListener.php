<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Ecommerce\Events\OrderCreated;
use Botble\Ecommerce\Events\OrderPlacedEvent;
use Botble\Ecommerce\Facades\InvoiceHelper;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateInvoiceListener implements ShouldQueue
{
    public function handle(OrderPlacedEvent|OrderCreated $event): void
    {
        $order = $event->order;

        InvoiceHelper::store($order);
    }
}
