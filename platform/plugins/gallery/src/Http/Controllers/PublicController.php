<?php

namespace Botble\Gallery\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Gallery\Facades\Gallery;
use Botble\Gallery\Models\Gallery as GalleryModel;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;

class PublicController extends BaseController
{
    public function getGalleries()
    {
        $galleries = GalleryModel::query()
            ->wherePublished()
            ->with(['slugable', 'user'])
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->get();

        SeoHelper::setTitle(__('Galleries'));

        Theme::breadcrumb()->add(__('Galleries'), Gallery::getGalleriesPageUrl());

        return Theme::scope('galleries', compact('galleries'), 'plugins/gallery::themes.galleries')
            ->render();
    }
}
