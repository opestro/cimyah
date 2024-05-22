<?php

namespace Botble\Ecommerce\Importers;

use Botble\DataSynchronize\Importer\ImportColumn;
use Botble\DataSynchronize\Importer\Importer;
use Botble\Ecommerce\Enums\StockStatusEnum;
use Botble\Ecommerce\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductInventoryImporter extends Importer
{
    public function getLabel(): string
    {
        return trans('plugins/ecommerce::product-inventory.import.name');
    }

    public function getHeading(): string
    {
        return $this->getLabel();
    }

    public function getDoneMessage(int $count): string
    {
        return trans('plugins/ecommerce::product-inventory.import.done_message', ['count' => $count]);
    }

    public function getExportUrl(): ?string
    {
        return Auth::user()->hasPermission('ecommerce.product-inventory.export')
            ? route('ecommerce.product-inventory.export.store')
            : null;
    }

    public function getValidateUrl(): string
    {
        return route('ecommerce.product-inventory.import.validate');
    }

    public function getImportUrl(): string
    {
        return route('ecommerce.product-inventory.import.store');
    }

    public function getDownloadExampleUrl(): string
    {
        return route('ecommerce.product-inventory.import.download-example');
    }

    public function columns(): array
    {
        return [
            ImportColumn::make('id')
                ->label('ID')
                ->rules(
                    ['required', 'exists:ec_products,id'],
                    trans('plugins/ecommerce::product-inventory.import.rules.id')
                ),
            ImportColumn::make('name')
                ->rules(
                    ['required', 'string'],
                    trans('plugins/ecommerce::product-inventory.import.rules.name')
                ),
            ImportColumn::make('sku')
                ->label('SKU')
                ->rules(
                    ['nullable', 'string'],
                    trans('plugins/ecommerce::product-inventory.import.rules.sku')
                ),
            ImportColumn::make('with_storehouse_management')
                ->nullable()
                ->boolean()
                ->rules(
                    ['nullable', 'boolean'],
                    trans('plugins/ecommerce::product-inventory.import.rules.with_storehouse_management')
                ),
            ImportColumn::make('quantity')
                ->rules(
                    ['required_if:with_storehouse_management,1', 'numeric', 'min:0'],
                    trans('plugins/ecommerce::product-inventory.import.rules.quantity')
                ),
            ImportColumn::make('stock_status')
                ->rules(
                    ['required_if:with_storehouse_management,0', Rule::in(StockStatusEnum::values())],
                    trans('plugins/ecommerce::product-inventory.import.rules.stock_status', ['statuses' => implode(', ', StockStatusEnum::values())])
                ),
        ];
    }

    public function examples(): array
    {
        $products = Product::query()
            ->take(5)
            ->wherePublished()
            ->select(['id', 'name', 'sku', 'with_storehouse_management', 'quantity', 'stock_status'])
            ->get();

        if ($products->isNotEmpty()) {
            return $products->transform(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'with_storehouse_management' => $product->with_storehouse_management ? 'Yes' : 'No',
                    'quantity' => $product->quantity,
                    'stock_status' => $product->stock_status,
                ];
            })->all();
        }

        return [
            [
                'id' => '62',
                'name' => 'Product 1',
                'sku' => 'SKU-1',
                'with_storehouse_management' => 'Yes',
                'quantity' => 10,
            ],
            [
                'id' => '212',
                'name' => 'Product 2',
                'sku' => 'SKU-2',
                'with_storehouse_management' => 'Yes',
                'quantity' => 0,
            ],
            [
                'id' => '1004',
                'name' => 'Product 3',
                'sku' => null,
                'with_storehouse_management' => 'No',
                'quantity' => 23,
            ],
        ];
    }

    public function handle(array $data): int
    {
        return Product::withoutTimestamps(function () use ($data) {
            return Product::query()->upsert($data, ['id'], ['with_storehouse_management', 'quantity', 'stock_status']) / 2;
        });
    }
}
