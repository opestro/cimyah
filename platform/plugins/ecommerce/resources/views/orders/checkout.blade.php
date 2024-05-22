@extends('plugins/ecommerce::orders.master')

@section('title', __('Checkout'))

@section('content')
    @if (Cart::instance('cart')->isNotEmpty())
        @if (is_plugin_active('payment') && $orderAmount)
            @include('plugins/payment::partials.header')
        @endif

        <x-core::form
            :url="route('public.checkout.process', $token)"
            id="checkout-form"
            class="checkout-form payment-checkout-form"
            :data-update-url="route('public.ajax.checkout.update')"
        >
            <input id="checkout-token" name="checkout-token" type="hidden" value="{{ $token }}">

            <div class="row" id="main-checkout-product-info">
                <div class="order-1 order-md-2 col-lg-5 col-md-6">
                    <div class="d-block d-sm-none">
                        @include('plugins/ecommerce::orders.partials.logo')
                    </div>
                    <div class="position-relative" id="cart-item">
                        @include('plugins/ecommerce::orders.partials.amount')
                    </div>

                    <div class="mt-3 mb-5">
                        @include(EcommerceHelper::viewPath('discounts.partials.form'), compact('discounts'))
                    </div>
                </div>

                <div class="col-lg-7 col-md-6">
                    <div class="d-none d-sm-block">
                        @include('plugins/ecommerce::orders.partials.logo')
                    </div>

                    <div class="form-checkout">
                        {!! apply_filters('ecommerce_checkout_form_before', null, $products) !!}

                        @if ($isShowAddressForm)
                            <div class="mb-4">
                                <h5 class="checkout-payment-title">{{ __('Shipping information') }}</h5>

                                <input
                                    id="save-shipping-information-url"
                                    type="hidden"
                                    value="{{ route('public.checkout.save-information', $token) }}"
                                >

                                @include(
                                    'plugins/ecommerce::orders.partials.address-form',
                                    compact('sessionCheckoutData')
                                )
                            </div>

                            {!! apply_filters('ecommerce_checkout_form_after_shipping_address_form', null, $products) !!}
                        @endif

                        @if (EcommerceHelper::isBillingAddressEnabled())
                            <div class="mb-4">
                                <h5 class="checkout-payment-title">{{ __('Billing information') }}</h5>

                                @include(
                                    'plugins/ecommerce::orders.partials.billing-address-form',
                                    compact('sessionCheckoutData')
                                )
                            </div>

                            {!! apply_filters('ecommerce_checkout_form_after_billing_address_form', null, $products) !!}
                        @endif

                        @if (! is_plugin_active('marketplace'))
                            @if (Arr::get($sessionCheckoutData, 'is_available_shipping', true))
                                <div class="shipping-method-wrapper mb-4">
                                    <h5 class="checkout-payment-title">{{ __('Shipping method') }}</h5>
                                    <div class="shipping-info-loading loading-spinner" style="display: none;"></div>

                                    <div data-bb-toggle="checkout-shipping-methods-area">
                                        @include('plugins/ecommerce::orders.partials.shipping-methods')
                                    </div>
                                </div>

                                {!! apply_filters('ecommerce_checkout_form_after_shipping_address_form', null, $products) !!}
                            @endif
                        @endif

                        {!! apply_filters('ecommerce_checkout_form_before_payment_form', null, $products) !!}

                        <input
                            name="amount"
                            type="hidden"
                            value="{{ format_price($orderAmount, null, true) }}"
                        >

                        <div data-bb-toggle="checkout-payment-methods-area">
                            @include('plugins/ecommerce::orders.partials.payment-methods')
                        </div>

                        {!! apply_filters('ecommerce_checkout_form_after_payment_form', null, $products) !!}

                        <div @class(['form-group mb-3', 'has-error' => $errors->has('description')])>
                            <label class="form-label" for="description">{{ __('Order notes') }}</label>
                            <textarea
                                class="form-control"
                                id="description"
                                name="description"
                                rows="3"
                                placeholder="{{ __('Notes about your order, e.g. special notes for delivery.') }}"
                            >{{ old('description') }}</textarea>
                            {!! Form::error('description', $errors) !!}
                        </div>

                        @if (EcommerceHelper::getMinimumOrderAmount() > $rawTotal)
                            <div role="alert" class="alert alert-warning">
                                {{ __('Minimum order amount is :amount, you need to buy more :more to place an order!', ['amount' => format_price(EcommerceHelper::getMinimumOrderAmount()), 'more' => format_price(EcommerceHelper::getMinimumOrderAmount() - Cart::instance('cart')->rawSubTotal())]) }}
                            </div>
                        @endif

                        @if (EcommerceHelper::isDisplayTaxFieldsAtCheckoutPage())
                            @include(
                                'plugins/ecommerce::orders.partials.tax-information',
                                compact('sessionCheckoutData')
                            )

                            {!! apply_filters('ecommerce_checkout_form_after_tax_information_form', null, $products) !!}
                        @endif

                        @if($privacyPolicyUrl = theme_option('ecommerce_term_and_privacy_policy_url'))
                            <div class="form-check ps-0 mb-3">
                                <input
                                    id="agree_terms_and_policy"
                                    name="agree_terms_and_policy"
                                    type="checkbox"
                                    value="1"
                                    @checked (old('agree_terms_and_policy', true))
                                >
                                <label class="form-check-label" for="agree_terms_and_policy">
                                    {!! BaseHelper::clean(__(
                                        'I agree to the :link',
                                        ['link' => Html::link($privacyPolicyUrl, __('Terms and Privacy Policy'), attributes: ['class' => 'text-decoration-underline', 'target' => '_blank'])]
                                    )) !!}
                                </label>
                            </div>
                        @endif

                        {!! apply_filters('ecommerce_checkout_form_after', null, $products) !!}

                        <div class="row align-items-center g-3 mb-5">
                            <div class="order-2 order-md-1 col-md-6 text-center text-md-start mb-4 mb-md-0">
                                <a class="d-flex align-items-center gap-1" href="{{ route('public.cart') }}">
                                    <x-core::icon name="ti ti-arrow-narrow-left" />
                                    <span class="d-inline-block back-to-cart">{{ __('Back to cart') }}</span>
                                </a>

                                {!! apply_filters('ecommerce_checkout_form_after_back_to_cart_link', null, $products) !!}
                            </div>
                            <div class="order-1 order-md-2 col-md-6">
                                @if (EcommerceHelper::isValidToProcessCheckout())
                                    <button
                                        class="btn payment-checkout-btn payment-checkout-btn-step float-end"
                                        data-processing-text="{{ __('Processing. Please wait...') }}"
                                        data-error-header="{{ __('Error') }}"
                                        type="submit"
                                    >
                                        {{ __('Checkout') }}
                                    </button>
                                @else
                                    <span class="btn payment-checkout-btn-step float-end disabled">
                                        {{ __('Checkout') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-core::form>

        @if (is_plugin_active('payment'))
            @include('plugins/payment::partials.footer')
        @endif
    @else
        <div class="container">
            <div class="alert alert-warning my-5">
                <span>{!! __('No products in cart. :link!', ['link' => Html::link(BaseHelper::getHomepageUrl(), __('Back to shopping'))]) !!}</span>
            </div>
        </div>
    @endif
@stop

@push('footer')
    <script type="text/javascript" src="{{ asset('vendor/core/core/js-validation/js/js-validation.js') }}"></script>

    {!! JsValidator::formRequest(
        Botble\Ecommerce\Http\Requests\SaveCheckoutInformationRequest::class,
        '#checkout-form',
    ) !!}
@endpush
