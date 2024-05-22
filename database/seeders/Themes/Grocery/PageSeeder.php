<?php

namespace Database\Seeders\Themes\Grocery;

use Database\Seeders\Themes\Main\PageSeeder as MainPageSeeder;

class PageSeeder extends MainPageSeeder
{
    protected function getData(): array
    {
        $this->uploadFiles('shapes');

        return [
            [
                'name' => 'Home',
                'content' => '[simple-slider customize_font_family_of_description="1" font_family_of_description="Charm" style="5" key="home-slider" shape_1="grocery/sliders/shape-1.png" shape_2="grocery/sliders/shape-2.png" shape_3="grocery/sliders/shape-3.png" shape_4="grocery/sliders/shape-4.png" is_autoplay="yes" autoplay_speed="5000"][/simple-slider]' .
                    '[ecommerce-categories category_ids="1,5,9,13,14,18" title="Popular on the Shofy store." subtitle="Shop by Category" enable_lazy_loading="yes"][/ecommerce-categories]' .
                    '[ecommerce-product-groups style="tabs" title="Trending Products" subtitle="All Product Shop" limit="8" tabs="all,featured,on-sale,trending,top-rated" enable_lazy_loading="yes"][/ecommerce-product-groups]' .
                    '[ecommerce-flash-sale style="2" title="Grab the best Offer Of this Week!" subtitle="Best Deals of the week!" flash_sale_id="1" limit="3" background_image="grocery/banners/3.png" enable_lazy_loading="yes"][/ecommerce-flash-sale]' .
                    '<p> </p>' .
                    '[ecommerce-product-groups style="columns" tabs="trending,top-rated" limit="3" ads="GA3K1VZWNMPF" enable_lazy_loading="yes"][/ecommerce-product-groups]' .
                    '[testimonials style="3" title="Our Happy Customers" subtitle="Customer Reviews" testimonial_ids="1,2,3,4" enable_lazy_loading="yes"][/testimonials]' .
                    '[ecommerce-products style="slider" title="Bestsellers of the week" subtitle=" More to Discover" category_ids="5" limit="5" with_sidebar="on" ads_ids="3,4" enable_lazy_loading="yes"][/ecommerce-products]' .
                    '[site-features style="4" quantity="4" title_1="Flexible Delivery" icon_1="ti ti-truck-delivery" title_2="100% Money Back" icon_2="ti ti-currency-dollar" title_3="Secure Payment" icon_3="ti ti-credit-card" title_4="24 Hour Support" icon_4="ti ti-headset" enable_lazy_loading="yes"][/site-features]' .
                    '[app-downloads title="Get the app & get Your Groceries from home" google_label="Google Play" google_icon="ti ti-brand-google-play" google_url="https://play.google.com/" apple_label="Apple Store" apple_icon="ti ti-brand-apple-filled" apple_url="https://apps.apple.com/" screenshot="main/general/cta-thumb-1.jpg" shape_image_left="main/general/cta-shape-1.png" shape_image_right="main/general/cta-shape-2.png" enable_lazy_loading="yes"][/app-downloads]',
                'template' => 'full-width',
                'metadata' => [
                    'breadcrumb_style' => 'none',
                ],
            ],
            [
                'name' => 'Product Categories',
                'content' => '[ads style="1" key_1="UROL9F9ZZVAA" key_2="B30VDBKO7SBF" enable_lazy_loading="yes"][/ads]' .
                    '[ecommerce-categories category_ids="1,5,9,13,14,18" style="grid" enable_lazy_loading="yes"][/ecommerce-categories]',
                'template' => 'full-width',
                'metadata' => [
                    'breadcrumb_style' => 'align-start',
                ],
            ],
        ];
    }
}
