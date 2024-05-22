<?php

namespace Botble\Ecommerce\Http\Requests\Fronts\Auth;

use Botble\Base\Rules\EmailRule;
use Botble\Support\Http\Requests\Request;

class ForgotPasswordRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => ['required', new EmailRule()],
        ];
    }
}
