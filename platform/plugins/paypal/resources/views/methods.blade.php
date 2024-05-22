@if (setting('payment_paypal_status') == 1)
    <x-plugins-payment::payment-method
        :name="PAYPAL_PAYMENT_METHOD_NAME"
        paymentName="PayPal"
        :supportedCurrencies="(new Botble\PayPal\Services\Gateways\PayPalPaymentService)->supportedCurrencyCodes()"
    >
        <x-slot name="currencyNotSupportedMessage">
            <p class="mt-1 mb-0">
                {{ __('Learn more') }}:
                {{ Html::link('https://developer.paypal.com/docs/api/reference/currency-codes', attributes: ['target' => '_blank', 'rel' => 'nofollow']) }}.
            </p>
        </x-slot>
    </x-plugins-payment::payment-method>
@endif
