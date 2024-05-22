<?php

namespace Database\Seeders\Themes\Main;

use Botble\Page\Database\Traits\HasPageSeeder;
use Botble\Theme\Database\Seeders\ThemeSeeder;
use Botble\Theme\Database\Traits\HasThemeOptionSeeder;
use Botble\Theme\Supports\ThemeSupport;

class ThemeOptionSeeder extends ThemeSeeder
{
    use HasThemeOptionSeeder;
    use HasPageSeeder;

    public function run(): void
    {
        $this->createThemeOptions($this->getData());
    }

    protected function getData(): array
    {
        $this->uploadFiles('shapes', 'main');

        return [
            'site_name' => 'Shofy',
            'site_title' => 'Shofy - Multipurpose eCommerce Laravel Script',
            'seo_description' => 'Shofy is a powerful tool eCommerce Laravel script for creating a professional and visually appealing online store.',
            'copyright' => '© %Y All Rights Reserved.',
            'tp_primary_font' => 'Roboto',
            'primary_color' => '#0C55AA',
            'favicon' => $this->filePath('general/favicon.png', 'main'),
            'logo' => $this->filePath('general/logo.png', 'main'),
            'logo_light' => $this->filePath('general/logo-white.png', 'main'),
            'header_style' => 1,
            'preloader_icon' => $this->filePath('general/preloader-icon.png', 'main'),
            'address' => '502 New Street, Brighton VIC, Australia',
            'hotline' => '8 800 332 65-66',
            'email' => 'contact@fartmart.co',
            'working_time' => 'Mon - Fri: 07AM - 06PM',
            'homepage_id' => $this->getPageId('Home'),
            'blog_page_id' => $this->getPageId('Blog'),
            'cookie_consent_message' => 'Your experience on this site will be improved by allowing cookies ',
            'cookie_consent_learn_more_url' => 'cookie-policy',
            'cookie_consent_learn_more_text' => 'Cookie Policy',
            'number_of_products_per_page' => 24,
            'number_of_cross_sale_product' => 6,
            'ecommerce_products_page_layout' => 'left_sidebar',
            'ecommerce_product_item_style' => 1,
            'logo_in_the_checkout_page' => $this->filePath('general/logo.png', 'main'),
            'logo_in_invoices' => $this->filePath('general/logo.png', 'main'),
            'logo_vendor_dashboard' => $this->filePath('general/logo.png', 'main'),
            '404_page_image' => $this->filePath('general/404.png', 'main'),
            'newsletter_popup_enable' => true,
            'newsletter_popup_image' => $this->filePath('general/newsletter-popup.png', 'main'),
            'newsletter_popup_title' => 'Subscribe Now',
            'newsletter_popup_subtitle' => 'Newsletter',
            'newsletter_popup_description' => 'Subscribe to our newsletter and get 10% off your first purchase',
            'lazy_load_images' => true,
            'lazy_load_placeholder_image' => $this->filePath('general/placeholder.png', 'main'),
            'breadcrumb_background_image' => $this->filePath('general/breadcrumb.jpg', 'main'),
            'section_title_shape_decorated' => 'style-3',
            'social_links' => ThemeSupport::getDefaultSocialLinksData(),
            'social_sharing' => ThemeSupport::getDefaultSocialSharingData(),
        ];
    }
}
