<?php

namespace Botble\Gallery\Repositories\Eloquent;

use Botble\Gallery\Repositories\Interfaces\GalleryInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Support\Collection;

class GalleryRepository extends RepositoriesAbstract implements GalleryInterface
{
    public function getAll(array $with = ['slugable', 'user'], int $limit = 0): Collection
    {
        $data = $this->model
            ->with($with)
            ->wherePublished()
            ->orderBy('order')
            ->orderByDesc('created_at');

        if ($limit) {
            $data->limit($limit);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getDataSiteMap(): Collection
    {
        $data = $this->model
            ->with('slugable')
            ->wherePublished()
            ->orderBy('order')
            ->select(['id', 'name', 'updated_at'])
            ->orderByDesc('created_at');

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getFeaturedGalleries(int $limit, array $with = ['slugable', 'user']): Collection
    {
        $data = $this->model
            ->with($with)
            ->wherePublished()
            ->where('is_featured', true)
            ->select([
                'id',
                'name',
                'user_id',
                'image',
                'created_at',
            ])
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->limit($limit);

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
