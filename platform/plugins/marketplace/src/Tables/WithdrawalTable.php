<?php

namespace Botble\Marketplace\Tables;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Ecommerce\Tables\Formatters\PriceFormatter;
use Botble\Marketplace\Enums\WithdrawalStatusEnum;
use Botble\Marketplace\Models\Withdrawal;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class WithdrawalTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Withdrawal::class)
            ->addActions([
                EditAction::make()->route('marketplace.withdrawal.edit'),
                DeleteAction::make()->route('marketplace.withdrawal.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('customer_id', function ($item) {
                if (! $this->hasPermission('customers.edit')) {
                    return BaseHelper::clean($item->customer->name);
                }

                if (! $item->customer->id) {
                    return '&mdash;';
                }

                return Html::link(route('customers.edit', $item->customer->id), BaseHelper::clean($item->customer->name));
            })
            ->formatColumn('fee', PriceFormatter::class)
            ->formatColumn('amount', PriceFormatter::class);

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'customer_id',
                'amount',
                'fee',
                'currency',
                'created_at',
                'status',
            ])
            ->with(['customer']);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('customer_id')
                ->title(trans('plugins/marketplace::withdrawal.vendor'))
                ->alignStart(),
            Column::formatted('amount')->title(trans('plugins/ecommerce::order.amount')),
            Column::formatted('fee')->title(trans('plugins/ecommerce::shipping.fee')),
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            'status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => WithdrawalStatusEnum::labels(),
                'validate' => 'required|' . Rule::in(WithdrawalStatusEnum::values()),
            ],
        ];
    }
}
