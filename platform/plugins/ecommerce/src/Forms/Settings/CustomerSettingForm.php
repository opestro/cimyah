<?php

namespace Botble\Ecommerce\Forms\Settings;

use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\RadioFieldOption;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\RadioField;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\Settings\CustomerSettingRequest;
use Botble\Setting\Forms\SettingForm;

class CustomerSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/ecommerce::setting.customer.customer_setting'))
            ->setSectionDescription(trans('plugins/ecommerce::setting.customer.customer_setting_description'))
            ->setValidatorClass(CustomerSettingRequest::class)
            ->add(
                'verify_customer_email',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.customer.form.verify_customer_email'))
                    ->helperText(trans('plugins/ecommerce::setting.customer.form.verify_customer_email_helper'))
                    ->value(EcommerceHelper::isEnableEmailVerification())
                    ->toArray()
            )
            ->add(
                'enabled_customer_account_deletion',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.customer.form.enabled_customer_account_deletion'))
                    ->helperText(trans('plugins/ecommerce::setting.customer.form.enabled_customer_account_deletion_helper'))
                    ->value(get_ecommerce_setting('enabled_customer_account_deletion', true))
                    ->toArray()
            )
            ->add(
                'enabled_customer_dob_field',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.customer.form.enabled_customer_dob_field'))
                    ->helperText(trans('plugins/ecommerce::setting.customer.form.enabled_customer_dob_field_helper'))
                    ->value(get_ecommerce_setting('enabled_customer_dob_field', true))
                    ->toArray()
            )
            ->add(
                'login_option',
                RadioField::class,
                RadioFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.customer.form.login_option'))
                    ->selected(EcommerceHelper::getLoginOption())
                    ->choices([
                        'email' => trans('plugins/ecommerce::setting.customer.form.login_with_email'),
                        'phone' => trans('plugins/ecommerce::setting.customer.form.login_with_phone'),
                        'email_or_phone' => trans('plugins/ecommerce::setting.customer.form.login_with_email_or_phone'),
                    ])
                    ->toArray(),
            );
    }
}
