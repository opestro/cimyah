<?php

namespace Botble\Location\Repositories\Eloquent;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Language\Facades\Language;
use Botble\Location\Repositories\Interfaces\CityInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;

class CityRepository extends RepositoriesAbstract implements CityInterface
{
    public function filters(?string $keyword, ?int $limit = 10, array $with = [], array $select = ['cities.*']): Collection
    {
        $data = $this->model
            ->wherePublished('cities.status')
            ->leftJoin('states', function (JoinClause $query) {
                $query
                    ->on('states.id', '=', 'cities.state_id')
                    ->where('states.status', BaseStatusEnum::PUBLISHED);
            })
            ->join('countries', function (JoinClause $query) {
                $query
                    ->on('countries.id', '=', 'cities.country_id')
                    ->where('countries.status', BaseStatusEnum::PUBLISHED);
            })
            ->when($keyword, function (Builder $query) use ($keyword) {
                $keyword = '%' . $keyword . '%';

                $query->where(function (Builder $query) use ($keyword) {
                    $query
                        ->where(function (Builder $query) use ($keyword) {
                            return $query
                                ->where('cities.name', 'LIKE', $keyword)
                                ->orWhere('states.name', 'LIKE', $keyword);
                        })
                        ->when(
                            is_plugin_active('language')
                            && is_plugin_active('language-advanced')
                            && Language::getCurrentLocale() != Language::getDefaultLocale(),
                            function (Builder $query) use (
                                $keyword
                            ) {
                                $query
                                    ->orWhere(function (Builder $query) use ($keyword) {
                                        return $query
                                            ->whereHas('translations', function ($query) use ($keyword) {
                                                $query->where('name', 'LIKE', $keyword);
                                            })
                                            ->orWhereHas('state.translations', function ($query) use ($keyword) {
                                                $query->where('name', 'LIKE', $keyword);
                                            });
                                    });
                            }
                        );
                });
            });

        if ($limit) {
            $data = $data->limit($limit);
        }

        if ($with) {
            $data = $data->with($with);
        }

        return $this->applyBeforeExecuteQuery($data)->get($select);
    }
}
