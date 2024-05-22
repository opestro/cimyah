<?php

namespace Botble\Ecommerce\Http\Controllers\Customers;

use Botble\Ecommerce\Enums\OrderCancellationReasonEnum;
use Botble\Ecommerce\Enums\OrderHistoryActionEnum;
use Botble\Ecommerce\Facades\InvoiceHelper;
use Botble\Ecommerce\Facades\OrderHelper;
use Botble\Ecommerce\Forms\Fronts\CancelOrderForm;
use Botble\Ecommerce\Http\Controllers\BaseController;
use Botble\Ecommerce\Http\Requests\Fronts\CancelOrderRequest;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\OrderHistory;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;

class OrderController extends BaseController
{
    public function __construct()
    {
        Theme::asset()
            ->add('customer-style', 'vendor/core/plugins/ecommerce/css/customer.css', ['bootstrap-css']);
    }

    public function index()
    {
        SeoHelper::setTitle(__('Orders'));

        $orders = Order::query()
            ->where([
                'user_id' => auth('customer')->id(),
                'is_finished' => 1,
            ])
            ->withCount(['products'])
            ->orderByDesc('created_at')
            ->paginate(10);

        Theme::breadcrumb()
            ->add(__('Orders'), route('customer.orders'));

        return Theme::scope(
            'ecommerce.customers.orders.list',
            compact('orders'),
            'plugins/ecommerce::themes.customers.orders.list'
        )->render();
    }

    public function show(int|string $id)
    {
        $order = Order::query()
            ->where([
                'id' => $id,
                'user_id' => auth('customer')->id(),
            ])
            ->with(['address', 'products'])
            ->firstOrFail();

        $cancelOrderForm = CancelOrderForm::createFromModel($order);

        SeoHelper::setTitle(__('Order detail :id', ['id' => $order->code]));

        Theme::breadcrumb()
            ->add(
                __('Order detail :id', ['id' => $order->code]),
                route('customer.orders.view', $id)
            );

        return Theme::scope(
            'ecommerce.customers.orders.view',
            compact('order', 'cancelOrderForm'),
            'plugins/ecommerce::themes.customers.orders.view'
        )->render();
    }

    public function destroy(int|string $id, CancelOrderRequest $request)
    {
        return $this->handleCancelOrder($id, $request->input('cancellation_reason'), $request->input('cancellation_reason_description'));
    }

    public function print(int|string $id, Request $request)
    {
        $order = Order::query()
            ->where([
                'id' => $id,
                'user_id' => auth('customer')->id(),
            ])
            ->firstOrFail();

        if (! $order->isInvoiceAvailable()) {
            abort(404);
        }

        if ($request->input('type') == 'print') {
            return InvoiceHelper::streamInvoice($order->invoice);
        }

        return InvoiceHelper::downloadInvoice($order->invoice);
    }

    public function getCancelOrder(int|string $id)
    {
        return $this->handleCancelOrder($id);
    }

    protected function handleCancelOrder(int|string $id, ?string $reason = null, ?string $reasonDescription = null)
    {
        /** @var \Botble\Ecommerce\Models\Order $order */
        $order = Order::query()
            ->where([
                'id' => $id,
                'user_id' => auth('customer')->id(),
            ])
            ->with(['address', 'products'])
            ->firstOrFail();

        if (! $order->canBeCanceled()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/ecommerce::order.cancel_error'));
        }

        OrderHelper::cancelOrder($order, $reason, $reasonDescription);

        $customerName = $order->address->name ?: $order->user->name;

        $description = match (true) {
            $reason != OrderCancellationReasonEnum::OTHER => __('Order was cancelled by customer :customer with reason :reason', [
                'customer' => $customerName,
                'reason' => OrderCancellationReasonEnum::getLabel($reason),
            ]),
            $reason == OrderCancellationReasonEnum::OTHER => __('Order was cancelled by customer :customer with reason :reason', [
                'customer' => $customerName,
                'reason' => $reasonDescription,
            ]),
            default => __('Order was cancelled by customer :customer', ['customer' => $customerName]),
        };

        OrderHistory::query()->create([
            'action' => OrderHistoryActionEnum::CANCEL_ORDER,
            'description' => $description,
            'order_id' => $order->getKey(),
        ]);

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/ecommerce::order.cancel_success'));
    }
}
