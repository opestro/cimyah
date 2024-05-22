<?php

namespace Botble\Ecommerce\Forms\Settings;

use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\Settings\ShoppingSettingRequest;
use Botble\Setting\Forms\SettingForm;

class ShoppingSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/ecommerce::setting.shopping.name'))
            ->setSectionDescription(trans('plugins/ecommerce::setting.shopping.description'))
            ->setValidatorClass(ShoppingSettingRequest::class)
            ->add('shopping_cart_enabled', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.shopping.form.enable_cart'),
                'value' => EcommerceHelper::isCartEnabled(),
            ])
            ->add('cart_destroy_on_logout', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.shopping.form.cart_destroy_on_logout'),
                'value' => get_ecommerce_setting('cart_destroy_on_logout', false),
            ])
            ->add('wishlist_enabled', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.shopping.form.enable_wishlist'),
                'value' => EcommerceHelper::isWishlistEnabled(),
            ])
            ->add('compare_enabled', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.shopping.form.enable_compare'),
                'value' => EcommerceHelper::isCompareEnabled(),
            ])
            ->add('order_tracking_enabled', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.shopping.form.enable_order_tracking'),
                'value' => EcommerceHelper::isOrderTrackingEnabled(),
            ])
            ->add('enable_quick_buy_button', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.shopping.form.enable_quick_buy_button'),
                'value' => EcommerceHelper::isQuickBuyButtonEnabled(),
            ])
            ->add('order_auto_confirmed', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.shopping.form.enable_order_auto_confirmed'),
                'value' => EcommerceHelper::isOrderAutoConfirmedEnabled(),
            ])
            ->add('quick_buy_target_page', 'customRadio', [
                'label' => trans('plugins/ecommerce::setting.shopping.form.quick_buy_target'),
                'value' => EcommerceHelper::getQuickBuyButtonTarget(),
                'values' => [
                    'checkout' => trans('plugins/ecommerce::setting.shopping.form.checkout_page'),
                    'cart' => trans('plugins/ecommerce::setting.shopping.form.cart_page'),
                ],
            ]);
    }
}
