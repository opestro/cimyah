<?php

namespace Botble\Marketplace\Tables;

use Botble\Base\Facades\BaseHelper;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Tables\Formatters\PriceFormatter;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Marketplace\Tables\Traits\ForVendor;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class OrderTable extends TableAbstract
{
    use ForVendor;

    public function setup(): void
    {
        $this
            ->model(Order::class)
            ->addActions(array_filter([
                EditAction::make()->route('marketplace.vendor.orders.edit'),
                MarketplaceHelper::allowVendorDeleteTheirOrders()
                    ? DeleteAction::make()->route('marketplace.vendor.orders.destroy')
                    : null,
            ]));
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('payment_status', function (Order $item) {
                if (! is_plugin_active('payment')) {
                    return '&mdash;';
                }

                return $item->payment->status->label() ? BaseHelper::clean(
                    $item->payment->status->toHtml()
                ) : '&mdash;';
            })
            ->editColumn('payment_method', function (Order $item) {
                if (! is_plugin_active('payment')) {
                    return '&mdash;';
                }

                return BaseHelper::clean($item->payment->payment_channel->label() ?: '&mdash;');
            })
            ->formatColumn('amount', PriceFormatter::class)
            ->formatColumn('shipping_amount', PriceFormatter::class);

        if (EcommerceHelper::isTaxEnabled()) {
            $data = $data->editColumn('tax_amount', function ($item) {
                return format_price($item->tax_amount);
            });
        }

        $data = $data
            ->filter(function ($query) {
                if ($keyword = $this->request->input('search.value')) {
                    return $query
                        ->whereHas('address', function ($subQuery) use ($keyword) {
                            return $subQuery
                                ->where('name', 'LIKE', '%' . $keyword . '%')
                                ->orWhere('email', 'LIKE', '%' . $keyword . '%')
                                ->orWhere('phone', 'LIKE', '%' . $keyword . '%');
                        })
                        ->orWhereHas('user', function ($subQuery) use ($keyword) {
                            return $subQuery
                                ->where('name', 'LIKE', '%' . $keyword . '%')
                                ->orWhere('email', 'LIKE', '%' . $keyword . '%')
                                ->orWhere('phone', 'LIKE', '%' . $keyword . '%');
                        })
                        ->orWhere('code', 'LIKE', '%' . $keyword . '%');
                }

                return $query;
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $with = ['user'];

        if (is_plugin_active('payment')) {
            $with[] = 'payment';
        }

        $query = $this
            ->getModel()
            ->query()
            ->with($with)
            ->select([
                'id',
                'status',
                'user_id',
                'created_at',
                'amount',
                'tax_amount',
                'shipping_amount',
                'payment_id',
            ])
            ->where('is_finished', 1)
            ->where('store_id', auth('customer')->user()->store->id);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        $columns = [
            IdColumn::make(),
            FormattedColumn::make('user_id')
                ->title(trans('plugins/ecommerce::order.email'))
                ->alignStart()
                ->orderable(false)
                ->renderUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();

                    return sprintf(
                        '%s <br> %s <br> %s',
                        $item->user->name ?: $item->address->name,
                        $item->user->email ?: $item->address->email,
                        $item->user->phone ?: $item->address->phone
                    );
                }),
            Column::formatted('amount')
                ->title(trans('plugins/ecommerce::order.amount')),
        ];

        if (EcommerceHelper::isTaxEnabled()) {
            $columns[] = Column::make('tax_amount')
                ->title(trans('plugins/ecommerce::order.tax_amount'));
        }

        $columns = array_merge($columns, [
            Column::formatted('shipping_amount')
                ->title(trans('plugins/ecommerce::order.shipping_amount')),
        ]);

        if (is_plugin_active('payment')) {
            $columns = array_merge($columns, [
                Column::make('payment_method')
                    ->name('payment_id')
                    ->title(trans('plugins/ecommerce::order.payment_method'))
                    ->alignStart(),
                Column::make('payment_status')
                    ->name('payment_id')
                    ->title(trans('plugins/ecommerce::order.payment_status_label')),
            ]);
        }

        return array_merge($columns, [
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ]);
    }

    public function getDefaultButtons(): array
    {
        return array_merge(['export'], parent::getDefaultButtons());
    }
}
