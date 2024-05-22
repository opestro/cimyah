<?php

namespace Database\Seeders\Themes\Main;

use Botble\Ecommerce\Models\Brand;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Theme\Database\Seeders\ThemeSeeder;
use Botble\Widget\Database\Traits\HasWidgetSeeder;
use Botble\Widget\Models\Widget as WidgetModel;

class WidgetSeeder extends ThemeSeeder
{
    use HasWidgetSeeder;

    public function run(): void
    {
        WidgetModel::query()->truncate();

        $this->createWidgets($this->getData());
    }

    protected function getData(): array
    {
        return [
            [
                'widget_id' => 'SiteInfoWidget',
                'sidebar_id' => 'footer_primary_sidebar',
                'position' => 1,
                'data' => [
                    'id' => 'SiteInfoWidget',
                    'logo' => $this->filePath('general/logo.png', 'main'),
                    'logo_height' => 50,
                    'about' => 'Shofy is a powerful tool eCommerce Laravel script for creating a professional and visually appealing online store.',
                    'show_social_links' => true,
                ],
            ],
            [
                'widget_id' => 'CustomMenuWidget',
                'sidebar_id' => 'footer_primary_sidebar',
                'position' => 2,
                'data' => [
                    'id' => 'CustomMenuWidget',
                    'name' => 'My Account',
                    'menu_id' => 'my-account',
                ],
            ],
            [
                'widget_id' => 'CustomMenuWidget',
                'sidebar_id' => 'footer_primary_sidebar',
                'position' => 3,
                'data' => [
                    'id' => 'CustomMenuWidget',
                    'name' => 'Information',
                    'menu_id' => 'information',
                ],
            ],
            [
                'widget_id' => 'SiteContactWidget',
                'sidebar_id' => 'footer_primary_sidebar',
                'position' => 4,
                'data' => [
                    'id' => 'SiteContactWidget',
                    'name' => 'Talk To Us',
                    'phone_label' => 'Got Questions? Call us',
                    'phone' => '+670 413 90 762',
                    'email' => 'support@shofy.com',
                    'address' => '79 Sleepy Hollow St. Jamaica, New York 1432',
                ],
            ],
            [
                'widget_id' => 'ProductCategoriesWidget',
                'sidebar_id' => 'footer_primary_sidebar',
                'position' => 5,
                'data' => [
                    'id' => 'ProductCategoriesWidget',
                    'name' => 'Health & Beauty',
                    'categories' => [5, 6, 7, 8, 10, 11, 12],
                ],
            ],
            [
                'widget_id' => 'ProductCategoriesWidget',
                'sidebar_id' => 'footer_primary_sidebar',
                'position' => 7,
                'data' => [
                    'id' => 'ProductCategoriesWidget',
                    'name' => 'Electronics',
                    'style' => 'simple-text',
                    'categories' => [3, 4, 15, 18, 19, 20],
                ],
            ],
            [
                'widget_id' => 'ProductCategoriesWidget',
                'sidebar_id' => 'footer_primary_sidebar',
                'position' => 8,
                'data' => [
                    'id' => 'ProductCategoriesWidget',
                    'name' => 'Sweet Treats',
                    'categories' => [11, 12, 13, 14, 15, 16, 17],
                ],
            ],
            [
                'widget_id' => 'ProductCategoriesWidget',
                'sidebar_id' => 'footer_primary_sidebar',
                'position' => 9,
                'data' => [
                    'id' => 'ProductCategoriesWidget',
                    'name' => 'Fashion',
                    'categories' => [1, 2, 3, 4, 5, 6, 7, 8],
                ],
            ],
            [
                'widget_id' => 'NewsletterWidget',
                'sidebar_id' => 'footer_top_sidebar',
                'position' => 1,
                'data' => [
                    'id' => 'NewsletterWidget',
                    'title' => 'Subscribe our Newsletter',
                    'subtitle' => 'Sale 20% off all store',
                ],
            ],
            [
                'widget_id' => 'SiteCopyrightWidget',
                'sidebar_id' => 'footer_bottom_sidebar',
                'position' => 1,
                'data' => [
                    'id' => 'SiteCopyrightWidget',
                    'content' => '© %y% All rights Reserved.',
                ],
            ],
            [
                'widget_id' => 'SiteAcceptedPaymentsWidget',
                'sidebar_id' => 'footer_bottom_sidebar',
                'position' => 2,
                'data' => [
                    'id' => 'SiteAcceptedPaymentsWidget',
                    'name' => 'Accepted Payments',
                    'image' => $this->filePath('general/footer-pay.png', 'main'),
                    'url' => '#',
                ],
            ],
            [
                'widget_id' => 'BlogSearchWidget',
                'sidebar_id' => 'blog_sidebar',
                'position' => 1,
                'data' => [
                    'id' => 'BlogSearchWidget',
                ],
            ],
            [
                'widget_id' => 'BlogAboutMeWidget',
                'sidebar_id' => 'blog_sidebar',
                'position' => 2,
                'data' => [
                    'id' => 'BlogAboutMeWidget',
                    'name' => 'About Me',
                    'author_url' => '/blog',
                    'author_avatar' => $this->filePath(sprintf('users/%d.jpg', rand(1, 10)), 'main'),
                    'author_name' => "Ravi O'Leigh",
                    'author_role' => 'Photographer & Blogger',
                    'author_description' => 'Lorem ligula eget dolor. Aenean massa. Cum sociis que penatibus magnis dis parturient',
                    'author_signature' => $this->filePath('general/signature.png', 'main'),
                ],
            ],
            [
                'widget_id' => 'BlogPostsWidget',
                'sidebar_id' => 'blog_sidebar',
                'position' => 3,
                'data' => [
                    'id' => 'BlogPostsWidget',
                    'name' => 'Latest Posts',
                    'limit' => 3,
                ],
            ],
            [
                'widget_id' => 'BlogCategoriesWidget',
                'sidebar_id' => 'blog_sidebar',
                'position' => 4,
                'data' => [
                    'id' => 'BlogCategoriesWidget',
                    'name' => 'Categories',
                    'number_display' => 6,
                ],
            ],
            [
                'widget_id' => 'BlogTagsWidget',
                'sidebar_id' => 'blog_sidebar',
                'position' => 5,
                'data' => [
                    'id' => 'BlogTagsWidget',
                    'name' => 'Popular Tags',
                    'number_display' => 6,
                ],
            ],
            [
                'widget_id' => 'ProductDetailInfoWidget',
                'sidebar_id' => 'product_details_sidebar',
                'position' => 1,
                'data' => [
                    'id' => 'ProductDetailInfoWidget',
                    'messages' => [
                        [
                            ['key' => 'message', 'value' => '30 days easy returns'],
                        ],
                        [
                            ['key' => 'message', 'value' => 'Order yours before 2.30pm for same day dispatch'],
                        ],
                    ],
                    'description' => 'Guaranteed safe & secure checkout',
                    'image' => $this->filePath('general/footer-pay.png', 'main'),
                ],
            ],
            [
                'widget_id' => 'EcommerceBrands',
                'sidebar_id' => 'products_listing_top_sidebar',
                'position' => 1,
                'data' => [
                    'id' => 'EcommerceBrands',
                    'brand_ids' => Brand::query()->pluck('id')->all(),
                ],
            ],
            [
                'widget_id' => 'ProductCategoriesWidget',
                'sidebar_id' => 'products_listing_top_sidebar',
                'position' => 2,
                'data' => [
                    'id' => 'ProductCategoriesWidget',
                    'categories' => ProductCategory::query()->whereNotNull('image')->where('is_featured', true)->pluck('id')->all(),
                    'style' => $this->getThemeName() === 'main' ? 'slider' : 'grid',
                    'display_children' => ! ($this->getThemeName() === 'main'),
                ],
            ],
        ];
    }
}
