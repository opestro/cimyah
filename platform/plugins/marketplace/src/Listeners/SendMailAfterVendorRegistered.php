<?php

namespace Botble\Marketplace\Listeners;

use Botble\Base\Facades\EmailHandler;
use Botble\Ecommerce\Models\Customer;
use Illuminate\Auth\Events\Registered;

class SendMailAfterVendorRegistered
{
    public function handle(Registered $event)
    {
        $customer = $event->user;

        if (! $customer instanceof Customer || ! $customer->is_vendor || ! $customer->store) {
            return;
        }

        $store = $customer->store;

        EmailHandler::setModule(MARKETPLACE_MODULE_SCREEN_NAME)
            ->setVariableValues([
                'vendor_name' => $customer->name,
                'shop_name' => $store->name,
            ])
            ->sendUsingTemplate('welcome-vendor', $customer->email);
    }
}
