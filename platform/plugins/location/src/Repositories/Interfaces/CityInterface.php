<?php

namespace Botble\Location\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface CityInterface extends RepositoryInterface
{
    public function filters(?string $keyword, ?int $limit = 10, array $with = [], array $select = ['cities.*']): Collection;
}
