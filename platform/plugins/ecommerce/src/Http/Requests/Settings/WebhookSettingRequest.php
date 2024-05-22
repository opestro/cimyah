<?php

namespace Botble\Ecommerce\Http\Requests\Settings;

use Botble\Support\Http\Requests\Request;

class WebhookSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'order_placed_webhook_url' => ['nullable', 'url'],
        ];
    }
}
