<?php

namespace Database\Seeders\Themes\Main;

use Botble\Gallery\Models\Gallery;
use Botble\Gallery\Models\GalleryMeta;
use Botble\Slug\Facades\SlugHelper;
use Botble\Theme\Database\Seeders\ThemeSeeder;

class GallerySeeder extends ThemeSeeder
{
    public function run(): void
    {
        $this->uploadFiles('galleries');

        Gallery::query()->truncate();
        GalleryMeta::query()->truncate();

        $images = [];

        $faker = $this->fake();

        foreach ($faker->randomElements(range(1, 5), rand(3, 5)) as $i) {
            $images[] = [
                'img' => $this->filePath("galleries/$i.jpg"),
                'description' => $faker->realText(),
            ];
        }

        foreach ($this->getData() as $index => $item) {
            $gallery = Gallery::query()->create([
                'user_id' => 1,
                'name' => $item,
                'description' => $faker->realText(),
                'image' => $this->filePath(sprintf('galleries/%d.jpg', $index + 1)),
                'is_featured' => true,
            ]);

            SlugHelper::createSlug($gallery);

            GalleryMeta::query()->create([
                'images' => $images,
                'reference_id' => $gallery->getKey(),
                'reference_type' => Gallery::class,
            ]);
        }
    }

    protected function getData(): array
    {
        return [
            'Perfect',
            'New Day',
            'Happy Day',
            'Nature',
            'Morning',
        ];
    }
}
