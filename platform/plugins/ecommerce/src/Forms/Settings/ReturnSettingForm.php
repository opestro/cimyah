<?php

namespace Botble\Ecommerce\Forms\Settings;

use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\Settings\ReturnSettingRequest;
use Botble\Setting\Forms\SettingForm;

class ReturnSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/ecommerce::setting.return.name'))
            ->setSectionDescription(trans('plugins/ecommerce::setting.return.description'))
            ->setValidatorClass(ReturnSettingRequest::class)
            ->add('is_enabled_order_return', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.return.form.is_enabled_order_return'),
                'value' => EcommerceHelper::isOrderReturnEnabled(),
                'wrapper' => [
                    'class' => 'mb-0',
                ],
                'attr' => [
                    'data-bb-toggle' => 'collapse',
                    'data-bb-target' => '.order-returns-settings',
                ],
            ])
            ->add('open_fieldset_order_returns_settings', 'html', [
                'html' => sprintf(
                    '<fieldset class="form-fieldset mt-3 order-returns-settings" style="display: %s;" data-bb-value="1">',
                    EcommerceHelper::isOrderReturnEnabled() ? 'block' : 'none'
                ),
            ])
            ->add('can_custom_return_product_quantity', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.return.form.allow_partial_return'),
                'value' => EcommerceHelper::allowPartialReturn(),
                'help_block' => [
                    'text' => trans('plugins/ecommerce::setting.return.form.allow_partial_return_description'),
                ],
            ])
            ->add('returnable_days', 'number', [
                'label' => trans('plugins/ecommerce::setting.return.form.returnable_days'),
                'value' => EcommerceHelper::getReturnableDays(),
                'attr' => [
                    'placeholder' => trans('plugins/ecommerce::setting.return.form.returnable_days'),
                    'min' => 1,
                ],
                'help_block' => [
                    'text' => trans('plugins/ecommerce::setting.return.form.return_settings_helper'),
                ],
            ])
            ->add('close_fieldset_order_returns_settings', 'html', [
                'html' => '</fieldset>',
            ]);
    }
}
