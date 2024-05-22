<?php

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Gallery\Models\Gallery as GalleryModel;
use Botble\Gallery\Models\GalleryMeta;
use Botble\Theme\Facades\Theme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

if (! function_exists('gallery_meta_data')) {
    function gallery_meta_data(Model $object, array $select = ['gallery_meta.id', 'gallery_meta.images']): array
    {
        $meta = GalleryMeta::query()
            ->where([
                'reference_id' => $object->getKey(),
                'reference_type' => get_class($object),
            ])
            ->select($select)
            ->first();

        if (! empty($meta)) {
            $images = $meta->images;
            if (is_string($images)) {
                $images = json_decode($images, true);
            }

            return $images ? (array) $images : [];
        }

        return [];
    }
}

if (! function_exists('get_galleries')) {
    function get_galleries(int $limit = 8, array $with = ['slugable', 'user']): Collection
    {
        return GalleryModel::query()
            ->with($with)
            ->where([
                'status' => BaseStatusEnum::PUBLISHED,
                'is_featured' => 1,
            ])
            ->select([
                'id',
                'name',
                'user_id',
                'image',
                'created_at',
            ])
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}

if (! function_exists('render_galleries')) {
    function render_galleries(int $limit): string
    {
        $galleries = get_galleries($limit);

        $view = apply_filters('galleries_box_template_view', 'plugins/gallery::shortcodes.gallery');

        return view($view, compact('galleries'))->render();
    }
}

if (! function_exists('get_list_galleries')) {
    function get_list_galleries(array $condition): Collection
    {
        return GalleryModel::query()
            ->where($condition)
            ->with(['slugable', 'user'])
            ->get();
    }
}

if (! function_exists('render_object_gallery')) {
    function render_object_gallery(array $galleries, ?string $category = null): string
    {
        Theme::asset()
            ->container('footer')
            ->usePath(false)
            ->add(
                'owl.carousel',
                asset('vendor/core/plugins/gallery/libraries/owl-carousel/owl.carousel.css'),
                [],
                [],
                '1.0.0'
            )
            ->add('object-gallery-css', asset('vendor/core/plugins/gallery/css/object-gallery.css'), [], [], '1.0.0')
            ->add(
                'carousel',
                asset('vendor/core/plugins/gallery/libraries/owl-carousel/owl.carousel.js'),
                ['jquery'],
                [],
                '1.0.0'
            )
            ->add(
                'object-gallery-js',
                asset('vendor/core/plugins/gallery/js/object-gallery.js'),
                ['jquery'],
                [],
                '1.0.0'
            );

        return view('plugins/gallery::partials.object-gallery', compact('galleries', 'category'))->render();
    }
}
