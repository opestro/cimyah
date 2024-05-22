@if (get_payment_setting('status', RAZORPAY_PAYMENT_METHOD_NAME) == 1)
    <x-plugins-payment::payment-method
        :name="RAZORPAY_PAYMENT_METHOD_NAME"
        paymentName="Razorpay"
        :supportedCurrencies="(new Botble\Razorpay\Services\Gateways\RazorpayPaymentService)->supportedCurrencyCodes()"
    >
        <x-slot name="currencyNotSupportedMessage">
            <p class="mt-1 mb-0">
                {{ __('Learn more') }}:
                {{ Html::link('https://razorpay.com/docs/payments/payments/international-payments/#supported-currencies', attributes: ['target' => '_blank', 'rel' => 'nofollow']) }}.
            </p>
        </x-slot>

        @if ($errorMessage)
            <div class="text-danger my-2">
                {!! BaseHelper::clean($errorMessage) !!}
            </div>
        @endif

        <input id="rzp_order_id" type="hidden" value="{{ $orderId }}">
    </x-plugins-payment::payment-method>

    @if (EcommerceHelper::isValidToProcessCheckout())
        <script>
            $(document).ready(function() {

                var $paymentCheckoutForm = $(document).find('.payment-checkout-form');

                $paymentCheckoutForm.on('submit', function(e) {
                    if ($paymentCheckoutForm.valid() && $('input[name=payment_method]:checked').val() ===
                        'razorpay' && !$('input[name=razorpay_payment_id]').val()) {
                        e.preventDefault();
                    }
                });

                var loadExternalScript = function(path) {
                    var result = $.Deferred();
                    var script = document.createElement('script');

                    script.async = 'async';
                    script.type = 'text/javascript';
                    script.src = path;
                    script.onload = script.onreadystatechange = function(_, isAbort) {
                        if (!script.readyState || /loaded|complete/.test(script.readyState)) {
                            if (isAbort) {
                                result.reject();
                            } else {
                                result.resolve();
                            }
                        }
                    };

                    script.onerror = function() {
                        result.reject();
                    };

                    $('head')[0].appendChild(script);

                    return result.promise();
                }

                var callRazorPayScript = function() {
                    loadExternalScript('https://checkout.razorpay.com/v1/checkout.js').then(function() {
                        window.rzpay = new Razorpay({
                            key: '{{ get_payment_setting('key', RAZORPAY_PAYMENT_METHOD_NAME) }}',
                            name: '{{ $name }}',
                            description: '{{ $name }}',
                            order_id: $('#rzp_order_id').val(),
                            handler: function(transaction) {
                                var form = $paymentCheckoutForm;
                                if (transaction.razorpay_payment_id && transaction
                                    .razorpay_order_id && transaction.razorpay_signature) {
                                    form.append($(
                                        '<input type="hidden" name="razorpay_payment_id">'
                                    ).val(transaction.razorpay_payment_id));
                                    form.append($(
                                        '<input type="hidden" name="razorpay_order_id">'
                                    ).val(transaction.razorpay_order_id));
                                    form.append($(
                                        '<input type="hidden" name="razorpay_signature">'
                                    ).val(transaction.razorpay_signature));
                                    form.submit();
                                }
                            },
                            'prefill': {
                                'name': $(document).find('#address_name').val(),
                                'email': $(document).find('#address_email').val(),
                                'contact': $(document).find('#address_phone').val()
                            },
                        });
                        window.rzpay.open();
                    });
                }

                $(document).off('click', '.payment-checkout-btn').on('click', '.payment-checkout-btn', function(event) {
                    event.preventDefault();

                    var _self = $(this);
                    var form = _self.closest('form');

                    if (form.valid && !form.valid()) {
                        return;
                    }

                    _self.attr('disabled', 'disabled');
                    var submitInitialText = _self.html();
                    _self.html('<i class="fa fa-gear fa-spin"></i> ' + _self.data('processing-text'));

                    var method = $('input[name=payment_method]:checked').val();

                    if (method === 'stripe' && $('.stripe-card-wrapper').length > 0) {
                        Stripe.setPublishableKey($('#payment-stripe-key').data('value'));
                        Stripe.card.createToken(form, function(status, response) {
                            if (response.error) {
                                if (typeof Botble != 'undefined') {
                                    Botble.showError(response.error.message, _self.data(
                                        'error-header'));
                                } else {
                                    alert(response.error.message);
                                }
                                _self.removeAttr('disabled');
                                _self.html(submitInitialText);
                            } else {
                                form.append($('<input type="hidden" name="stripeToken">').val(response
                                    .id));
                                form.submit();
                            }
                        });
                    } else if (method === 'razorpay') {

                        callRazorPayScript();

                        _self.removeAttr('disabled');
                        _self.html(submitInitialText);
                    } else {
                        form.submit();
                    }
                });
            });
        </script>
    @endif
@endif
