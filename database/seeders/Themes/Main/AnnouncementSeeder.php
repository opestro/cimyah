<?php

namespace Database\Seeders\Themes\Main;

use ArchiElite\Announcement\Enums\AnnouncePlacement;
use ArchiElite\Announcement\Enums\TextAlignment;
use ArchiElite\Announcement\Models\Announcement;
use Botble\Setting\Facades\Setting;
use Botble\Theme\Database\Seeders\ThemeSeeder;

class AnnouncementSeeder extends ThemeSeeder
{
    public function run(): void
    {
        Announcement::query()->truncate();

        $now = $this->now();

        foreach ($this->getData() as $key => $value) {
            Announcement::query()->create([
                'name' => sprintf('Announcement %s', $key + 1),
                'content' => $value,
                'start_date' => $now,
                'dismissible' => true,
            ]);
        }

        Setting::set($this->getSettings())->save();
    }

    protected function getData(): array
    {
        return [
            'Enjoy free shipping on all orders over $99! Shop now and save on delivery costs.',
            'Need assistance? Our customer support is available 24/7 to help you with any questions or concerns.',
            'Shop with confidence! We offer a hassle-free 30 days return service for your peace of mind.',
        ];
    }

    protected function getSettings(): array
    {
        return [
            'announcement_max_width' => '1390',
            'announcement_text_color' => '#fff',
            'announcement_background_color' => 'transparent',
            'announcement_placement' => AnnouncePlacement::THEME,
            'announcement_text_alignment' => TextAlignment::START,
            'announcement_dismissible' => false,
        ];
    }
}
