<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ProductAttributeSetsRequest extends Request
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:400'],
            'order' => ['required', 'integer', 'min:0', 'max:127'],
            'status' => Rule::in(BaseStatusEnum::values()),
            'attributes' => ['nullable', 'string'],
            'deleted_attributes' => ['nullable', 'string'],
            'display_layout' => ['required', 'string', 'in:dropdown,text,visual'],
            'categories' => ['nullable', 'array'],
        ];
    }
}
