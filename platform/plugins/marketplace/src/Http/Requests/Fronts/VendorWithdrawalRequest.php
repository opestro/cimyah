<?php

namespace Botble\Marketplace\Http\Requests\Fronts;

use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Support\Http\Requests\Request;

class VendorWithdrawalRequest extends Request
{
    public function rules(): array
    {
        $maximum = auth('customer')->user()->balance - MarketplaceHelper::getSetting('fee_withdrawal', 0);

        return [
            'amount' => ['required', 'numeric', 'min:0', "max:{$maximum}"],
            'description' => ['nullable', 'max:400'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.max' => __('The balance is not enough for withdrawal'),
        ];
    }
}
