<?php

namespace Botble\SalePopup\Providers;

use Botble\Base\Supports\ServiceProvider;
use Botble\Language\Facades\Language;
use Botble\Setting\Facades\Setting;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_filter('sale_popup_setting_key', function (string $key): string {
            if (! is_plugin_active('language') || ! is_plugin_active('language-advanced')) {
                return $key;
            }

            $currentLocale = is_in_admin(true) ? Language::getCurrentAdminLocale() : Language::getCurrentLocale();
            $locale = $currentLocale !== Language::getDefaultLocale() ? $currentLocale : null;

            if ($locale && in_array($locale, array_keys(Language::getSupportedLocales()))) {
                $key = "$key-$locale";

                return Setting::has("$key-$locale") ? "$key-$locale" : $key;
            }

            return $key;
        }, 55);
    }
}
