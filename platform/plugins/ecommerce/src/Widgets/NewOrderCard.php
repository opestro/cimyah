<?php

namespace Botble\Ecommerce\Widgets;

use Botble\Base\Widgets\Card;
use Botble\Ecommerce\Models\Order;
use Botble\Payment\Enums\PaymentStatusEnum;
use Carbon\CarbonPeriod;

class NewOrderCard extends Card
{
    public function getOptions(): array
    {
        if (is_plugin_active('payment')) {
            $query = Order::query()
                ->whereDate('ec_orders.created_at', '>=', $this->startDate)
                ->whereDate('ec_orders.created_at', '<=', $this->endDate)
                ->join('payments', 'payments.id', '=', 'ec_orders.payment_id')
                ->whereIn('payments.status', [PaymentStatusEnum::COMPLETED, PaymentStatusEnum::PENDING])
                ->whereDate('payments.created_at', '>=', $this->startDate)
                ->whereDate('payments.created_at', '<=', $this->endDate);
        } else {
            $query = Order::query()
                ->whereDate('created_at', '>=', $this->startDate)
                ->whereDate('created_at', '<=', $this->endDate);
        }

        $data = $query
            ->selectRaw(
                'count(ec_orders.id) as total, date_format(ec_orders.created_at, "' . $this->dateFormat . '") as period'
            )
            ->groupBy('period')
            ->pluck('total')
            ->toArray();

        return [
            'series' => [
                [
                    'data' => $data,
                ],
            ],
        ];
    }

    public function getViewData(): array
    {
        if (is_plugin_active('payment')) {
            $count = Order::query()
                ->whereDate('ec_orders.created_at', '>=', $this->startDate)
                ->whereDate('ec_orders.created_at', '<=', $this->endDate)
                ->join('payments', 'payments.id', '=', 'ec_orders.payment_id')
                ->whereIn('payments.status', [PaymentStatusEnum::COMPLETED, PaymentStatusEnum::PENDING])
                ->whereDate('payments.created_at', '>=', $this->startDate)
                ->whereDate('payments.created_at', '<=', $this->endDate)
                ->groupBy('payments.status')
                ->count();
        } else {
            $count = Order::query()
                ->whereDate('created_at', '>=', $this->startDate)
                ->whereDate('created_at', '<=', $this->endDate)
                ->count();
        }

        $startDate = clone $this->startDate;
        $endDate = clone $this->endDate;

        $currentPeriod = CarbonPeriod::create($startDate, $endDate);
        $previousPeriod = CarbonPeriod::create(
            $startDate->subDays($currentPeriod->count()),
            $endDate->subDays($currentPeriod->count())
        );

        if (is_plugin_active('payment')) {
            $currentOrders = Order::query()
                ->whereDate('ec_orders.created_at', '>=', $currentPeriod->getStartDate())
                ->whereDate('ec_orders.created_at', '<=', $currentPeriod->getEndDate())
                ->join('payments', 'payments.id', '=', 'ec_orders.payment_id')
                ->whereIn('payments.status', [PaymentStatusEnum::COMPLETED, PaymentStatusEnum::PENDING])
                ->whereDate('payments.created_at', '>=', $this->startDate)
                ->whereDate('payments.created_at', '<=', $this->endDate)
                ->groupBy('payments.status')
                ->count();

            $previousOrders = Order::query()
                ->whereDate('ec_orders.created_at', '>=', $previousPeriod->getStartDate())
                ->whereDate('ec_orders.created_at', '<=', $previousPeriod->getEndDate())
                ->join('payments', 'payments.id', '=', 'ec_orders.payment_id')
                ->whereIn('payments.status', [PaymentStatusEnum::COMPLETED, PaymentStatusEnum::PENDING])
                ->whereDate('payments.created_at', '>=', $this->startDate)
                ->whereDate('payments.created_at', '<=', $this->endDate)
                ->groupBy('payments.status')
                ->count();
        } else {
            $currentOrders = Order::query()
                ->whereDate('created_at', '>=', $currentPeriod->getStartDate())
                ->whereDate('created_at', '<=', $currentPeriod->getEndDate())
                ->count();

            $previousOrders = Order::query()
                ->whereDate('created_at', '>=', $previousPeriod->getStartDate())
                ->whereDate('created_at', '<=', $previousPeriod->getEndDate())
                ->count();
        }

        $result = $currentOrders - $previousOrders;

        $result > 0 ? $this->chartColor = '#4ade80' : $this->chartColor = '#ff5b5b';

        return array_merge(parent::getViewData(), [
            'content' => view(
                'plugins/ecommerce::reports.widgets.new-order-card',
                compact('count', 'result')
            )->render(),
        ]);
    }
}
