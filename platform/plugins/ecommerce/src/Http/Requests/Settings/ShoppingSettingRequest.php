<?php

namespace Botble\Ecommerce\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class ShoppingSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'shopping_cart_enabled' => $onOffRule = new OnOffRule(),
            'cart_destroy_on_logout' => $onOffRule,
            'wishlist_enabled' => $onOffRule,
            'compare_enabled' => $onOffRule,
            'order_tracking_enabled' => $onOffRule,
            'enable_quick_buy_button' => $onOffRule,
            'order_auto_confirmed' => $onOffRule,
            'quick_buy_target_page' => ['nullable', 'required_if:enable_quick_buy_button,1', 'in:checkout,cart'],
        ];
    }
}
