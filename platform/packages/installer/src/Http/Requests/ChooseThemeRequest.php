<?php

namespace Botble\Installer\Http\Requests;

use Botble\Support\Http\Requests\Request;
use Botble\Theme\Facades\Manager;
use Illuminate\Validation\Rule;

class ChooseThemeRequest extends Request
{
    public function rules(): array
    {
        return [
            'theme' => ['required', 'string', Rule::in(array_keys(Manager::getThemes()))],
        ];
    }
}
