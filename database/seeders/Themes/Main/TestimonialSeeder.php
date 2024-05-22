<?php

namespace Database\Seeders\Themes\Main;

use Botble\Testimonial\Models\Testimonial;
use Botble\Theme\Database\Seeders\ThemeSeeder;

class TestimonialSeeder extends ThemeSeeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'James Dopli',
                'company' => 'Developer',
                'content' => 'Thanks for all your efforts and teamwork over the last several months!  Thank you so much',
            ],
            [
                'name' => 'Theodore Handle',
                'company' => 'CO Founder',
                'content' => 'How you use the city or town name is up to you. All results may be freely used in any work.',
            ],
            [
                'name' => 'Shahnewaz Sakil',
                'company' => 'UI/UX Designer',
                'content' => 'Very happy with our choice to take our daughter to Brave care. The entire team was great! Thank you!',
            ],
            [
                'name' => 'Albert Flores',
                'company' => 'Bank of America',
                'content' => 'Wedding day savior! 5 stars. Their bridal collection is a game-changer. Made me feel like a star.',
            ],
        ];

        Testimonial::query()->truncate();

        $files = $this->getFilesFromPath('main/users');

        foreach ($testimonials as $item) {
            Testimonial::query()->create([
                ...$item,
                'image' => $files->unique()->random(),
            ]);
        }
    }
}
