@if (setting('payment_stripe_status') == 1)
    <x-plugins-payment::payment-method
        :name="STRIPE_PAYMENT_METHOD_NAME"
        paymentName="Stripe"
        :supportedCurrencies="(new Botble\Stripe\Services\Gateways\StripePaymentService)->supportedCurrencyCodes()"
    >
        @if (get_payment_setting('payment_type', STRIPE_PAYMENT_METHOD_NAME, 'stripe_api_charge') == 'stripe_api_charge')
            <div class="card-checkout" style="max-width: 350px">
                <div class="form-group mt-3 mb-3">
                    <div class="stripe-card-wrapper"></div>
                </div>

                <div @class(['form-group mb-3', 'has-error' => $errors->has('number') || $errors->has('expiry')])>
                    <div class="row">
                        <div class="col-sm-8">
                            <input
                                class="form-control"
                                id="stripe-number"
                                data-stripe="number"
                                type="text"
                                placeholder="{{ trans('plugins/payment::payment.card_number') }}"
                                autocomplete="off"
                            >
                        </div>
                        <div class="col-sm-4">
                            <input
                                class="form-control"
                                id="stripe-exp"
                                data-stripe="exp"
                                type="text"
                                placeholder="{{ trans('plugins/payment::payment.mm_yy') }}"
                                autocomplete="off"
                            >
                        </div>
                    </div>
                </div>
                <div @class(['form-group mb-3', 'has-error' => $errors->has('name') || $errors->has('cvc')])>
                    <div class="row">
                        <div class="col-sm-8">
                            <input
                                class="form-control"
                                id="stripe-name"
                                data-stripe="name"
                                type="text"
                                placeholder="{{ trans('plugins/payment::payment.full_name') }}"
                                autocomplete="off"
                            >
                        </div>
                        <div class="col-sm-4">
                            <input
                                class="form-control"
                                id="stripe-cvc"
                                data-stripe="cvc"
                                type="text"
                                placeholder="{{ trans('plugins/payment::payment.cvc') }}"
                                autocomplete="off"
                            >
                        </div>
                    </div>
                </div>
            </div>
            <div id="payment-stripe-key" data-value="{{ get_payment_setting('client_id', STRIPE_PAYMENT_METHOD_NAME) }}"></div>
        @endif
    </x-plugins-payment::payment-method>
@endif
