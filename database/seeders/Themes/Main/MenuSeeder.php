<?php

namespace Database\Seeders\Themes\Main;

use Botble\Blog\Models\Post;
use Botble\Ecommerce\Models\Product;
use Botble\Menu\Database\Traits\HasMenuSeeder;
use Botble\Page\Database\Traits\HasPageSeeder;
use Botble\Page\Models\Page;
use Botble\Theme\Database\Seeders\ThemeSeeder;

class MenuSeeder extends ThemeSeeder
{
    use HasMenuSeeder;
    use HasPageSeeder;

    public function run(): void
    {
        $this->createMenus($this->getData());
    }

    protected function getData(): array
    {
        return [
            [
                'name' => 'Main menu',
                'slug' => 'main-menu',
                'location' => 'main-menu',
                'items' => [
                    [
                        'title' => 'Home',
                        'reference_id' => 1,
                        'reference_type' => Page::class,
                        'children' => [
                            [
                                'title' => 'Electronics',
                                'url' => 'https://shofy.botble.com',
                            ],
                            [
                                'title' => 'Fashion',
                                'url' => 'https://shofy-fashion.botble.com',
                            ],
                            [
                                'title' => 'Beauty',
                                'url' => 'https://shofy-beauty.botble.com',
                            ],
                            [
                                'title' => 'Jewelry',
                                'url' => 'https://shofy-jewelry.botble.com',
                            ],
                            [
                                'title' => 'Grocery',
                                'url' => 'https://shofy-grocery.botble.com',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Shop',
                        'children' => [
                            [
                                'title' => 'Shop Categories',
                                'reference_id' => $this->getPageId('Product Categories'),
                                'reference_type' => Page::class,
                            ],
                            [
                                'title' => 'Shop List',
                                'url' => url('products?layout=list'),
                            ],
                            [
                                'title' => 'Shop Grid',
                                'url' => url('products?layout=grid'),
                            ],
                            [
                                'title' => 'Product Detail',
                                'url' => Product::query()->wherePublished()->where('is_variation', false)->inRandomOrder()->first()->url,
                            ],
                            [
                                'title' => 'Grab Coupons',
                                'reference_id' => $this->getPageId('Coupons'),
                                'reference_type' => Page::class,
                            ],
                            [
                                'title' => 'Cart',
                                'url' => url('cart'),
                            ],
                            [
                                'title' => 'Compare',
                                'url' => url('compare'),
                            ],
                            [
                                'title' => 'Wishlist',
                                'url' => url('wishlist'),
                            ],
                            [
                                'title' => 'Track Your Order',
                                'url' => url('orders/tracking'),
                            ],
                        ],
                    ],
                    [
                        'title' => 'Vendors',
                        'url' => url('stores'),
                    ],
                    [
                        'title' => 'Pages',
                        'children' => [
                            [
                                'title' => 'FAQs',
                                'reference_id' => $this->getPageId('FAQs'),
                                'reference_type' => Page::class,
                            ],
                            [
                                'title' => 'Login',
                                'url' => url('login'),
                            ],
                            [
                                'title' => 'Register',
                                'url' => url('register'),
                            ],
                            [
                                'title' => 'Forgot Password',
                                'url' => url('password/reset'),
                            ],
                            [
                                'title' => '404 Error',
                                'url' => url('404'),
                            ],
                        ],
                    ],
                    [
                        'title' => 'Blog',
                        'reference_id' => $this->getPageId('Blog'),
                        'reference_type' => Page::class,
                        'children' => [
                            [
                                'title' => 'Blog Grid',
                                'url' => url('blog?layout=grid'),
                            ],
                            [
                                'title' => 'Blog List',
                                'url' => url('blog?layout=list'),
                            ],
                            [
                                'title' => 'Blog Detail',
                                'url' => Post::query()->wherePublished()->inRandomOrder()->first()->url,
                            ],
                        ],
                    ],
                    [
                        'title' => 'Contact',
                        'reference_id' => $this->getPageId('Contact'),
                        'reference_type' => Page::class,
                    ],
                ],
            ],
            [
                'name' => 'My Account',
                'slug' => 'my-account',
                'items' => [
                    [
                        'title' => 'Track Orders',
                        'url' => url('orders/tracking'),
                    ],
                    [
                        'title' => 'Shipping',
                        'reference_id' => $this->getPageId('Shipping'),
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Wishlist',
                        'url' => url('wishlist'),
                    ],
                    [
                        'title' => 'My Account',
                        'url' => url('customer/overview'),
                    ],
                    [
                        'title' => 'Order History',
                        'url' => url('customer/orders'),
                    ],
                    [
                        'title' => 'Returns',
                        'url' => url('customer/order-returns'),
                    ],
                ],
            ],
            [
                'name' => 'Information',
                'slug' => 'information',
                'items' => [
                    [
                        'title' => 'Our Story',
                        'reference_id' => $this->getPageId('Our Story'),
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Careers',
                        'reference_id' => $this->getPageId('Careers'),
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Privacy Policy',
                        'reference_id' => $this->getPageId('Cookie Policy'),
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Latest News',
                        'url' => url('blog'),
                    ],
                    [
                        'title' => 'Contact Us',
                        'reference_id' => $this->getPageId('Contact'),
                        'reference_type' => Page::class,
                    ],
                ],
            ],
        ];
    }
}
