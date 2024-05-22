<?php

namespace Botble\Ecommerce\Http\Requests\Fronts;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Rules\EmailRule;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Support\Http\Requests\Request;

class OrderTrackingRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'order_id' => ['nullable', 'string', 'min:1'],
            'email' => ['nullable', new EmailRule()],
        ];

        if (EcommerceHelper::isLoginUsingPhone()) {
            $rules['phone'] = ['nullable', ...BaseHelper::getPhoneValidationRule(true)];
        }

        return $rules;
    }
}
