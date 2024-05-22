<?php

namespace Database\Seeders\Themes\Jewelry;

use Database\Seeders\Themes\Main\AnnouncementSeeder as MainAnnouncementSeeder;

class AnnouncementSeeder extends MainAnnouncementSeeder
{
    protected function getSettings(): array
    {
        return [
            ...parent::getSettings(),
            'announcement_max_width' => null,
            'announcement_text_color' => '#010f1c',
        ];
    }
}
