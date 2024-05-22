<?php

namespace Botble\SimpleSlider\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class SimpleSliderSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'simple_slider_using_assets' => new OnOffRule(),
        ];
    }
}
