<?php

namespace Botble\Ecommerce\Importers;

use Botble\DataSynchronize\Importer\ImportColumn;
use Botble\DataSynchronize\Importer\Importer;
use Botble\Ecommerce\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductPriceImporter extends Importer
{
    public function getLabel(): string
    {
        return trans('plugins/ecommerce::product-prices.import.name');
    }

    public function getHeading(): string
    {
        return $this->getLabel();
    }

    public function getDoneMessage(int $count): string
    {
        return trans('plugins/ecommerce::product-prices.import.done_message', ['count' => $count]);
    }

    public function getExportUrl(): ?string
    {
        return Auth::user()->hasPermission('ecommerce.product-prices.export')
            ? route('ecommerce.product-prices.export.store')
            : null;
    }

    public function getValidateUrl(): string
    {
        return route('ecommerce.product-prices.import.validate');
    }

    public function getImportUrl(): string
    {
        return route('ecommerce.product-prices.import.store');
    }

    public function getDownloadExampleUrl(): string
    {
        return route('ecommerce.product-prices.import.download-example');
    }

    public function columns(): array
    {
        return [
            ImportColumn::make('id')
                ->label('ID')
                ->rules(
                    ['required', 'exists:ec_products,id'],
                    trans('plugins/ecommerce::product-prices.import.rules.id')
                ),
            ImportColumn::make('name')
                ->rules(
                    ['required', 'string'],
                    trans('plugins/ecommerce::product-prices.import.rules.name')
                ),
            ImportColumn::make('sku')
                ->label('SKU')
                ->rules(
                    ['nullable', 'string'],
                    trans('plugins/ecommerce::product-prices.import.rules.sku')
                ),
            ImportColumn::make('cost_per_item')
                ->nullable()
                ->rules(
                    ['nullable', 'numeric'],
                    trans('plugins/ecommerce::product-prices.import.rules.cost_per_item')
                ),
            ImportColumn::make('price')
                ->rules(
                    ['required', 'numeric'],
                    trans('plugins/ecommerce::product-prices.import.rules.price')
                ),
            ImportColumn::make('sale_price')
                ->nullable()
                ->rules(
                    ['nullable', 'numeric'],
                    trans('plugins/ecommerce::product-prices.import.rules.sale_price')
                ),
        ];
    }

    public function examples(): array
    {
        $products = Product::query()
            ->take(5)
            ->wherePublished()
            ->select(['id', 'name', 'sku', 'cost_per_item', 'price', 'sale_price'])
            ->get();

        if ($products->isNotEmpty()) {
            return $products->all();
        }

        return [
            [
                'id' => '62',
                'name' => 'Product 1',
                'sku' => 'SKU-1',
                'cost_per_item' => null,
                'price' => 250,
                'sale_price' => null,
            ],
            [
                'id' => '212',
                'name' => 'Product 2',
                'sku' => 'SKU-2',
                'cost_per_item' => null,
                'price' => 200,
                'sale_price' => 150,
            ],
            [
                'id' => '1004',
                'name' => 'Product 3',
                'sku' => null,
                'cost_per_item' => 100,
                'price' => 92,
                'sale_price' => 32,
            ],
        ];
    }

    public function handle(array $data): int
    {
        return Product::withoutTimestamps(function () use ($data) {
            return Product::query()->upsert($data, ['id'], ['cost_per_item', 'price', 'sale_price']) / 2;
        });
    }
}
