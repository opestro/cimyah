<?php

namespace Botble\Marketplace\Http\Controllers\Fronts;

use Botble\Base\Facades\EmailHandler;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Marketplace\Http\Requests\Fronts\ContactStoreRequest;
use Botble\Marketplace\Models\Message;
use Botble\Marketplace\Models\Store;

class ContactStoreController extends BaseController
{
    public function store(string $id, ContactStoreRequest $request): BaseHttpResponse
    {
        $store = Store::query()
            ->wherePublished()
            ->findOrFail($id);

        $emailVariables = [
            'store_name' => $store->name,
            'store_phone' => $store->phone,
            'store_address' => $store->full_address,
            'store_url' => $store->url,
            'customer_message' => $request->input('content'),
        ];

        if (auth('customer')->check()) {
            $customer = auth('customer')->user();

            if ($customer->store?->id == $id) {
                return $this
                    ->httpResponse()
                    ->setError()
                    ->setMessage(__('You cannot send a message to your own store.'));
            }

            $emailVariables = [
                ...$emailVariables,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
            ];
        } else {
            $emailVariables = [
                ...$emailVariables,
                'customer_name' => $request->input('name'),
                'customer_email' => $request->input('email'),
            ];
        }

        Message::query()->create([
            'store_id' => $store->getKey(),
            'customer_id' => auth('customer')->id(),
            'name' => $emailVariables['customer_name'],
            'email' => $emailVariables['customer_email'],
            'content' => $request->input('content'),
        ]);

        EmailHandler::setModule(MARKETPLACE_MODULE_SCREEN_NAME)
            ->setVariableValues($emailVariables)
            ->sendUsingTemplate('contact-store', $store->email);

        return $this
            ->httpResponse()
            ->setMessage(__('Send message successfully!'));
    }
}
