<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\AddShippingRegionRequest;
use Botble\Ecommerce\Models\Shipping;

class AddShippingRegionForm extends FormAbstract
{
    public function setup(): void
    {
        $existedCountries = Shipping::query()->pluck('country')->all();

        foreach ($existedCountries as &$existedCountry) {
            if (empty($existedCountry)) {
                $existedCountry = '';
            }
        }

        $countries = ['' => trans('plugins/ecommerce::shipping.all_countries')] + EcommerceHelper::getAvailableCountries();

        $countries = array_diff_key($countries, array_flip($existedCountries));

        $this
            ->setupModel(new Shipping())
            ->contentOnly()
            ->setUrl(route('shipping_methods.region.create'))
            ->setTitle(trans('plugins/ecommerce::shipping.add_shipping_region'))
            ->setValidatorClass(AddShippingRegionRequest::class)
            ->add(
                'region',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/ecommerce::shipping.country'))
                    ->required()
                    ->searchable()
                    ->choices($countries)
                    ->toArray()
            );
    }
}
