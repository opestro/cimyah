<?php

namespace Database\Seeders\Themes\Fashion;

use Botble\Ecommerce\Database\Seeders\ReviewSeeder as BaseReviewSeeder;
use Illuminate\Support\Collection;

class ReviewSeeder extends BaseReviewSeeder
{
    protected function getFilesFromPath(string $path): Collection
    {
        return parent::getFilesFromPath("fashion/$path");
    }
}
