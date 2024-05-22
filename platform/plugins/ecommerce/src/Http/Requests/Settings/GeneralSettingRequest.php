<?php

namespace Botble\Ecommerce\Http\Requests\Settings;

use Botble\Base\Facades\BaseHelper;
use Botble\Support\Http\Requests\Request;

class GeneralSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'store_name' => ['required', 'string', 'max:120'],
            'store_company' => ['nullable', 'string', 'max:120'],
            'store_phone' => 'nullable|' . BaseHelper::getPhoneValidationRule(),
            'store_email' => ['nullable', 'email'],
            'store_address' => ['nullable', 'string', 'max:255'],
            'store_country' => ['nullable', 'string', 'max:120'],
            'store_state' => ['nullable', 'string', 'max:120'],
            'store_city' => ['nullable', 'string', 'max:120'],
            'store_vat_number' => ['nullable', 'string', 'max:120'],
            'store_zip_code' => ['nullable', 'string', 'min:5', 'max:9'],
        ];
    }
}
