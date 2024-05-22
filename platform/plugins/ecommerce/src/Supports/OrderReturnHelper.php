<?php

namespace Botble\Ecommerce\Supports;

use Botble\Base\Facades\EmailHandler;
use Botble\Ecommerce\Enums\OrderReturnHistoryActionEnum;
use Botble\Ecommerce\Enums\OrderReturnStatusEnum;
use Botble\Ecommerce\Events\OrderReturnedEvent;
use Botble\Ecommerce\Facades\OrderHelper as OrderHelperFacade;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\OrderProduct;
use Botble\Ecommerce\Models\OrderReturn;
use Botble\Ecommerce\Models\OrderReturnItem;
use Botble\Ecommerce\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderReturnHelper
{
    public function returnOrder(Order $order, array $data): array
    {
        $orderReturnData = [
            'order_id' => $order->getKey(),
            'store_id' => $order->store_id,
            'user_id' => $order->user_id,
            'order_status' => $order->status,
            'return_status' => OrderReturnStatusEnum::PENDING,
        ];

        if (! empty($data['reason'])) {
            $orderReturnData['reason'] = $data['reason'];
        }

        try {
            DB::beginTransaction();

            $orderReturn = OrderReturn::query()->create($orderReturnData);

            $orderReturnItemData = [];

            $orderProductIds = [];

            foreach ($data['items'] as $returnItem) {
                $orderProduct = OrderProduct::query()->find($returnItem['order_item_id']);
                if (! $orderProduct) {
                    continue;
                }

                $orderReturnItemData[] = [
                    'order_return_id' => $orderReturn->id,
                    'order_product_id' => $returnItem['order_item_id'],
                    'product_id' => $orderProduct->product_id,
                    'product_name' => $orderProduct->product_name,
                    'product_image' => $orderProduct->product_image,
                    'price' => $orderProduct->price,
                    'qty' => $returnItem['qty'],
                    'reason' => $returnItem['reason'] ?? null,
                    'refund_amount' => $returnItem['refund_amount'] ?? null,
                    'created_at' => Carbon::now(),
                ];

                $orderProductIds[] = $orderProduct->product_id;
            }

            OrderReturnItem::query()->insert($orderReturnItemData);

            $orderReturn->histories()->create([
                'action' => OrderReturnHistoryActionEnum::CREATED,
                'description' => __('Request return order with reason: :reason', ['reason' => $orderReturn->reason->label()]),
            ]);

            event(new OrderReturnedEvent($orderReturn));

            $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
            if ($mailer->templateEnabled('order-return-request')) {
                $mailer = OrderHelperFacade::setEmailVariables($order);

                $orderProducts = OrderProduct::query()
                    ->where('order_id', $order->getKey())
                    ->whereIn('product_id', $orderProductIds)
                    ->get();

                $order->dont_show_order_info_in_product_list = true;

                $mailer->setVariableValues([
                    'list_order_products' => view('plugins/ecommerce::emails.partials.order-detail', [
                        'order' => $order,
                        'products' => $orderProducts,
                    ])
                        ->render(),
                    'return_reason' => $orderReturn->reason->label(),
                ]);

                $mailer->sendUsingTemplate('order-return-request', get_admin_email()->toArray());
            }

            DB::commit();

            return [true, $orderReturn, null];
        } catch (Throwable $exception) {
            DB::rollBack();

            Log::error($exception->getMessage(), [
                'file' => $exception->getFile(),
                'function' => __FUNCTION__,
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]);

            return [false, [], $exception->getMessage()];
        }
    }

    public function cancelReturnOrder(OrderReturn $orderReturn, ?string $reason = null): array
    {
        $orderReturn->update([
            'return_status' => OrderReturnStatusEnum::CANCELED,
        ]);

        $orderReturn->histories()->create([
            'user_id' => auth()->id(),
            'action' => OrderReturnHistoryActionEnum::REJECTED,
            'description' => __('Cancel return order with reason: :reason', ['reason' => $reason]),
            'reason' => $reason,
        ]);

        return [true, $orderReturn];
    }

    public function updateReturnOrder(OrderReturn $orderReturn, array $data): array
    {
        try {
            DB::beginTransaction();

            $orderReturn->update($data);

            if ($orderReturn->return_status == OrderReturnStatusEnum::COMPLETED) {
                foreach ($orderReturn->items as $item) {
                    $product = Product::query()->find($item->product_id);
                    if ($product) {
                        $product->quantity += $item->qty;
                        $product->save();

                        if ($product->is_variation) {
                            $originalProduct = $product->original_product;
                            if ($originalProduct->id != $product->id) {
                                $originalProduct->quantity += $item->qty;
                                $originalProduct->save();
                            }
                        }
                    }
                }

                do_action(ACTION_AFTER_ORDER_RETURN_STATUS_COMPLETED, $orderReturn, $data);
            }

            $orderReturn->histories()->create([
                'user_id' => auth()->id(),
                'action' => match ($orderReturn->return_status->getValue()) {
                    OrderReturnStatusEnum::COMPLETED => OrderReturnHistoryActionEnum::MARK_AS_COMPLETED,
                    OrderReturnStatusEnum::PROCESSING => OrderReturnHistoryActionEnum::APPROVED,
                    default => OrderReturnHistoryActionEnum::REJECTED,
                },
                'description' => __('Update return order status to: :status', ['status' => $orderReturn->return_status->label()]),
                'reason' => $data['description'] ?? null,
            ]);

            $customer = $orderReturn->customer;

            EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME)
                ->setVariableValues([
                    'customer_name' => $customer->name,
                    'order_id' => $orderReturn->order->code,
                    'description' => $data['description'] ?? null,
                    'status' => $orderReturn->return_status->label(),
                ])
                ->sendUsingTemplate('order-return-status-updated', $customer->email);

            DB::commit();

            return [true, $orderReturn];
        } catch (Throwable $exception) {
            DB::rollBack();

            Log::error($exception->getMessage(), [
                'file' => $exception->getFile(),
                'function' => __FUNCTION__,
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]);

            return [false, []];
        }
    }
}
