@if (is_plugin_active('payment') && $orderAmount)
    @php
        $paymentMethods = apply_filters(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, null, [
            'amount' => format_price($orderAmount, null, true),
            'currency' => strtoupper(get_application_currency()->title),
            'name' => null,
            'selected' => PaymentMethods::getSelectedMethod(),
            'default' => PaymentMethods::getDefaultMethod(),
            'selecting' => PaymentMethods::getSelectingMethod(),
        ]) . PaymentMethods::render();
    @endphp

    <input
        name="currency"
        type="hidden"
        value="{{ strtoupper(get_application_currency()->title) }}"
    >

    @if($paymentMethods)
        <div class="position-relative mb-4">
            <div class="payment-info-loading loading-spinner" style="display: none"></div>
            <h5 class="checkout-payment-title">{{ __('Payment method') }}</h5>

            {!! apply_filters(PAYMENT_FILTER_PAYMENT_PARAMETERS, null) !!}

            <ul class="list-group list_payment_method">
                {!! $paymentMethods !!}
            </ul>
        </div>
    @endif
@endif
