<?php

namespace Botble\Ecommerce\Forms\Settings;

use Botble\Ecommerce\Http\Requests\Settings\ShippingSettingRequest;
use Botble\Setting\Forms\SettingForm;

class ShippingSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/ecommerce::setting.shipping.shipping_setting'))
            ->setSectionDescription(trans('plugins/ecommerce::setting.shipping.shipping_setting_description'))
            ->setValidatorClass(ShippingSettingRequest::class)
            ->contentOnly()
            ->add('hide_other_shipping_options_if_it_has_free_shipping', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.shipping.form.hide_other_shipping_options_if_it_has_free_shipping'),
                'value' => get_ecommerce_setting('hide_other_shipping_options_if_it_has_free_shipping', false),
                'wrapper' => [
                    'class' => 'mb-0',
                ],
            ]);
    }
}
