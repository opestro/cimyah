<x-plugins-payment::payment-method
    :name="\Botble\Payment\Enums\PaymentMethodEnum::COD"
    :label="get_payment_setting('name', 'cod', trans('plugins/payment::payment.payment_via_cod'))"
/>
