@if (get_payment_setting('status', SSLCOMMERZ_PAYMENT_METHOD_NAME) == 1)
    <x-plugins-payment::payment-method
        :name="SSLCOMMERZ_PAYMENT_METHOD_NAME"
        paymentName="SslCommerz"
    />
@endif
