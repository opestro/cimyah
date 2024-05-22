<?php

namespace Botble\Gallery\Services;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Gallery\Facades\Gallery;
use Botble\Gallery\Models\Gallery as GalleryModel;
use Botble\Media\Facades\RvMedia;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\SeoHelper\SeoOpenGraph;
use Botble\Slug\Models\Slug;
use Botble\Theme\Facades\Theme;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class GalleryService
{
    public function handleFrontRoutes(Slug|array $slug): Slug|array|Builder
    {
        if (! $slug instanceof Slug) {
            return $slug;
        }

        $condition = [
            'id' => $slug->reference_id,
            'status' => BaseStatusEnum::PUBLISHED,
        ];

        if (Auth::guard()->check() && request()->input('preview')) {
            Arr::forget($condition, 'status');
        }

        if ($slug->reference_type !== GalleryModel::class) {
            return $slug;
        }

        $gallery = GalleryModel::query()
            ->where($condition)
            ->with(['slugable'])
            ->firstOrFail();

        SeoHelper::setTitle($gallery->name)
            ->setDescription($gallery->description);

        $meta = new SeoOpenGraph();
        $meta->setDescription($gallery->description);
        $meta->setUrl($gallery->url);
        $meta->setTitle($gallery->name);
        $meta->setType('article');

        SeoHelper::setSeoOpenGraph($meta);

        SeoHelper::meta()->setUrl($gallery->url);

        if ($gallery->image) {
            $meta->setImage(RvMedia::getImageUrl($gallery->image));
        }

        Gallery::registerAssets();

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, GALLERY_MODULE_SCREEN_NAME, $gallery);

        Theme::breadcrumb()
            ->add(__('Galleries'), Gallery::getGalleriesPageUrl())
            ->add($gallery->name, $gallery->url);

        if (function_exists('admin_bar')) {
            admin_bar()
                ->registerLink(trans('plugins/gallery::gallery.edit_this_gallery'), route('galleries.edit', $gallery->getKey()), null, 'galleries.edit');
        }

        return [
            'view' => 'gallery',
            'default_view' => 'plugins/gallery::themes.gallery',
            'data' => compact('gallery'),
            'slug' => $gallery->slug,
        ];
    }
}
