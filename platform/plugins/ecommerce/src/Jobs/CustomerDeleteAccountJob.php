<?php

namespace Botble\Ecommerce\Jobs;

use Botble\Base\Facades\EmailHandler;
use Botble\Ecommerce\Events\AccountDeletedEvent;
use Botble\Ecommerce\Events\AccountDeletingEvent;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\CustomerDeletionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CustomerDeleteAccountJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public CustomerDeletionRequest $deletionRequest)
    {
    }

    public function handle(): void
    {
        $customer = Customer::query()->find($this->deletionRequest->customer_id);

        if (! $customer->exists) {
            return;
        }

        AccountDeletingEvent::dispatch($this->deletionRequest);

        $name = $customer->name;
        $email = $customer->email;

        $customer->delete();

        EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME)
            ->setVariableValue('customer_name', $name)
            ->sendUsingTemplate('customer-deletion-request-completed', $email);

        AccountDeletedEvent::dispatch($email, $name, $customer);
    }
}
