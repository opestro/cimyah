<?php

namespace Database\Seeders\Themes\Main;

use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Setting\Facades\Setting;
use Botble\Slug\Facades\SlugHelper;
use Botble\Slug\Models\Slug;
use Botble\Theme\Database\Seeders\ThemeSeeder;

class SettingSeeder extends ThemeSeeder
{
    public function run(): void
    {
        $this->uploadFiles('general', 'main');

        $settings = [
            'admin_favicon' => $this->filePath('general/favicon.png'),
            'admin_logo' => $this->filePath('general/logo-white.png'),
            SlugHelper::getPermalinkSettingKey(Post::class) => 'blog',
            SlugHelper::getPermalinkSettingKey(Category::class) => 'blog',
            'payment_cod_status' => true,
            'payment_cod_description' => 'Please pay money directly to the postman, if you choose cash on delivery method (COD).',
            'payment_bank_transfer_status' => true,
            'payment_bank_transfer_description' => 'Please send money to our bank account: ACB - 69270 213 19.',
            'payment_stripe_payment_type' => 'stripe_checkout',
            'plugins_ecommerce_customer_new_order_status' => false,
            'plugins_ecommerce_admin_new_order_status' => false,
            'ecommerce_is_enabled_support_digital_products' => true,
            'ecommerce_load_countries_states_cities_from_location_plugin' => false,
            'announcement_lazy_loading' => true,
            'ecommerce_product_sku_format' => 'SF-2443-%s%s%s%s',
        ];

        Setting::delete(array_keys($settings));

        Setting::set($settings)->save();

        Slug::query()->where('reference_type', Post::class)->update(['prefix' => 'blog']);
        Slug::query()->where('reference_type', Category::class)->update(['prefix' => 'blog']);
    }
}
