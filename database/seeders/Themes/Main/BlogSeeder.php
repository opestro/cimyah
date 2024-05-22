<?php

namespace Database\Seeders\Themes\Main;

use Botble\Blog\Database\Traits\HasBlogSeeder;
use Botble\Theme\Database\Seeders\ThemeSeeder;
use Illuminate\Support\Facades\File;

class BlogSeeder extends ThemeSeeder
{
    use HasBlogSeeder;

    public function run(): void
    {
        $this->uploadFiles('blog');
        $this->uploadFiles('users');

        $this->createBlogCategories(array_map(fn ($category) => ['name' => $category], [
            'Crisp Bread & Cake',
            'Fashion',
            'Electronic',
            'Commercial',
            'Organic Fruits',
            'Ecological',
        ]));

        $this->createBlogTags(array_map(fn ($tag) => ['name' => $tag], [
            'General',
            'Design',
            'Fashion',
            'Branding',
            'Modern',
            'Nature',
            'Vintage',
            'Sunglasses',
        ]));

        $this->createBlogPosts(array_map(function ($post) {
            return [
                'name' => $post,
                'content' => File::get(database_path('seeders/contents/post.html')),
                'image' => $this->filePath(sprintf('blog/post-%s.jpg', $this->faker->numberBetween(1, 12))),
            ];
        }, [
            '4 Expert Tips On How To Choose The Right Men’s Wallet',
            'Sexy Clutches: How to Buy & Wear a Designer Clutch Bag',
            'The Top 2020 Handbag Trends to Know',
            'How to Match the Color of Your Handbag With an Outfit',
            'How to Care for Leather Bags',
            "We're Crushing Hard on Summer's 10 Biggest Bag Trends",
            'Essential Qualities of Highly Successful Music',
            '9 Things I Love About Shaving My Head',
            'Why Teamwork Really Makes The Dream Work',
            'The World Caters to Average People',
            'The litigants on the screen are not actors',
            'Hiring the Right Sales Team at the Right Time',
            'Fully Embrace the Return of 90s fashion',
            'Exploring the English Countryside',
            'Here’s the First Valentino’s New Makeup Collection',
            'Follow Your own Design process, whatever gets',
            'Freelancer Days 2024, What’s new?',
            'Quality Foods Requirments For Every Human Body’s',
        ]));
    }
}
