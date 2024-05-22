<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Http\Requests\UpdatePrimaryStoreRequest;
use Botble\Ecommerce\Models\StoreLocator;

class ChangePrimaryStoreController extends BaseController
{
    public function __invoke(UpdatePrimaryStoreRequest $request)
    {
        $storeLocator = StoreLocator::query()->findOrFail($request->input('primary_store_id'));

        StoreLocator::query()->where('id', '!=', $storeLocator->getKey())->update(['is_primary' => false]);

        $storeLocator->update([
            'is_primary' => true,
        ]);

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage();
    }
}
