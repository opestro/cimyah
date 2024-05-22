<?php

namespace Botble\Marketplace\Tables;

use Botble\Ecommerce\Tables\Formatters\PriceFormatter;
use Botble\Marketplace\Models\Withdrawal;
use Botble\Marketplace\Tables\Traits\ForVendor;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class VendorWithdrawalTable extends TableAbstract
{
    use ForVendor;

    public function setup(): void
    {
        $this->model(Withdrawal::class);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->formatColumn('fee', PriceFormatter::class)
            ->formatColumn('amount', PriceFormatter::class)
            ->addColumn('operations', function (Withdrawal $item) {
                return view('plugins/marketplace::withdrawals.tables.actions', compact('item'))->render();
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->getModel()->query()
            ->select([
                'id',
                'fee',
                'amount',
                'status',
                'currency',
                'created_at',
            ])
            ->where('customer_id', auth('customer')->id());

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::formatted('amount')->title(trans('plugins/ecommerce::order.amount')),
            Column::formatted('fee')->title(trans('plugins/ecommerce::shipping.fee')),
            StatusColumn::make(),
            CreatedAtColumn::make(),
        ];
    }

    public function getDefaultButtons(): array
    {
        return array_merge(['export'], parent::getDefaultButtons());
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('marketplace.vendor.withdrawals.create'));
    }
}
