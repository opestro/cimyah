<?php

use Botble\Base\Models\BaseModel;
use Botble\Payment\Models\Payment;
use Botble\Stripe\Supports\StripeHelper;

if (! function_exists('convert_stripe_amount_from_api')) {
    function convert_stripe_amount_from_api(float $amount, ?BaseModel $currency): float
    {
        return $amount / StripeHelper::getStripeCurrencyMultiplier($currency);
    }
}

if (! function_exists('get_payment_setting')) {
    function get_payment_setting(string $key, $type = null, $default = null): string|array|null
    {
        return setting(get_payment_setting_key($key, $type), $default);
    }
}

if (! function_exists('get_payment_setting_key')) {
    function get_payment_setting_key(string $key, ?string $type = null): string
    {
        $key = $type ? "payment_{$type}_{$key}" : "payment_$key";

        return apply_filters('payment_setting_key', $key);
    }
}

if (! function_exists('get_payment_is_support_refund_online')) {
    function get_payment_is_support_refund_online(Payment $payment): bool|string
    {
        $paymentService = $payment->payment_channel->getServiceClass();

        if (! $paymentService || ! class_exists($paymentService)) {
            return false;
        }

        if (! method_exists($paymentService, 'getSupportRefundOnline')) {
            return false;
        }

        try {
            $isSupportRefund = (new $paymentService())->getSupportRefundOnline();

            return $isSupportRefund ? $paymentService : false;
        } catch (Exception) {
            return false;
        }
    }
}
