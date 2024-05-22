<?php

namespace Botble\PayPalPayout\Providers;

use Botble\Base\Facades\Assets;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Marketplace\Enums\PayoutPaymentMethodsEnum;
use Botble\Marketplace\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class PayPalPayoutServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        if (! is_plugin_active('payment')) {
            return;
        }

        $this->setNamespace('plugins/paypal-payout')
            ->loadAndPublishViews()
            ->publishAssets()
            ->loadRoutes();

        $this->app->booted(function () {
            add_filter(BASE_FILTER_BEFORE_RENDER_FORM, function ($form, $data) {
                if (
                    $data instanceof Withdrawal &&
                    is_in_admin(true) &&
                    Auth::check() &&
                    $data->getKey() && $data->payment_channel == PayoutPaymentMethodsEnum::PAYPAL
                ) {
                    Assets::addScriptsDirectly('vendor/core/plugins/paypal-payout/js/paypal-payout.js');

                    $form
                        ->add('payout-form', 'html', [
                            'html' => view('plugins/paypal-payout::payout-form', compact('data'))->render(),
                            'label' => __('PayPal automatically payout'),
                        ]);
                }

                return $form;
            }, 123, 2);
        });
    }
}
