<?php

namespace Botble\Ecommerce\Exporters;

use Botble\DataSynchronize\Exporter\ExportColumn;
use Botble\DataSynchronize\Exporter\Exporter;
use Botble\Ecommerce\Enums\StockStatusEnum;
use Botble\Ecommerce\Models\Product;
use Illuminate\Support\Collection;

class ProductInventoryExporter extends Exporter
{
    public function getLabel(): string
    {
        return trans('plugins/ecommerce::product-inventory.name');
    }

    public function columns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID')
                ->disabled(),
            ExportColumn::make('name')
                ->disabled(),
            ExportColumn::make('sku')
                ->label('SKU')
                ->disabled(),
            ExportColumn::make('with_storehouse_management')
                ->boolean()
                ->disabled(),
            ExportColumn::make('quantity')
                ->disabled(),
            ExportColumn::make('stock_status')
                ->dropdown(StockStatusEnum::values())
                ->disabled(),
        ];
    }

    public function collection(): Collection
    {
        return Product::getGroupedVariationQuery()
            ->addSelect([
                'ec_products.quantity',
                'ec_products.with_storehouse_management',
                'ec_products.stock_status',
            ])
            ->get();
    }
}
