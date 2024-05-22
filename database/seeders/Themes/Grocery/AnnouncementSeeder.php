<?php

namespace Database\Seeders\Themes\Grocery;

use Database\Seeders\Themes\Main\AnnouncementSeeder as MainAnnouncementSeeder;

class AnnouncementSeeder extends MainAnnouncementSeeder
{
    protected function getData(): array
    {
        return [
            '🍇 Fresh arrivals just in! Shop now for quality groceries and elevate your meals!',
            '🚀 Explore new flavors hassle-free. Enjoy free delivery on orders over $50!',
            '🌽 Quality guaranteed! Hassle-free returns within 30 days for your peace of mind.',
        ];
    }

    protected function getSettings(): array
    {
        return [
            ...parent::getSettings(),
            'announcement_max_width' => null,
            'announcement_text_color' => '#010f1c',
        ];
    }
}
