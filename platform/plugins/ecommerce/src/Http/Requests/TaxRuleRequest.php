<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Tax;
use Botble\Ecommerce\Models\TaxRule;
use Botble\Location\Rules\CityRule;
use Botble\Location\Rules\StateRule;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class TaxRuleRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'tax_id' => Rule::exists(Tax::class, 'id'),
            'country' => [Rule::in(array_keys(EcommerceHelper::getAvailableCountries()))],
            'state' => ['nullable'],
            'city' => ['nullable'],
        ];

        if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation()) {
            $rules['state'] = ['nullable', new StateRule('country')];

            if (EcommerceHelper::useCityFieldAsTextField()) {
                $rules['city'] = [
                    'nullable',
                    'string',
                    'max:120',
                ];
            } else {
                $rules['city'] = ['nullable', new CityRule('state')];
            }
        }

        if (EcommerceHelper::isZipCodeEnabled()) {
            $rules['zip_code'] = [
                'nullable',
                'min:5',
                'max:9',
                Rule::unique(TaxRule::class, 'zip_code')->ignore($this->route('rule.id')),
            ];
        }

        return $rules;
    }
}
