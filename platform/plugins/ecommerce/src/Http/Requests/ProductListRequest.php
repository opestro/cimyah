<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Support\Http\Requests\Request;

class ProductListRequest extends Request
{
    public function rules(): array
    {
        return [
            'product_id' => ['nullable', 'exists:ec_products,id'],
            'keyword' => ['nullable', 'string', 'max:255'],
            'include_variation' => ['nullable', 'in:0,1'],
        ];
    }
}
