<x-plugins-payment::payment-method
    :name="Botble\Payment\Enums\PaymentMethodEnum::COD"
    :label="get_payment_setting('name', 'cod', trans('plugins/payment::payment.payment_via_cod'))"
>
    @php
        $minimumOrderAmount = get_payment_setting('minimum_amount', 'cod', 0);
    @endphp

    @if ($minimumOrderAmount > Cart::instance('cart')->rawSubTotal())
        <div
            class="alert alert-warning"
            style="margin-top: 15px;"
        >
            {{ __('Minimum order amount to use COD (Cash On Delivery) payment method is :amount, you need to buy more :more to place an order!', ['amount' => format_price($minimumOrderAmount), 'more' => format_price($minimumOrderAmount - Cart::instance('cart')->rawSubTotal())]) }}
        </div>
    @endif
</x-plugins-payment::payment-method>
