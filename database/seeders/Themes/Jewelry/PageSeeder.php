<?php

namespace Database\Seeders\Themes\Jewelry;

use Database\Seeders\Themes\Main\PageSeeder as MainPageSeeder;

class PageSeeder extends MainPageSeeder
{
    protected function getData(): array
    {
        $this->uploadFiles('brands', 'main');

        return [
            [
                'name' => 'Home',
                'content' => '[simple-slider style="4" key="home-slider" customize_font_family_of_description="1" font_family_of_description="Charm" shape_1="fashion/sliders/shape-1.png" shape_2="fashion/sliders/shape-2.png" shape_3="fashion/sliders/shape-3.png" is_autoplay="yes" autoplay_speed="5000"][/simple-slider]' .
                    '[site-features style="3" quantity="4" title_1="Free Delivery" description_1="Orders from all item" icon_1="ti ti-truck-delivery" title_2="Return & Refund" description_2="Money-back guarantee" icon_2="ti ti-currency-dollar" title_3="Member Discount" description_3="Every order over $140.00" icon_3="ti ti-discount-2" title_4="Support 24/7" description_4="Contact us 24 hours a day" icon_4="ti ti-headset" enable_lazy_loading="yes"][/site-features]' .
                    '[ads style="3" key_1="UROL9F9ZZVAA" key_2="B30VDBKO7SBF" key_3="BN3ZCHLIE95I" key_4="QGPRRJ2MPZYA" enable_lazy_loading="yes"][/ads]' .
                    '[about image_1="main/general/about-1.jpg" image_2="main/general/about-2.jpg" subtitle="Unity Collection" title="Shop our limited Edition Collaborations" description="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras vel mi quam. Fusce vehicula vitae mauris sit amet tempor. Donec consectetur lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna." action_label="Contact Us" action_url="/contact" enable_lazy_loading="yes"][/about]' .
                    '[ecommerce-products style="slider-full-width" title="This Week\'s Featured" subtitle="Shop by Category" collection_ids="1" limit="5" with_sidebar="on" background_color="#EFF1F5" enable_lazy_loading="yes"][/ecommerce-products]' .
                    '[ecommerce-product-groups title="Discover our Products" subtitle="Product Collection" limit="8" tabs="all,featured,on-sale,trending,top-rated" enable_lazy_loading="yes"][/ecommerce-product-groups]' .
                    '[ecommerce-products style="slider" title="Top Sellers In Dress for You" subtitle="Best Seller This Week’s" by="collection" collection_ids="2" limit="5" enable_lazy_loading="yes"][/ecommerce-products]' .
                    '[image-slider type="custom" quantity="4" name_1="Brandit" image_1="main/brands/1.png" url_1="https://brandit-wear.com" name_2="Vintage" image_2="main/brands/2.png" url_2="https://vintagebrand.com/" name_3="Showtime" image_3="main/brands/3.png" url_3="https://www.showtime.com/" name_4="Classic Design Studio" image_4="main/brands/5.png" url_4="http://www.classicdesignstudios.com/" enable_lazy_loading="yes"][/image-slider]' .
                    '[gallery style="2" title="Trends on image feed" subtitle="After many months design and development of a modern online retailer" limit="6" enable_lazy_loading="yes"][/gallery]',
                'template' => 'full-width',
                'metadata' => [
                    'breadcrumb_style' => 'none',
                ],
            ],
            [
                'name' => 'Product Categories',
                'content' => '[ads style="3" key_1="UROL9F9ZZVAA" key_2="B30VDBKO7SBF" enable_lazy_loading="yes"][/ads]' .
                    '<p>&nbsp;</p>' .
                    '[ecommerce-categories category_ids="11,14,17,18,21,22,23,25,38" style="grid" enable_lazy_loading="yes"][/ecommerce-categories]' .
                    '<p>&nbsp;</p>' .
                    '<p>&nbsp;</p>',
                'template' => 'full-width',
                'metadata' => [
                    'breadcrumb_style' => 'align-start',
                ],
            ],
        ];
    }
}
