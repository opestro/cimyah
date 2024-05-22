<?php

namespace Botble\Location\Tables;

use Botble\Base\Facades\Html;
use Botble\Location\Models\City;
use Botble\Location\Models\Country;
use Botble\Location\Models\State;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\SelectBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class CityTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(City::class)
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route('city.edit'),
                Column::make('state_id')
                    ->title(trans('plugins/location::city.state'))
                    ->alignStart(),
                Column::make('country_id')
                    ->title(trans('plugins/location::city.country'))
                    ->alignStart(),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('city.create'))
            ->addActions([
                EditAction::make()->route('city.edit'),
                DeleteAction::make()->route('city.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('city.destroy'))
            ->addBulkChanges([
                NameBulkChange::make(),
                SelectBulkChange::make()
                    ->name('state_id')
                    ->title(trans('plugins/location::city.state'))
                    ->searchable()
                    ->choices(fn () => State::query()->pluck('name', 'id')->all()),
                SelectBulkChange::make()
                    ->name('country_id')
                    ->title(trans('plugins/location::city.country'))
                    ->searchable()
                    ->choices(fn () => Country::query()->pluck('name', 'id')->all()),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'name',
                        'state_id',
                        'country_id',
                        'created_at',
                        'status',
                    ]);
            });
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('state_id', function (City $item) {
                if (! $item->state_id || ! $item->state->name) {
                    return '&mdash;';
                }

                return Html::link(route('state.edit', $item->state_id), $item->state->name);
            })
            ->editColumn('country_id', function (City $item) {
                if (! $item->country_id || ! $item->country->name) {
                    return '&mdash;';
                }

                return Html::link(route('country.edit', $item->country_id), $item->country->name);
            })
            ->filter(function (Builder $query) {
                $keyword = $this->request->input('search.value');

                if (! $keyword) {
                    return $query;
                }

                return $query->where(function (Builder $query) use ($keyword) {
                    $query
                        ->where('id', $keyword)
                        ->orWhere('name', 'LIKE', '%' . $keyword . '%')
                        ->orWhereHas('state', function (Builder $subQuery) use ($keyword) {
                            return $subQuery
                                ->where('name', 'LIKE', '%' . $keyword . '%');
                        })
                        ->orWhereHas('country', function (Builder $subQuery) use ($keyword) {
                            return $subQuery
                                ->where('name', 'LIKE', '%' . $keyword . '%');
                        });
                });
            });

        return $this->toJson($data);
    }
}
