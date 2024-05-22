<?php

namespace Botble\Payment\Http\Requests\Settings;

use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PaymentMethodSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'default_payment_method' => ['required', Rule::in(PaymentMethodEnum::values())],
        ];
    }
}
