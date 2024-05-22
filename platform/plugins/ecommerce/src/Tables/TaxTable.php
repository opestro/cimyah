<?php

namespace Botble\Ecommerce\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Models\Tax;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class TaxTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Tax::class)
            ->addActions([
                EditAction::make()->route('tax.edit'),
                DeleteAction::make()->route('tax.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('percentage', function (Tax $item) {
                return ($item->percentage + 0) . '%';
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'title',
                'percentage',
                'priority',
                'status',
                'created_at',
            ])
            ->withCount(['rules']);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            NameColumn::make('title')->route('tax.edit'),
            Column::make('percentage')
                ->title(trans('plugins/ecommerce::tax.percentage')),
            Column::make('priority')
                ->title(trans('plugins/ecommerce::tax.priority')),
            Column::make('rules_count')
                ->title(trans('plugins/ecommerce::tax.rules'))
                ->searchable(false)
                ->orderable(false),
            StatusColumn::make(),
            CreatedAtColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('tax.create'), 'tax.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('tax.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            'title' => [
                'title' => trans('core/base::tables.name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'datePicker',
            ],
        ];
    }
}
