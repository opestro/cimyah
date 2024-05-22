<?php

namespace Botble\Marketplace\Http\Requests\Fronts;

use Botble\Base\Rules\MediaImageRule;
use Botble\Marketplace\Http\Requests\StoreRequest;

class VendorStoreRequest extends StoreRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        unset($rules['customer_id'], $rules['status']);

        $rules['logo_input'] = ['nullable', new MediaImageRule()];
        $rules['cover_image_input'] = ['nullable', new MediaImageRule()];

        return $rules;
    }
}
