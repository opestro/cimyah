<?php

namespace Botble\Ecommerce\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class TaxSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'ecommerce_tax_enabled' => $onOffRule = new OnOffRule(),
            'display_tax_fields_at_checkout_page' => $onOffRule,
            'default_tax_rate' => ['nullable', 'integer', 'min:0'],
            'display_product_price_including_taxes' => $onOffRule,
        ];
    }
}
