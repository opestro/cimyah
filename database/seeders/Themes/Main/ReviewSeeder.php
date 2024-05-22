<?php

namespace Database\Seeders\Themes\Main;

use Botble\Ecommerce\Database\Seeders\ReviewSeeder as BaseReviewSeeder;
use Illuminate\Support\Collection;

class ReviewSeeder extends BaseReviewSeeder
{
    protected function getFilesFromPath(string $path): Collection
    {
        return parent::getFilesFromPath("main/$path");
    }
}
