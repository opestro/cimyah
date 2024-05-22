<?php

namespace Botble\Location\Http\Requests;

use Botble\Location\Facades\Location;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ImportLocationRequest extends Request
{
    public function rules(): array
    {
        return [
            'country_code' => ['required', 'string', Rule::in(array_keys(Location::getAvailableCountries()))],
            'continue' => ['required', 'boolean'],
        ];
    }
}
