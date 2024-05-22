<?php

namespace Botble\Ecommerce\Forms\Settings;

use Botble\Base\Facades\Assets;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\Settings\GeneralSettingRequest;
use Botble\Setting\Forms\SettingForm;

class GeneralSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation()) {
            Assets::addScriptsDirectly('vendor/core/plugins/location/js/location.js');
        }

        $this
            ->setSectionTitle(trans('plugins/ecommerce::setting.general.name'))
            ->setSectionDescription(trans('plugins/ecommerce::store-locator.description'))
            ->setValidatorClass(GeneralSettingRequest::class)
            ->columns(3)
            ->add('store_name', 'text', [
                'label' => trans('plugins/ecommerce::store-locator.shop_name'),
                'value' => get_ecommerce_setting('store_name'),
                'colspan' => 3,
            ])
            ->add('store_company', 'text', [
                'label' => trans('plugins/ecommerce::ecommerce.company'),
                'value' => get_ecommerce_setting('store_company'),
                'colspan' => 3,
            ])
            ->add('store_phone', 'text', [
                'label' => trans('plugins/ecommerce::ecommerce.phone'),
                'value' => get_ecommerce_setting('store_phone'),
                'colspan' => 3,
            ])
            ->add('store_email', 'text', [
                'label' => trans('plugins/ecommerce::ecommerce.email'),
                'value' => get_ecommerce_setting('store_email'),
                'colspan' => 3,
            ])
            ->add('store_address', 'text', [
                'label' => trans('plugins/ecommerce::ecommerce.address'),
                'value' => get_ecommerce_setting('store_address'),
                'colspan' => 3,
            ])
            ->add('store_country', 'customSelect', [
                'label' => trans('plugins/ecommerce::ecommerce.country'),
                'selected' => get_ecommerce_setting('store_country'),
                'choices' => EcommerceHelper::getAvailableCountries(),
                'attr' => [
                    'data-type' => 'country',
                    'searchable' => true,
                ],
                'colspan' => 1,
            ]);

        if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation()) {
            $this->add('store_state', 'customSelect', [
                'label' => trans('plugins/ecommerce::ecommerce.state'),
                'selected' => get_ecommerce_setting('store_state'),
                'choices' => get_ecommerce_setting('store_country') || ! EcommerceHelper::isUsingInMultipleCountries()
                    ? EcommerceHelper::getAvailableStatesByCountry(get_ecommerce_setting('store_country'))
                    : [],
                'attr' => [
                    'data-type' => 'state',
                    'data-url' => route('ajax.states-by-country'),
                ],
                'colspan' => 1,
            ]);
        } else {
            $this->add('store_state', 'text', [
                'label' => trans('plugins/ecommerce::ecommerce.state'),
                'value' => get_ecommerce_setting('store_state'),
                'colspan' => 1,
            ]);
        }

        if (EcommerceHelper::useCityFieldAsTextField()) {
            $this->add('store_city', 'text', [
                'label' => trans('plugins/ecommerce::ecommerce.city'),
                'value' => get_ecommerce_setting('store_city'),
                'colspan' => 1,
            ]);
        } else {
            $this->add('store_city', 'customSelect', [
                'label' => trans('plugins/ecommerce::ecommerce.city'),
                'selected' => get_ecommerce_setting('store_city'),
                'choices' => get_ecommerce_setting('store_city')
                    ? EcommerceHelper::getAvailableCitiesByState(get_ecommerce_setting('store_state'))
                    : [],
                'attr' => [
                    'data-type' => 'city',
                    'data-url' => route('ajax.cities-by-state'),
                    'data-using-select2' => false,
                ],
                'colspan' => 1,
            ]);
        }

        if (EcommerceHelper::isZipCodeEnabled()) {
            $this->add('store_zip_code', 'text', [
                'label' => trans('plugins/ecommerce::store-locator.zip_code'),
                'value' => get_ecommerce_setting('store_zip_code'),
                'colspan' => 3,
            ]);
        }

        $this->add('store_vat_number', 'text', [
            'label' => trans('plugins/ecommerce::ecommerce.tax_id'),
            'value' => get_ecommerce_setting('store_vat_number'),
            'colspan' => 3,
        ]);
    }
}
