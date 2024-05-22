<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Rules\EmailRule;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Customer;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class RegisterRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'max:120', 'min:2'],
            'email' => [
                'nullable',
                Rule::requiredIf(! EcommerceHelper::isLoginUsingPhone()),
                new EmailRule(),
                Rule::unique((new Customer())->getTable()),
            ],
            'phone' => [
                'nullable',
                Rule::requiredIf(EcommerceHelper::isLoginUsingPhone()),
                ...explode('|', BaseHelper::getPhoneValidationRule()),
                Rule::unique((new Customer())->getTable(), 'phone'),
            ],
            'password' => ['required', 'min:6', 'confirmed'],
            'agree_terms_and_policy' => ['sometimes', 'accepted:1'],
        ];

        return apply_filters('ecommerce_customer_registration_form_validation_rules', $rules);
    }

    public function attributes(): array
    {
        return apply_filters('ecommerce_customer_registration_form_validation_attributes', [
            'name' => __('Name'),
            'email' => __('Email'),
            'password' => __('Password'),
            'agree_terms_and_policy' => __('Term and Policy'),
        ]);
    }

    public function messages(): array
    {
        return apply_filters('ecommerce_customer_registration_form_validation_messages', []);
    }
}
