<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Ecommerce\Enums\ShippingRuleTypeEnum;
use Botble\Ecommerce\Models\ShippingRule;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ShippingRuleItemRequest extends Request
{
    public function rules(): array
    {
        return [
            'shipping_rule_id' => [
                'required',
                Rule::exists(ShippingRule::class, 'id')->where(function ($query) {
                    return $query->whereIn('type', ShippingRuleTypeEnum::keysAllowRuleItems());
                }),
            ],
            'country' => ['required'],
            'state' => [
                'sometimes',
                Rule::requiredIf(function () {
                    return ShippingRule::query()
                        ->where([
                            'id' => $this->input('shipping_rule_id'),
                            'type' => ShippingRuleTypeEnum::BASED_ON_LOCATION,
                        ])
                        ->exists();
                }),
                Rule::exists('states', 'id'),
            ],
            'city' => ['nullable', 'required_without:state', 'exists:cities,id'],
            'zip_code' => [
                'min:5',
                'max:9',
                Rule::requiredIf(function () {
                    return ShippingRule::query()
                        ->where([
                            'id' => $this->input('shipping_rule_id'),
                            'type' => ShippingRuleTypeEnum::BASED_ON_ZIPCODE,
                        ])
                        ->exists();
                }),
            ],
            'adjustment_price' => ['required', 'numeric', 'min:-100000000000', 'max:100000000000'],
            'is_enabled' => Rule::in(['0', '1']),
        ];
    }
}
