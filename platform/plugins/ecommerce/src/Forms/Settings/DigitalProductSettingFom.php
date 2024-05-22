<?php

namespace Botble\Ecommerce\Forms\Settings;

use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\Settings\DigitalProductSettingRequest;
use Botble\Setting\Forms\SettingForm;

class DigitalProductSettingFom extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/ecommerce::setting.digital_product.digital_products_settings'))
            ->setSectionDescription(trans('plugins/ecommerce::setting.digital_product.digital_products_settings_description'))
            ->setValidatorClass(DigitalProductSettingRequest::class)
            ->add('is_enabled_support_digital_products', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.digital_product.form.enable_support_digital_product'),
                'value' => EcommerceHelper::isEnabledSupportDigitalProducts(),
                'wrapper' => [
                    'class' => 'mb-0',
                ],
                'attr' => [
                    'data-bb-toggle' => 'collapse',
                    'data-bb-target' => '.digital-products-settings',
                ],
            ])
            ->add('open_allow_guest_checkout_for_digital_products', 'html', [
                'html' => sprintf(
                    '<fieldset class="form-fieldset mt-3 digital-products-settings" style="display: %s;" data-bb-value="1">',
                    EcommerceHelper::isEnabledSupportDigitalProducts() ? 'block' : 'none'
                ),
            ])
            ->add('allow_guest_checkout_for_digital_products', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.digital_product.form.allow_guest_checkout_for_digital_products'),
                'value' => EcommerceHelper::allowGuestCheckoutForDigitalProducts(),
            ])
            ->add('closed_allow_guest_checkout_for_digital_products', 'html', ['html' => '</fieldset>']);
    }
}
