<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Base\Rules\EmailRule;
use Botble\Ecommerce\Models\Customer;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CustomerCreateRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', new EmailRule(), Rule::unique((new Customer())->getTable(), 'email')],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'private_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
