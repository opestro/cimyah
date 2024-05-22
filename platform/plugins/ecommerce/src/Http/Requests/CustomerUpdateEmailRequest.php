<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Base\Rules\EmailRule;
use Botble\Ecommerce\Models\Customer;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CustomerUpdateEmailRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => ['required', new EmailRule(), Rule::unique((new Customer())->getTable(), 'email')->ignore($this->route('id'))],
        ];
    }
}
