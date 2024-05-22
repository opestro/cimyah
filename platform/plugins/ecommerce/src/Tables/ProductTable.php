<?php

namespace Botble\Ecommerce\Tables;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\DataSynchronize\Table\HeaderActions\ExportHeaderAction;
use Botble\DataSynchronize\Table\HeaderActions\ImportHeaderAction;
use Botble\Ecommerce\Enums\ProductTypeEnum;
use Botble\Ecommerce\Enums\StockStatusEnum;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Brand;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\IsFeaturedBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\NumberBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\ImageColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\Relation as EloquentRelation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Product::class)
            ->addActions([
                EditAction::make()->route('products.edit'),
                DeleteAction::make()->route('products.destroy'),
            ])
            ->addHeaderActions([
                ExportHeaderAction::make()
                    ->route('tools.data-synchronize.export.products.index')
                    ->permission('ecommerce.export.products.index'),
                ImportHeaderAction::make()
                    ->route('tools.data-synchronize.import.products.index')
                    ->permission('ecommerce.import.products.index'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function (Product $item) {
                $productType = null;

                if (EcommerceHelper::isEnabledSupportDigitalProducts()) {
                    $productType = Html::tag('small', ' &mdash; ' . $item->product_type->label())->toHtml();
                }

                if (! $this->hasPermission('products.edit')) {
                    return BaseHelper::clean($item->name) . $productType;
                }

                return Html::link(
                    route('products.edit', $item->getKey()),
                    BaseHelper::clean($item->name)
                ) . $productType;
            })
            ->editColumn('price', function (Product $item) {
                return $item->price_in_table;
            })
            ->editColumn('quantity', function (Product $item) {
                if (! $item->with_storehouse_management) {
                    return '&#8734;';
                }

                if ($item->variations->isEmpty()) {
                    return $item->quantity;
                }

                $withStoreHouseManagement = $item->with_storehouse_management;

                $quantity = 0;

                foreach ($item->variations as $variation) {
                    if (! $variation->product->with_storehouse_management) {
                        $withStoreHouseManagement = false;

                        break;
                    }

                    $quantity += $variation->product->quantity;
                }

                return $withStoreHouseManagement ? $quantity : '&#8734;';
            })
            ->editColumn('sku', function (Product $item) {
                return BaseHelper::clean($item->sku ?: '&mdash;');
            })
            ->editColumn('order', function (Product $item) {
                return view('plugins/ecommerce::products.partials.sort-order', compact('item'))->render();
            })
            ->editColumn('stock_status', function (Product $item) {
                return BaseHelper::clean($item->stock_status_html);
            })
            ->filter(function ($query) {
                $keyword = request()->input('search.value');
                if ($keyword) {
                    $keyword = '%' . $keyword . '%';

                    $query
                        ->where('ec_products.name', 'LIKE', $keyword)
                        ->where('is_variation', 0)
                        ->orWhere(function ($query) use ($keyword) {
                            $query
                                ->where('is_variation', 0)
                                ->where(function ($query) use ($keyword) {
                                    $query
                                        ->orWhere('ec_products.sku', 'LIKE', $keyword)
                                        ->orWhere('ec_products.created_at', 'LIKE', $keyword)
                                        ->orWhereHas('variations.product', function ($query) use ($keyword) {
                                            $query->where('sku', 'LIKE', $keyword);
                                        });
                                });
                        });

                    return $query;
                }

                return $query;
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->getModel()
            ->query()
            ->select([
                'id',
                'name',
                'order',
                'created_at',
                'status',
                'sku',
                'image',
                'images',
                'price',
                'sale_price',
                'sale_type',
                'start_date',
                'end_date',
                'quantity',
                'with_storehouse_management',
                'stock_status',
                'product_type',
            ])
            ->where('is_variation', 0)
            ->with('variations.product');

        return $this->applyScopes($query);
    }

    public function htmlDrawCallbackFunction(): ?string
    {
        return parent::htmlDrawCallbackFunction() . 'Botble.initEditable()';
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            ImageColumn::make(),
            Column::make('name')
                ->title(trans('plugins/ecommerce::products.name'))
                ->alignStart(),
            Column::make('price')
                ->title(trans('plugins/ecommerce::products.price'))
                ->alignStart(),
            Column::make('stock_status')
                ->title(trans('plugins/ecommerce::products.stock_status')),
            Column::make('quantity')
                ->title(trans('plugins/ecommerce::products.quantity'))
                ->alignStart(),
            Column::make('sku')
                ->title(trans('plugins/ecommerce::products.sku'))
                ->alignStart(),
            Column::make('order')
                ->title(trans('plugins/ecommerce::ecommerce.sort_order'))
                ->width(50),
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ];
    }

    public function buttons(): array
    {
        $buttons = [];

        if (EcommerceHelper::isEnabledSupportDigitalProducts() && $this->hasPermission('products.create')) {
            $buttons['create'] = [
                'extend' => 'collection',
                'text' => view('core/table::partials.create')->render(),
                'class' => 'btn-primary',
                'buttons' => [
                    [
                        'className' => 'action-item',
                        'text' => ProductTypeEnum::PHYSICAL()->toIcon() . ' ' . Html::tag(
                            'span',
                            ProductTypeEnum::PHYSICAL()->label(),
                            [
                                    'data-action' => 'physical-product',
                                    'data-href' => route('products.create'),
                                    'class' => 'ms-1',
                                ]
                        )->toHtml(),
                    ],
                    [
                        'className' => 'action-item',
                        'text' => ProductTypeEnum::DIGITAL()->toIcon() . ' ' . Html::tag(
                            'span',
                            ProductTypeEnum::DIGITAL()->label(),
                            [
                                    'data-action' => 'digital-product',
                                    'data-href' => route('products.create', ['product_type' => 'digital']),
                                    'class' => 'ms-1',
                                ]
                        )->toHtml(),
                    ],
                ],
            ];
        } else {
            $buttons = $this->addCreateButton(route('products.create'), 'products.create');
        }

        return $buttons;
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('products.destroy'),
        ];
    }

    public function renderTable($data = [], $mergeData = []): View|Factory|Response
    {
        if ($this->isEmpty()) {
            return view('plugins/ecommerce::products.intro');
        }

        return parent::renderTable($data, $mergeData);
    }

    public function getFilters(): array
    {
        $data = parent::getFilters();

        $data['category'] = array_merge($data['category'], [
            'type' => 'select-ajax',
        ]);

        $data['brand_id'] = array_merge($data['brand_id'], [
            'type' => 'select-ajax',
        ]);

        $data['stock_status'] = [
            'title' => trans('plugins/ecommerce::products.form.stock_status'),
            'type' => 'select',
            'choices' => StockStatusEnum::labels(),
            'validate' => 'required|in:' . implode(',', StockStatusEnum::values()),
        ];

        $data['product_type'] = [
            'title' => trans('plugins/ecommerce::products.form.product_type.title'),
            'type' => 'select',
            'choices' => ProductTypeEnum::labels(),
            'validate' => 'required|in:' . implode(',', ProductTypeEnum::values()),
        ];

        return $data;
    }

    public function getBulkChanges(): array
    {
        return [
            NameBulkChange::make(),
            NumberBulkChange::make()
                ->name('order')
                ->title(trans('plugins/ecommerce::ecommerce.sort_order')),
            'category' => [
                'title' => trans('plugins/ecommerce::products.category'),
                'type' => 'select-ajax',
                'validate' => 'required',
                'callback' => function (int|string|null $value = null): array {
                    $categorySelected = [];
                    if ($value && $category = ProductCategory::query()->find($value)) {
                        $categorySelected = [$category->getKey() => $category->name];
                    }

                    return [
                        'url' => route('product-categories.search'),
                        'selected' => $categorySelected,
                        'minimum-input' => 1,
                    ];
                },
            ],
            'brand_id' => [
                'title' => trans('plugins/ecommerce::products.brand'),
                'type' => 'select-ajax',
                'validate' => 'required',
                'callback' => function (int|string|null $value = null): array {
                    $brandSelected = [];
                    if ($value && $brand = Brand::query()->find($value)) {
                        $brandSelected = [$brand->getKey() => $brand->name];
                    }

                    return [
                        'url' => route('brands.search'),
                        'selected' => $brandSelected,
                        'minimum-input' => 1,
                    ];
                },
            ],
            StatusBulkChange::make(),
            CreatedAtBulkChange::make(),
            IsFeaturedBulkChange::make(),
        ];
    }

    public function applyFilterCondition(
        EloquentBuilder|QueryBuilder|EloquentRelation $query,
        string $key,
        string $operator,
        ?string $value
    ): EloquentRelation|EloquentBuilder|QueryBuilder {
        switch ($key) {
            case 'created_at':
                if (! $value) {
                    break;
                }

                $value = BaseHelper::formatDate($value);

                return $query->whereDate('ec_products.' . $key, $operator, $value);
            case 'category':
                if (! $value) {
                    break;
                }

                if (! BaseHelper::isJoined($query, 'ec_product_categories')) {
                    $query = $query
                        ->join(
                            'ec_product_category_product',
                            'ec_product_category_product.product_id',
                            '=',
                            'ec_products.id'
                        )
                        ->join(
                            'ec_product_categories',
                            'ec_product_category_product.category_id',
                            '=',
                            'ec_product_categories.id'
                        )
                        ->select($query->getModel()->getTable() . '.*');
                }

                return $query->where('ec_product_category_product.category_id', $value);

            case 'brand':
                if (! $value) {
                    break;
                }

                return $query->where('ec_products.brand_id', $operator, $value);

            case 'stock_status':
                if (! $value) {
                    break;
                }

                if ($value == StockStatusEnum::ON_BACKORDER) {
                    return parent::applyFilterCondition($query, $key, $operator, $value);
                }

                if ($value == StockStatusEnum::OUT_OF_STOCK) {
                    return $query
                        ->where(function ($query) {
                            $query
                                ->where(function ($subQuery) {
                                    $subQuery
                                        ->where('with_storehouse_management', 0)
                                        ->where('stock_status', StockStatusEnum::OUT_OF_STOCK);
                                })
                                ->orWhere(function ($subQuery) {
                                    $subQuery
                                        ->where('with_storehouse_management', 1)
                                        ->where('allow_checkout_when_out_of_stock', 0)
                                        ->where('quantity', '<=', 0);
                                });
                        });
                }

                if ($value == StockStatusEnum::IN_STOCK) {
                    return $query
                        ->where(function ($query) {
                            return $query
                                ->where(function ($subQuery) {
                                    $subQuery
                                        ->where('with_storehouse_management', 0)
                                        ->where('stock_status', StockStatusEnum::IN_STOCK);
                                })
                                ->orWhere(function ($subQuery) {
                                    $subQuery
                                        ->where('with_storehouse_management', 1)
                                        ->where(function ($sub) {
                                            $sub
                                                ->where('allow_checkout_when_out_of_stock', 1)
                                                ->orWhere('quantity', '>', 0);
                                        });
                                });
                        });
                }
        }

        return parent::applyFilterCondition($query, $key, $operator, $value);
    }

    public function saveBulkChangeItem(Model|Product $item, string $inputKey, ?string $inputValue): Model|bool
    {
        if ($inputKey === 'category') {
            /**
             * @var Product $item
             */
            $item->categories()->sync([$inputValue]);

            return $item;
        }

        return parent::saveBulkChangeItem($item, $inputKey, $inputValue);
    }
}
