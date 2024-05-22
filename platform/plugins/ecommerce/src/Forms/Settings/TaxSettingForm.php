<?php

namespace Botble\Ecommerce\Forms\Settings;

use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\Settings\TaxSettingRequest;
use Botble\Ecommerce\Models\Tax;
use Botble\Setting\Forms\SettingForm;

class TaxSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/ecommerce::setting.tax.tax_setting'))
            ->setSectionDescription(trans('plugins/ecommerce::setting.tax.tax_setting_description'))
            ->contentOnly()
            ->setValidatorClass(TaxSettingRequest::class)
            ->add('ecommerce_tax_enabled', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.tax.form.enable_tax'),
                'value' => EcommerceHelper::isTaxEnabled(),
                'wrapper' => [
                    'class' => 'mb-0',
                ],
                'attr' => [
                    'data-bb-toggle' => 'collapse',
                    'data-bb-target' => '.tax-settings',
                ],
            ])
            ->add('open_fieldset_tax_settings', 'html', [
                'html' => sprintf(
                    '<fieldset class="form-fieldset mt-3 tax-settings" style="display: %s;" data-bb-value="1">',
                    EcommerceHelper::isTaxEnabled() ? 'block' : 'none'
                ),
            ])
            ->add('default_tax_rate', 'customSelect', [
                'label' => trans('plugins/ecommerce::setting.tax.form.default_tax_rate'),
                'selected' => get_ecommerce_setting('default_tax_rate'),
                'choices' => [0 => trans('plugins/ecommerce::setting.tax.form.select_tax')] +
                    app(Tax::class)->pluck('title', 'id')->all(),
                'help_block' => [
                    'text' => trans('plugins/ecommerce::setting.tax.form.default_tax_rate_description'),
                ],
            ])
            ->add('display_product_price_including_taxes', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.tax.form.display_product_price_including_taxes'),
                'value' => EcommerceHelper::isDisplayProductIncludingTaxes(),
            ])
            ->add('display_tax_fields_at_checkout_page', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.tax.form.display_tax_fields_at_checkout_page'),
                'value' => EcommerceHelper::isDisplayTaxFieldsAtCheckoutPage(),
            ])
            ->add('close_fieldset_tax_settings', 'html', [
                'html' => '</fieldset>',
            ]);
    }
}
