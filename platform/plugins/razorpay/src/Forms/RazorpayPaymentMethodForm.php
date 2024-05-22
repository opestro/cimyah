<?php

namespace Botble\Razorpay\Forms;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\TextField;
use Botble\Payment\Forms\PaymentMethodForm;

class RazorpayPaymentMethodForm extends PaymentMethodForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->paymentId(RAZORPAY_PAYMENT_METHOD_NAME)
            ->paymentName('Razorpay')
            ->paymentDescription(__('Customer can buy product and pay directly using Visa, Credit card via :name', ['name' => 'Razorpay']))
            ->paymentLogo(url('vendor/core/plugins/razorpay/images/razorpay.svg'))
            ->paymentUrl('https://razorpay.com')
            ->paymentInstructions(view('plugins/razorpay::instructions')->render())
            ->add(
                sprintf('payment_%s_key', RAZORPAY_PAYMENT_METHOD_NAME),
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Key'))
                    ->value(BaseHelper::hasDemoModeEnabled() ? '*******************************' : get_payment_setting('key', RAZORPAY_PAYMENT_METHOD_NAME))
                    ->toArray()
            )
            ->add(
                sprintf('payment_%s_secret', RAZORPAY_PAYMENT_METHOD_NAME),
                'password',
                TextFieldOption::make()
                    ->label(__('Secret'))
                    ->value(BaseHelper::hasDemoModeEnabled() ? '*******************************' : get_payment_setting('secret', RAZORPAY_PAYMENT_METHOD_NAME))
                    ->toArray()
            );
    }
}
