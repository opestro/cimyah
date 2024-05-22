<?php

namespace Botble\Gallery\Tables;

use Botble\Gallery\Models\Gallery;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\ImageColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class GalleryTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Gallery::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('galleries.create'))
            ->addColumns([
                IdColumn::make(),
                ImageColumn::make(),
                NameColumn::make()->route('galleries.edit'),
                Column::make('order')
                    ->title(trans('core/base::tables.order'))
                    ->width(100),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addActions([
                EditAction::make()->route('galleries.edit'),
                DeleteAction::make()->route('galleries.destroy'),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('galleries.destroy'),
            ])
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'name',
                        'order',
                        'created_at',
                        'status',
                        'image',
                    ]);
            });
    }
}
