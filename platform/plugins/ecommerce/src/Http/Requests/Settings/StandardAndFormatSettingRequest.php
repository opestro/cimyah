<?php

namespace Botble\Ecommerce\Http\Requests\Settings;

use Botble\Support\Http\Requests\Request;

class StandardAndFormatSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'store_order_prefix' => ['nullable', 'string', 'max:120'],
            'store_order_suffix' => ['nullable', 'string', 'max:120'],
            'store_weight_unit' => ['required', 'string', 'in:g,kg,lb,oz'],
            'store_width_height_unit' => ['required', 'string', 'in:cm,m,inch'],
        ];
    }
}
