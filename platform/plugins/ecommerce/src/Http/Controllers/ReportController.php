<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Facades\Assets;
use Botble\Base\Widgets\Contracts\AdminWidget;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Tables\Reports\RecentOrdersTable;
use Botble\Ecommerce\Tables\Reports\TopSellingProductsTable;
use Botble\Ecommerce\Tables\Reports\TrendingProductsTable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReportController extends BaseController
{
    public function getIndex(Request $request, AdminWidget $widget)
    {
        $this->pageTitle(trans('plugins/ecommerce::reports.name'));

        Assets::addScriptsDirectly([
            'vendor/core/plugins/ecommerce/libraries/daterangepicker/daterangepicker.js',
            'vendor/core/plugins/ecommerce/js/report.js',
        ])
            ->addStylesDirectly([
                'vendor/core/plugins/ecommerce/libraries/daterangepicker/daterangepicker.css',
                'vendor/core/plugins/ecommerce/css/report.css',
            ]);

        Assets::usingVueJS();

        [$startDate, $endDate] = EcommerceHelper::getDateRangeInReport($request);

        if ($request->ajax()) {
            return $this
                ->httpResponse()->setData(view('plugins/ecommerce::reports.ajax', compact('widget'))->render());
        }

        return view(
            'plugins/ecommerce::reports.index',
            compact('startDate', 'endDate', 'widget')
        );
    }

    public function getTopSellingProducts(TopSellingProductsTable $topSellingProductsTable)
    {
        return $topSellingProductsTable->renderTable();
    }

    public function getRecentOrders(RecentOrdersTable $recentOrdersTable)
    {
        return $recentOrdersTable->renderTable();
    }

    public function getTrendingProducts(TrendingProductsTable $trendingProductsTable)
    {
        return $trendingProductsTable->renderTable();
    }

    public function getDashboardWidgetGeneral()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $today = Carbon::now();

        $processingOrders = Order::query()
            ->where(function (Builder $query) {
                $query
                    ->whereNotIn('status', [OrderStatusEnum::CANCELED, OrderStatusEnum::COMPLETED])
                    ->orWhereNull('completed_at');
            })
            ->whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $today)
            ->count();

        $completedOrders = Order::query()
            ->where(function (Builder $query) {
                $query
                    ->where('status', OrderStatusEnum::COMPLETED)
                    ->orWhereNotNull('completed_at');
            })
            ->whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $today)
            ->count();

        $revenue = Order::countRevenueByDateRange($startOfMonth, $today);

        $lowStockProducts = Product::query()
            ->where('with_storehouse_management', 1)
            ->where('quantity', '<', 2)
            ->where('quantity', '>', 0)
            ->count();

        $outOfStockProducts = Product::query()
            ->where('with_storehouse_management', 1)
            ->where('quantity', '<', 1)
            ->count();

        return $this
            ->httpResponse()
            ->setData(
                view(
                    'plugins/ecommerce::reports.widgets.general',
                    compact(
                        'processingOrders',
                        'revenue',
                        'completedOrders',
                        'outOfStockProducts',
                        'lowStockProducts'
                    )
                )->render()
            );
    }
}
