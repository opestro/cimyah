<?php

namespace Botble\Ecommerce\Http\Requests\Settings;

use Botble\Ecommerce\Facades\Currency;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CurrencySettingRequest extends Request
{
    public function prepareForValidation(): void
    {
        $this->merge([
            'currencies_data' => json_decode($this->input('currencies'), true),
        ]);
    }

    public function rules(): array
    {
        return [
            'currencies' => ['nullable', 'string', 'max:10000'],
            'deleted_currencies' => ['nullable', 'string', 'max:10000'],
            'currencies_data.*.title' => ['required', 'string', Rule::in(Currency::currencyCodes())],
            'currencies_data.*.symbol' => ['required', 'string'],
            'enable_auto_detect_visitor_currency' => ['sometimes', 'in:0,1'],
            'add_space_between_price_and_currency' => ['sometimes', 'in:0,1'],
            'thousands_separator' => ['required', 'string', Rule::in([',', '.', 'space'])],
            'decimal_separator' => ['required', 'string', Rule::in([',', '.', 'space'])],
            'use_exchange_rate_from_api' => ['sometimes', 'in:0,1'],
            'exchange_rate_api_provider' => ['nullable', 'in:api_layer,open_exchange_rate,none'],
            'api_layer_api_key' => ['nullable', 'required_if:exchange_rate_api_provider,api_layer', 'string'],
            'open_exchange_app_id' => ['nullable', 'required_if:exchange_rate_api_provider,open_exchange_rate', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'currencies_data.*.title.in' => trans('plugins/ecommerce::currency.invalid_currency_name', [
                'currencies' => implode(', ', Currency::currencyCodes()),
            ]),
        ];
    }

    public function attributes(): array
    {
        return [
            'currencies_data.*.title' => trans('plugins/ecommerce::currency.invalid_currency_name'),
            'currencies_data.*.symbol' => trans('plugins/ecommerce::currency.symbol'),
        ];
    }
}
