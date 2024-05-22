<?php

namespace Botble\Marketplace\Http\Requests\Fronts;

use Botble\Support\Http\Requests\Request;

class ContactStoreRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'content' => ['required', 'string', 'max:1000'],
        ];

        if (! auth('customer')->check()) {
            $rules += [
                'name' => ['required', 'string', 'max:40'],
                'email' => ['required', 'email'],
            ];
        }

        return $rules;
    }
}
