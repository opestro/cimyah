<?php

namespace Database\Seeders\Themes\Fashion;

use Database\Seeders\Themes\Main\PageSeeder as MainPageSeeder;

class PageSeeder extends MainPageSeeder
{
    protected function getData(): array
    {
        return [
            [
                'name' => 'Home',
                'content' => '[simple-slider style="2" key="home-slider" shape_1="fashion/sliders/shape-1.png" shape_2="fashion/sliders/shape-2.png" shape_3="fashion/sliders/shape-3.png" is_autoplay="yes" autoplay_speed="5000"][/simple-slider]' .
                    '[ads style="2" key_1="WXAUTIJV1QU0" key_2="7Z5RXBBWV7J2" key_3="JY08TDO8FG1E" full_width="1" enable_lazy_loading="yes"][/ads]' .
                    '[ecommerce-categories style="slider" title="Popular on the Shofy store." subtitle="Shop by Category" category_ids="1,2,7,11,18,19" background_color="#F3F5F7" enable_lazy_loading="yes"][/ecommerce-categories]' .
                    '[ecommerce-product-groups title="Customer Favorite Style Product" subtitle="All Product Shop" limit="8" tabs="all,featured,on-sale,trending,top-rated" enable_lazy_loading="yes"][/ecommerce-product-groups]' .
                    '[ecommerce-products style="slider-full-width" title="This Week\'s Featured" subtitle="Shop by Category" collection_ids="1" limit="5" with_sidebar="on" background_color="#EFF1F5" enable_lazy_loading="yes"][/ecommerce-products]' .
                    '[ecommerce-products title="Trending Arrivals" subtitle="More to Discover" collection_ids="1" limit="5" with_sidebar="on" ads_ids="6" style="slider" ads="VKJNCBIBQC1O" enable_lazy_loading="yes"][/ecommerce-products]' .
                    '[ecommerce-products title="This Week\'s Featured" subtitle="Best Seller This Week\'s" by="specify" product_ids="3,4,5,6" limit="12" style="grid" button_label="Shop All Now" button_url="/products" enable_lazy_loading="yes"][/ecommerce-products]' .
                    '[testimonials style="1" title="The Review Are In" testimonial_ids="2,3,4" enable_lazy_loading="yes"][/testimonials]' .
                    '[blog-posts title="Latest News & Articles" subtitle="Our Blog & News" type="recent" limit="3" button_label="Discover More" button_url="/blog" enable_lazy_loading="yes"][/blog-posts]' .
                    '[site-features style="2" quantity="4" title_1="Free Delivery" description_1="Orders from all item" icon_1="ti ti-truck-delivery" title_2="Return & Refund" description_2="Money-back guarantee" icon_2="ti ti-currency-dollar" title_3="Member Discount" description_3="Every order over $140.00" icon_3="ti ti-discount-2" title_4="Support 24/7" description_4="Contact us 24 hours a day" icon_4="ti ti-headset" enable_lazy_loading="yes"][/site-features]' .
                    '[gallery style="2" limit="5" enable_lazy_loading="yes"][/gallery]',
                'template' => 'full-width',
                'metadata' => [
                    'breadcrumb_style' => 'none',
                ],
            ],
            [
                'name' => 'Product Categories',
                'content' => '[ads style="2" key_1="UROL9F9ZZVAA" key_2="B30VDBKO7SBF" enable_lazy_loading="yes"][/ads]' .
                    '[ecommerce-categories style="slider" title="Popular on the Shofy store." subtitle="Shop by Category" category_ids="1,2,7,11,18,19" background_color="#F3F5F7" enable_lazy_loading="yes"][/ecommerce-categories]',
                'template' => 'full-width',
                'metadata' => [
                    'breadcrumb_style' => 'align-start',
                ],
            ],
        ];
    }
}
