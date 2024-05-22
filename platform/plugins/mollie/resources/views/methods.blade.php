@if (get_payment_setting('status', MOLLIE_PAYMENT_METHOD_NAME) == 1)
    <x-plugins-payment::payment-method
        :name="MOLLIE_PAYMENT_METHOD_NAME"
        paymentName="Mollie"
    />
@endif
