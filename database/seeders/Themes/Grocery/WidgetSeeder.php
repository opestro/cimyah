<?php

namespace Database\Seeders\Themes\Grocery;

use Database\Seeders\Themes\Main\WidgetSeeder as MainWidgetSeeder;

class WidgetSeeder extends MainWidgetSeeder
{
    protected function getData(): array
    {
        $data = parent::getData();

        unset($data[4]);

        return $data;
    }
}
