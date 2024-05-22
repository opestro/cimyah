<?php

namespace Database\Seeders\Themes\Main;

use Botble\CookieConsent\Database\Traits\HasCookieConsentSeeder;
use Botble\Page\Database\Traits\HasPageSeeder;
use Botble\Theme\Database\Seeders\ThemeSeeder;
use Illuminate\Support\Facades\File;

class PageSeeder extends ThemeSeeder
{
    use HasCookieConsentSeeder;
    use HasPageSeeder;

    public function run(): void
    {
        $this->truncatePages();

        $this->uploadFiles('contact', 'main');

        $this->createPages([
            ...$this->getData(),
            [
                'name' => 'Coupons',
                'content' => '[ecommerce-coupons coupon_ids="1,2,3,4,5,6" enable_lazy_loading="yes"][/ecommerce-coupons]',
                'template' => 'full-width',
            ],
            [
                'name' => 'Blog',
                'template' => 'full-width',
            ],
            [
                'name' => 'Contact',
                'content' => htmlentities('[contact-form show_contact_form="1" title="Sent A Message" quantity="2" icon_1="' . $this->filePath('contact/icon-1.png', 'main') . '" content_1="contact@shofy.com <br> <strong>+670 413 90 762</strong>" icon_2="' . $this->filePath('contact/icon-2.png', 'main') . '" content_2="502 New St, Brighton VIC 3186, Australia" show_social_info="1" social_info_label="Find on social media" social_info_icon="' . $this->filePath('contact/icon-3.png', 'main') . '"][/contact-form]') .
                    '[google-map]502 New Street, Brighton VIC, Australia[/google-map]',
                'template' => 'full-width',
            ],
            [
                'name' => 'FAQs',
                'content' => '[faqs style="group" title="Frequently Ask Questions" description="Below are frequently asked questions, you may find the answer for yourself." category_ids="1,2,3" expand_first_time="1"][/faqs]',
                'metadata' => [
                    'breadcrumb_style' => 'align-center',
            ],
            ],
            [
                'name' => $this->getCookieConsentPageName(),
                'content' => $this->getCookieConsentPageContent(),
            ],
            [
                'name' => 'Our Story',
                'content' => File::get(database_path('seeders/contents/our-story.html')),
            ],
            [
                'name' => 'Careers',
                'content' => File::get(database_path('seeders/contents/careers.html')),
            ],
            [
                'name' => 'Shipping',
                'content' => File::get(database_path('seeders/contents/shipping.html')),
            ],
        ]);
    }

    protected function getData(): array
    {
        $this->uploadFiles('contact');

        return [
            [
                'name' => 'Home',
                'content' => '[simple-slider style="1" key="home-slider" customize_font_family_of_description="1" font_family_of_description="Oregano" shape_1="main/sliders/shape-1.png" shape_2="main/sliders/shape-2.png" shape_3="main/sliders/shape-3.png" shape_4="main/sliders/shape-4.png" is_autoplay="yes" autoplay_speed="5000"][/simple-slider]' .
                    '[ecommerce-categories style="slider" category_ids="6,10,13,16,30" enable_lazy_loading="yes"][/ecommerce-categories]' .
                    '[site-features style="1" quantity="4" title_1="Free Delivery" description_1="Orders from all item" icon_1="ti ti-truck-delivery" title_2="Return & Refund" description_2="Money-back guarantee" icon_2="ti ti-currency-dollar" title_3="Member Discount" description_3="Every order over $140.00" icon_3="ti ti-discount-2" title_4="Support 24/7" description_4="Contact us 24 hours a day" icon_4="ti ti-headset" enable_lazy_loading="yes"][/site-features]' .
                    '[ecommerce-product-groups title="Trending Products" limit="8" tabs="all,featured,on-sale,trending,top-rated" enable_lazy_loading="yes"][/ecommerce-product-groups]' .
                    '[ads style="1" key_1="UROL9F9ZZVAA" key_2="B30VDBKO7SBF" enable_lazy_loading="yes"][/ads]' .
                    '[ecommerce-flash-sale style="1" title="Deal of The Day" flash_sale_id="1" limit="4" button_label="View All Deals" button_url="/products" enable_lazy_loading="yes"][/ecommerce-flash-sale]' .
                    '[ecommerce-products style="grid" category_ids="20" limit="12" with_sidebar="on" image="main/gadgets/gadget-girl.png" action_label="More Products" ads_ids="3,4" enable_lazy_loading="yes"][/ecommerce-products]' .
                    '[ads style="4" key_1="B5ZA76ZWMWAE" key_2="F1LTQS976YPY" key_3="IHPZ2WBSYJUK" enable_lazy_loading="yes"][/ads]' .
                    '[ecommerce-products style="slider" title="New Arrivals" by="collection" collection_ids="1" limit="12" enable_lazy_loading="yes"][/ecommerce-products]' .
                    '[ecommerce-product-groups style="columns" limit="3" tabs="on-sale,trending,top-rated" enable_lazy_loading="yes"][/ecommerce-product-groups]' .
                    '[blog-posts title="Latest news & articles" type="latest" limit="3" button_label="View All" button_url="/blog" enable_lazy_loading="yes"][/blog-posts]' .
                    '[gallery style="1" limit="5" enable_lazy_loading="yes"][/gallery]',
                'template' => 'full-width',
                'metadata' => [
                    'breadcrumb_style' => 'none',
                ],
            ],
            [
                'name' => 'Product Categories',
                'content' => '[ads style="1" key_1="UROL9F9ZZVAA" key_2="B30VDBKO7SBF" enable_lazy_loading="yes"][/ads]' .
                    '<p>&nbsp;</p>' .
                    '[ecommerce-categories category_ids="11,14,17,18,21,22,23,25,38" style="grid" enable_lazy_loading="yes"][/ecommerce-categories]' .
                    '<p>&nbsp;</p>' .
                    '<p>&nbsp;</p>',
                'template' => 'full-width',
            ],
        ];
    }
}
