<?php

namespace Botble\Location\Tables;

use Botble\Location\Models\Country;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\BulkChanges\TextBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class CountryTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Country::class)
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route('country.edit'),
                Column::make('nationality')
                    ->title(trans('plugins/location::country.nationality'))
                    ->alignStart(),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('country.create'))
            ->addActions([
                EditAction::make()->route('country.edit'),
                DeleteAction::make()->route('country.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('country.destroy'))
            ->addBulkChanges([
                TextBulkChange::make()
                    ->name('nationality')
                    ->title(trans('plugins/location::country.nationality')),
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'name',
                        'nationality',
                        'created_at',
                        'status',
                    ]);
            });
    }
}
