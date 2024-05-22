<?php

namespace Botble\Ecommerce\Events;

use Botble\Base\Events\Event;
use Botble\Ecommerce\Models\CustomerDeletionRequest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AccountDeletingEvent extends Event
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public CustomerDeletionRequest $deletionRequest)
    {
    }
}
