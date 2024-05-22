<?php

namespace Botble\SslCommerz\Forms;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Payment\Forms\PaymentMethodForm;

class SslCommerzPaymentMethodForm extends PaymentMethodForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->paymentId(SSLCOMMERZ_PAYMENT_METHOD_NAME)
            ->paymentName('SslCommerz')
            ->paymentDescription(__('Customer can buy product and pay directly using Visa, Credit card via :name', ['name' => 'SslCommerz']))
            ->paymentLogo(url('vendor/core/plugins/sslcommerz/images/sslcommerz.png'))
            ->paymentUrl('https://sslcommerz.com')
            ->paymentInstructions(view('plugins/sslcommerz::instructions')->render())
            ->add(
                sprintf('payment_%s_store_id', SSLCOMMERZ_PAYMENT_METHOD_NAME),
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Store ID'))
                    ->value(BaseHelper::hasDemoModeEnabled() ? '*******************************' : get_payment_setting('store_id', SSLCOMMERZ_PAYMENT_METHOD_NAME))
                    ->attributes(['data-counter' => 400])
                    ->toArray()
            )
            ->add(
                sprintf('payment_%s_store_password', SSLCOMMERZ_PAYMENT_METHOD_NAME),
                'password',
                TextFieldOption::make()
                    ->label(__('Store Password (API/Secret key)'))
                    ->value(BaseHelper::hasDemoModeEnabled() ? '*******************************' : get_payment_setting('store_password', SSLCOMMERZ_PAYMENT_METHOD_NAME))
                    ->toArray()
            )
            ->add(
                sprintf('payment_%s_mode', SSLCOMMERZ_PAYMENT_METHOD_NAME),
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/payment::payment.live_mode'))
                    ->value(get_payment_setting('mode', SSLCOMMERZ_PAYMENT_METHOD_NAME, true))
                    ->toArray(),
            );
    }
}
