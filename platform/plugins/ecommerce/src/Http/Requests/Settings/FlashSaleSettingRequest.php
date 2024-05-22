<?php

namespace Botble\Ecommerce\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class FlashSaleSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'flash_sale_enabled' => [new OnOffRule()],
        ];
    }
}
