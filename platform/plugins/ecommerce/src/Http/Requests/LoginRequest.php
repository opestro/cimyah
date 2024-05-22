<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Rules\EmailRule;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Factory;

class LoginRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'email' => ['required', 'string', ...match (EcommerceHelper::getLoginOption()) {
                'phone' => BaseHelper::getPhoneValidationRule(true),
                'email' => [new EmailRule()],
                default => [],
            }],
            'password' => ['required', 'string'],
        ];

        return apply_filters('ecommerce_customer_login_form_validation_rules', $rules);
    }

    public function attributes(): array
    {
        return apply_filters('ecommerce_customer_login_form_validation_attributes', [
            'email' => match (EcommerceHelper::getLoginOption()) {
                'phone' => __('Phone'),
                'email_or_phone' => __('Email or Phone number'),
                default => __('Email'),
            },
            'password' => __('Password'),
        ]);
    }

    public function messages(): array
    {
        return apply_filters('ecommerce_customer_login_form_validation_messages', []);
    }

    public function isEmail($value): bool
    {
        $validator = $this->container->make(Factory::class);

        return $validator
            ->make(['email' => $value], ['email' => ['email']])
            ->passes();
    }
}
