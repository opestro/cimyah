<?php

namespace Botble\Ads\Repositories\Eloquent;

use Botble\Ads\Repositories\Interfaces\AdsInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Eloquent\Collection;

class AdsRepository extends RepositoriesAbstract implements AdsInterface
{
    public function getAll(): Collection
    {
        // @phpstan-ignore-next-line
        $data = $this->model
            ->wherePublished()
            ->notExpired()
            ->with(['metadata']);

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
