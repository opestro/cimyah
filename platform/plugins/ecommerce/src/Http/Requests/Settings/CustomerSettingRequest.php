<?php

namespace Botble\Ecommerce\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class CustomerSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'verify_customer_email' => [$onOffRule = new OnOffRule()],
            'enabled_customer_account_deletion' => [$onOffRule],
            'login_using_phone' => [$onOffRule],
            'enabled_customer_dob_field' => [$onOffRule],
            'login_option' => ['required', 'string', 'in:email,phone,email_or_phone'],
        ];
    }
}
