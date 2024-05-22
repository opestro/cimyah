<?php

namespace Botble\Ecommerce\Tables;

use Botble\Table\Columns\FormattedColumn;

class ProductInventoryTable extends ProductBulkEditableTable
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->addColumns([
                FormattedColumn::make('with_storehouse_management')
                    ->title(trans('plugins/ecommerce::product-inventory.storehouse_management'))
                    ->renderUsing(function (FormattedColumn $column) {
                        return view('plugins/ecommerce::product-inventory.columns.storehouse_management', [
                            'product' => $column->getItem(),
                            'type' => 'storehouse_management',
                        ]);
                    })
                    ->nowrap()
                    ->orderable(false),
                FormattedColumn::make('quantity')
                    ->title(trans('plugins/ecommerce::products.form.quantity'))
                    ->renderUsing(function (FormattedColumn $column) {
                        return view('plugins/ecommerce::product-inventory.columns.quantity', [
                            'product' => $column->getItem(),
                        ]);
                    })
                    ->nowrap()
                    ->orderable(false),
            ]);
    }

    public function query()
    {
        /** @var \Illuminate\Database\Query\Builder $query */
        $query = parent::query();

        $query->addSelect([
            'ec_products.stock_status',
            'ec_products.quantity',
            'ec_products.with_storehouse_management',
        ]);

        return $query;
    }
}
