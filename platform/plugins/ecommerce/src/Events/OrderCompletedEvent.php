<?php

namespace Botble\Ecommerce\Events;

use Botble\Base\Events\Event;
use Botble\Ecommerce\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCompletedEvent extends Event
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Order $order)
    {
    }
}
