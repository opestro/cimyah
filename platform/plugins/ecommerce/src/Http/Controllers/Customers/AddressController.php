<?php

namespace Botble\Ecommerce\Http\Controllers\Customers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Http\Requests\CreateAddressFromAdminRequest;
use Botble\Ecommerce\Models\Address;

class AddressController extends BaseController
{
    public function store(CreateAddressFromAdminRequest $request)
    {
        if ($request->boolean('is_default')) {
            Address::query()
                ->where([
                    'is_default' => 1,
                    'customer_id' => $request->input('customer_id'),
                ])
                ->update([
                    'is_default' => 0,
                ]);
        }

        $request->merge([
            'customer_id' => $request->input('customer_id'),
            'is_default' => $request->input('is_default', 0),
        ]);

        Address::query()->create($request->input());

        return $this
            ->httpResponse()
            ->setNextUrl(route('customer.address'))
            ->withCreatedSuccessMessage();
    }

    public function update(int|string $id, CreateAddressFromAdminRequest $request)
    {
        $address = Address::query()->findOrFail($id);

        if ($request->boolean('is_default')) {
            Address::query()
                ->where([
                    'is_default' => 1,
                    'customer_id' => $request->input('customer_id'),
                ])
                ->update([
                    'is_default' => 0,
                ]);
        }

        $request->merge([
            'customer_id' => $request->input('customer_id'),
            'is_default' => $request->input('is_default', 0),
        ]);

        $address->fill($request->input());

        $address->save();

        return $this
            ->httpResponse()
            ->setNextUrl(route('customer.address'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(int|string $id)
    {
        $address = Address::query()->findOrFail($id);

        $address->delete();

        return $this
            ->httpResponse()
            ->setNextUrl(route('customer.address'))
            ->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function edit(int|string $id)
    {
        $address = Address::query()->findOrFail($id);

        return view('plugins/ecommerce::customers.addresses.form-edit', compact('address'))->render();
    }
}
