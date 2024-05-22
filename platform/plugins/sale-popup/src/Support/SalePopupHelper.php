<?php

namespace Botble\SalePopup\Support;

use Botble\Setting\Facades\Setting;

class SalePopupHelper
{
    public function getSettingKeyPrefix()
    {
        return apply_filters('sale_popup_setting_key_prefix', 'sale_popup');
    }

    public function getSetting(string $key, mixed $default = null): mixed
    {
        return setting(self::getSettingKey($key), $default);
    }

    public function getSettingKey(string $key)
    {
        return apply_filters(
            'sale_popup_setting_key',
            "{$this->getSettingKeyPrefix()}_$key"
        );
    }

    public function saveSettings(array $settings): void
    {
        foreach ($settings as $settingKey => $settingValue) {
            $settingValue = is_array($settingValue) ? json_encode($settingValue) : $settingValue;

            Setting::set($this->getSettingKey($settingKey), $settingValue);
        }

        Setting::save();
    }

    public function settingKeys(): array
    {
        return [
            'enabled',
            'collection_id',
            'purchased_text',
            'verified_text',
            'quick_view_text',
            'list_users_purchased',
            'show_time_ago_suggest',
            'list_sale_time',
            'limit_products',
            'show_verified',
            'show_close_button',
            'show_quick_view_button',
            'display_pages',
        ];
    }

    public function displayPages(): array
    {
        return [
            'public.index' => trans('plugins/sale-popup::sale-popup.display_pages.homepage'),
            'public.product' => trans('plugins/sale-popup::sale-popup.display_pages.product_detail'),
            'public.products' => trans('plugins/sale-popup::sale-popup.display_pages.product_listing'),
            'public.cart' => trans('plugins/sale-popup::sale-popup.display_pages.cart'),
        ];
    }
}
