<?php

namespace Database\Seeders\Themes\Main;

use Botble\ACL\Database\Seeders\UserSeeder as MainUserSeeder;

class UserSeeder extends MainUserSeeder
{
    public function run(): void
    {
        $this->uploadFiles('main/users');

        parent::run();
    }
}
