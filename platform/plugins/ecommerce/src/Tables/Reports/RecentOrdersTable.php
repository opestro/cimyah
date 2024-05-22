<?php

namespace Botble\Ecommerce\Tables\Reports;

use Botble\Base\Facades\BaseHelper;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Tables\Formatters\PriceFormatter;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\LinkableColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class RecentOrdersTable extends TableAbstract
{
    public function setup(): void
    {
        $this->model(Order::class);

        $this->type = self::TABLE_TYPE_SIMPLE;
        $this->defaultSortColumn = 0;
        $this->view = $this->simpleTableView();
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('payment_status', function (Order $item) {
                if (! is_plugin_active('payment')) {
                    return '&mdash;';
                }

                return BaseHelper::clean($item->payment->status->toHtml() ?: '&mdash;');
            })
            ->editColumn('payment_method', function (Order $item) {
                if (! is_plugin_active('payment')) {
                    return '&mdash;';
                }

                return BaseHelper::clean($item->payment->payment_channel->label() ?: '&mdash;');
            })
            ->formatColumn('amount', PriceFormatter::class)
            ->editColumn('user_id', function (Order $item) {
                return BaseHelper::clean($item->user->name ?: $item->address->name);
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        [$startDate, $endDate] = EcommerceHelper::getDateRangeInReport(request());

        $with = ['user'];

        if (is_plugin_active('payment')) {
            $with[] = 'payment';
        }

        $query = $this->getModel()
            ->query()
            ->select([
                'id',
                'status',
                'code',
                'user_id',
                'created_at',
                'amount',
                'tax_amount',
                'payment_id',
            ])
            ->with($with)
            ->where('is_finished', 1)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderByDesc('created_at')
            ->limit(10);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            LinkableColumn::make('code')
                ->route('orders.edit')
                ->title(trans('core/base::tables.id'))
                ->alignCenter()
                ->width(20),
            Column::make('user_id')
                ->title(trans('plugins/ecommerce::order.customer_label'))
                ->alignStart(),
            Column::formatted('amount')
                ->title(trans('plugins/ecommerce::order.amount')),
            Column::make('payment_method')
                ->name('payment_id')
                ->title(trans('plugins/ecommerce::order.payment_method'))
                ->alignStart(),
            Column::make('payment_status')
                ->name('payment_id')
                ->title(trans('plugins/ecommerce::order.payment_status_label')),
            StatusColumn::make(),
            CreatedAtColumn::make(),
        ];
    }
}
