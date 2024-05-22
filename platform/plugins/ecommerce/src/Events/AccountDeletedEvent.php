<?php

namespace Botble\Ecommerce\Events;

use Botble\Base\Events\Event;
use Botble\Ecommerce\Models\Customer;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AccountDeletedEvent extends Event
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public string $email,
        public string $name,
        public Customer $customer
    ) {
    }
}
