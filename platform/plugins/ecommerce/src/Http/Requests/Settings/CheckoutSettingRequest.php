<?php

namespace Botble\Ecommerce\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\Base\Supports\Helper;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CheckoutSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'enable_guest_checkout' => $onOffRule = new OnOffRule(),
            'minimum_order_amount' => ['nullable', 'numeric', 'min:0'],
            'minimum_order_quantity' => ['nullable', 'integer', 'min:0'],
            'maximum_order_quantity' => ['nullable', 'integer', 'min:0'],
            'mandatory_form_fields_at_checkout' => ['sometimes', 'array'],
            'mandatory_form_fields_at_checkout.*' => ['nullable', Rule::in(array_keys(EcommerceHelper::getMandatoryFieldsAtCheckout()))],
            'hide_form_fields_at_checkout' => ['sometimes', 'array'],
            'hide_form_fields_at_checkout.*' => ['nullable', Rule::in(array_keys(EcommerceHelper::getMandatoryFieldsAtCheckout()))],
            'zip_code_enabled' => $onOffRule,
            'billing_address_enabled' => $onOffRule,
            'display_tax_fields_at_checkout_page' => $onOffRule,
            'load_countries_states_cities_from_location_plugin' => $onOffRule,
            'use_city_field_as_field_text' => $onOffRule,
            'available_countries' => ['sometimes', 'array'],
            'available_countries.*' => ['nullable', Rule::in(array_keys(Helper::countries()))],
            'enable_customer_recently_viewed_products' => $onOffRule,
            'max_customer_recently_viewed_products' => ['nullable', 'required_if:enable_customer_recently_viewed_products,1', 'integer', 'min:1'],
        ];
    }
}
