<?php

namespace Botble\Ecommerce\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class TrackingSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'facebook_pixel_enabled' => $onOffRule = new OnOffRule(),
            'facebook_pixel_id' => ['nullable', 'required_if:facebook_pixel_enabled,1', 'string', 'max:120'],
            'google_tag_manager_enabled' => $onOffRule,
        ];
    }
}
