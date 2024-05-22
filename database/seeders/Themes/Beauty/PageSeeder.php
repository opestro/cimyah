<?php

namespace Database\Seeders\Themes\Beauty;

use Database\Seeders\Themes\Main\PageSeeder as MainPageSeeder;

class PageSeeder extends MainPageSeeder
{
    protected function getData(): array
    {
        return [
            [
                'name' => 'Home',
                'content' => '[simple-slider customize_font_family_of_description="1" font_family_of_description="Charm" style="3" key="home-slider"][/simple-slider]' .
                    '[ecommerce-categories style="grid" category_ids="1,2,7,17" background_color="#F3F5F7" title="Discover our products" subtitle="Product Collection" button_label="Shop All Products" button_url="/products" enable_lazy_loading="yes"][/ecommerce-categories]' .
                    '[ecommerce-products style="simple" by="specify" product_ids="2,39,41" enable_lazy_loading="yes"][/ecommerce-products]' .
                    '[ecommerce-products style="grid" title="Best sellers in beauty" subtitle="Shop by Category" by="category" category_ids="2,3,4" limit="8" background_color="rgb(234, 228, 222)" button_label="Shop All Products" button_url="/products" enable_lazy_loading="yes"][/ecommerce-products]' .
                    '[ecommerce-product-groups title="Enjoy the best quality" subtitle="Best Seller This Week’s" limit="8" tabs="all,featured,on-sale,trending,top-rated" enable_lazy_loading="yes"][/ecommerce-product-groups]' .
                    '[testimonials style="2" title="What our Clients say" subtitle="Customers Review" testimonial_ids="1,2,3,4" enable_lazy_loading="yes"][/testimonials]' .
                    '[site-features style="2" quantity="4" title_1="Free Delivery" description_1="Orders from all item" icon_1="ti ti-truck-delivery" title_2="Return & Refund" description_2="Money-back guarantee" icon_2="ti ti-currency-dollar" title_3="Member Discount" description_3="Every order over $140.00" icon_3="ti ti-discount-2" title_4="Support 24/7" description_4="Contact us 24 hours a day" icon_4="ti ti-headset" enable_lazy_loading="yes"][/site-features]' .
                    '[gallery style="2" limit="6" enable_lazy_loading="yes"][/gallery]',
                'template' => 'full-width',
                'metadata' => [
                    'breadcrumb_style' => 'none',
                ],
            ],
            [
                'name' => 'Product Categories',
                'content' => '[ads style="2" key_1="UROL9F9ZZVAA" key_2="B30VDBKO7SBF" enable_lazy_loading="yes"][/ads]' .
                    '<p>&nbsp;</p>' .
                    '[ecommerce-categories category_ids="11,14,17,18,21,22,23,25,38" style="grid" enable_lazy_loading="yes"][/ecommerce-categories]' .
                    '<p>&nbsp;</p>' .
                    '<p>&nbsp;</p>',
                'template' => 'full-width',
                'metadata' => [
                    'breadcrumb_style' => 'align-start',
                ],
            ],
            [
                'name' => 'Coupons',
                'content' => '[ecommerce-coupons coupon_ids="1,2,3,4,5,6" enable_lazy_loading="yes"][/ecommerce-coupons]',
                'template' => 'full-width',
            ],
        ];
    }
}
