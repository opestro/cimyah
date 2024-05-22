<?php

namespace Botble\Marketplace\Widgets;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Widgets\Html;
use Botble\Marketplace\Enums\RevenueTypeEnum;
use Botble\Marketplace\Models\Revenue;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class SaleCommissionHtml extends Html
{
    public function getContent(): string
    {
        $count = collect([
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);

        $revenues = Revenue::query()
            ->selectRaw('DATE(created_at) AS date, SUM(COALESCE(fee, 0)) as total_fee, SUM(COALESCE(amount, 0)) as total_amount')
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->where(function (Builder $query) {
                $query
                    ->whereNull('type')
                    ->orWhere('type', RevenueTypeEnum::ADD_AMOUNT);
            })
            ->groupBy('date')
            ->get();

        $totalFee = $revenues->sum('total_fee');
        $totalAmount = $revenues->sum('total_amount');

        $colors = ['#80bc00', '#E91E63'];

        $count['revenues'] = collect([
            [
                'label' => trans('plugins/marketplace::marketplace.reports.total_fee'),
                'value' => (float) $totalFee,
                'color' => Arr::first($colors),
            ],
            [
                'label' => trans('plugins/marketplace::marketplace.reports.total_amount'),
                'value' => (float) $totalAmount,
                'color' => Arr::last($colors),
            ],
        ]);

        $dates = [];
        $period = CarbonPeriod::create($this->startDate->startOfDay(), $this->endDate->endOfDay());

        $symbol = get_application_currency()->symbol;
        $feeData = [
            'name' => trans('plugins/marketplace::marketplace.reports.fee', ['symbol' => $symbol]),
            'data' => [],
        ];

        $amountData = [
            'name' => trans('plugins/marketplace::marketplace.reports.amount', ['symbol' => $symbol]),
            'data' => [],
        ];

        foreach ($period as $date) {
            $fee = $revenues
                ->where('date', $date->toDateString())
                ->sum('total_fee');

            $amount = $revenues
                ->where('date', $date->toDateString())
                ->sum('total_amount');

            $feeData['data'][] = (float) $fee;
            $amountData['data'][] = (float) $amount;
            $dates[] = BaseHelper::formatDate($date);
        }

        $series = [
            $feeData,
            $amountData,
        ];

        $salesReport = compact('dates', 'series', 'colors', 'totalFee', 'totalAmount');

        return view('plugins/marketplace::reports.widgets.sale-commissions', compact('count', 'salesReport'))->render();
    }
}
