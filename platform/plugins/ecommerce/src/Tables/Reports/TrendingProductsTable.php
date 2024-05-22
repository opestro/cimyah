<?php

namespace Botble\Ecommerce\Tables\Reports;

use Botble\Base\Facades\Html;
use Botble\Ecommerce\Facades\EcommerceHelper as EcommerceHelper;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductView;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\IdColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class TrendingProductsTable extends TableAbstract
{
    public function setup(): void
    {
        $this->model(Product::class);

        $this->view = $this->simpleTableView();
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function (Product $product) {
                return Html::link($product->url, $product->name, ['target' => '_blank']);
            })
            ->editColumn('views', function (Product $product) {
                return number_format((float) $product->views_count);
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        [$startDate, $endDate] = EcommerceHelper::getDateRangeInReport(request());

        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'name',
                'views_count' => ProductView::query()
                    ->selectRaw('SUM(views) as views_count')
                    ->whereColumn('product_id', 'ec_products.id')
                    ->whereDate('date', '>=', $startDate)
                    ->whereDate('date', '<=', $endDate)
                    ->groupBy('product_id'),
            ])
            ->wherePublished()
            ->where('is_variation', false)
            ->orderByDesc('views_count')
            ->limit(5);

        return $this->applyScopes($query);
    }

    public function getColumns(): array
    {
        return $this->columns();
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('name')
                ->title(trans('plugins/ecommerce::reports.product_name'))
                ->alignStart()
                ->orderable(false)
                ->searchable(false),
            Column::make('views')
                ->title(trans('plugins/ecommerce::reports.views'))
                ->alignEnd()
                ->orderable(false)
                ->searchable(false),
        ];
    }

    public function isSimpleTable(): bool
    {
        return true;
    }
}
