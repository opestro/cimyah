<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Base\Rules\EmailRule;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Customer;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class AddCustomerWhenCreateOrderRequest extends Request
{
    public function rules(): array
    {
        if (! EcommerceHelper::isUsingInMultipleCountries()) {
            $this->merge(['country' => EcommerceHelper::getFirstCountryId()]);
        }

        $rules = EcommerceHelper::getCustomerAddressValidationRules();
        $rules['email'] = ['required', new EmailRule(), Rule::unique((new Customer())->getTable(), 'email')];

        return $rules;
    }
}
