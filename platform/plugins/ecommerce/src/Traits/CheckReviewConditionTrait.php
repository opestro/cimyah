<?php

namespace Botble\Ecommerce\Traits;

use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\Review;

trait CheckReviewConditionTrait
{
    protected function checkReviewCondition(int|string $productId): array
    {
        $customerId = auth('customer')->id();

        $exists = Review::query()
            ->where([
                'customer_id' => $customerId,
                'product_id' => $productId,
            ])
            ->exists();

        if ($exists) {
            return [
                'error' => true,
                'message' => __('You have reviewed this product already!'),
            ];
        }

        if (EcommerceHelper::onlyAllowCustomersPurchasedToReview()) {
            $order = Order::query()
                ->where([
                    'user_id' => $customerId,
                    'status' => OrderStatusEnum::COMPLETED,
                ])
                ->join('ec_order_product', function ($query) use ($productId) {
                    $query
                        ->on('ec_order_product.order_id', 'ec_orders.id')
                        ->leftJoin('ec_product_variations', 'ec_product_variations.product_id', 'ec_order_product.product_id')
                        ->where(function ($query) use ($productId) {
                            $query->where('ec_product_variations.configurable_product_id', $productId)
                            ->orWhere('ec_order_product.product_id', $productId);
                        });
                })
                ->exists();

            if (! $order) {
                return [
                    'error' => true,
                    'message' => __('Please purchase the product for a review!'),
                ];
            }
        }

        return [
            'error' => false,
        ];
    }
}
