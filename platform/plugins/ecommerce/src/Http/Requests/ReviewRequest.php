<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Base\Rules\EmailRule;
use Botble\Base\Rules\MediaImageRule;
use Botble\Support\Http\Requests\Request;

class ReviewRequest extends Request
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'images' => array_filter($this->input('images', []) ?? []),
        ]);
    }

    public function rules(): array
    {
        return [
            'created_at' => ['required', 'date'],
            'product_id' => ['required', 'exists:ec_products,id'],
            'customer_id' => ['nullable', 'exists:ec_customers,id'],
            'customer_name' => ['nullable', 'string', 'max:100'],
            'customer_email' => ['nullable', new EmailRule(), 'max:50'],
            'star' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:5000'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', new MediaImageRule()],
        ];
    }
}
