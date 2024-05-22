<?php

namespace Database\Seeders\Themes\Main;

use Botble\Contact\Enums\ContactStatusEnum;
use Botble\Contact\Models\Contact;
use Botble\Theme\Database\Seeders\ThemeSeeder;

class ContactSeeder extends ThemeSeeder
{
    public function run(): void
    {
        Contact::query()->truncate();

        $faker = fake();

        for ($i = 0; $i < 10; $i++) {
            Contact::query()->create([
                'name' => $faker->name(),
                'email' => $faker->safeEmail(),
                'phone' => $faker->phoneNumber(),
                'address' => $faker->address(),
                'subject' => $faker->text(50),
                'content' => $faker->text(500),
                'status' => $faker->randomElement([ContactStatusEnum::READ, ContactStatusEnum::UNREAD]),
            ]);
        }
    }
}
