<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Support\Http\Requests\Request;

class UpdateProductPriceRequest extends Request
{
    public function rules(): array
    {
        return [
            'column' => ['required', 'in:cost_per_item,price,sale_price'],
            'value' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
