<?php

namespace Botble\PayPal\Forms;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Payment\Forms\PaymentMethodForm;

class PaypalPaymentMethodForm extends PaymentMethodForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->paymentId(PAYPAL_PAYMENT_METHOD_NAME)
            ->paymentName('Paypal')
            ->paymentDescription(trans('plugins/payment::payment.paypal_description'))
            ->paymentLogo(url('vendor/core/plugins/paypal/images/paypal.svg'))
            ->paymentUrl('https://paypal.com')
            ->defaultDescriptionValue(__('You will be redirected to :name to complete the payment.', ['name' => 'PayPal']))
            ->paymentInstructions(view('plugins/paypal::instructions')->render())
            ->add(
                sprintf('payment_%s_client_id', PAYPAL_PAYMENT_METHOD_NAME),
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/payment::payment.client_id'))
                    ->value(BaseHelper::hasDemoModeEnabled() ? '*******************************' : get_payment_setting('client_id', 'paypal'))
                    ->toArray()
            )
            ->add(
                sprintf('payment_%s_client_secret', PAYPAL_PAYMENT_METHOD_NAME),
                'password',
                TextFieldOption::make()
                    ->label(trans('plugins/payment::payment.client_secret'))
                    ->value(BaseHelper::hasDemoModeEnabled() ? '*******************************' : get_payment_setting('client_secret', 'paypal'))
                    ->toArray()
            )
            ->add(
                sprintf('payment_%s_mode', PAYPAL_PAYMENT_METHOD_NAME),
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/payment::payment.live_mode'))
                    ->value(get_payment_setting('mode', PAYPAL_PAYMENT_METHOD_NAME, true))
                    ->toArray()
            );
    }
}
