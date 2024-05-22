<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ProductCategoryRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:250'],
            'description' => ['nullable', 'string', 'max:100000'],
            'image' => ['nullable', 'string', 'max:255'],
            'parent_id' => [
                'nullable',
                Rule::when($this->input('parent_id'), function () {
                    return Rule::exists('ec_product_categories', 'id');
                }),
            ],
            'order' => ['nullable', 'integer', 'min:0', 'max:127'],
            'icon' => ['nullable', 'string', 'max:50'],
            'icon_image' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['sometimes', 'boolean'],
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
