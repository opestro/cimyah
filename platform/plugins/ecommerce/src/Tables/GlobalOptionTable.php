<?php

namespace Botble\Ecommerce\Tables;

use Botble\Ecommerce\Models\GlobalOption;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\YesNoColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;

class GlobalOptionTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(GlobalOption::class)
            ->addActions([
                EditAction::make()->route('global-option.edit'),
                DeleteAction::make()->route('global-option.destroy'),
            ]);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'name',
                'created_at',
                'required',
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            NameColumn::make()->route('global-option.edit'),
            YesNoColumn::make('required')
                ->title(trans('plugins/ecommerce::product-option.required'))
                ->alignStart(),
            CreatedAtColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('global-option.create'), 'global-option.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('global-option.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            'name' => [
                'title' => trans('core/base::tables.name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'datePicker',
            ],
        ];
    }
}
