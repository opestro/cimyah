<?php

namespace Botble\Ecommerce\Services;

use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Models\Product;

class ProductWishlistService
{
    public function handle(Product $product): bool
    {
        $guard = auth('customer');

        if (! $guard->check()) {
            $instance = Cart::instance('wishlist');

            $wishlist = $instance->search(fn ($cartItem) => $cartItem->id == $product->getKey());

            if ($wishlist->isEmpty()) {
                $instance
                    ->add($product->getKey(), $product->name, 1, $product->front_sale_price)
                    ->associate(Product::class);

                return true;
            }

            $wishlist->each(fn ($cartItem, $rowId) => $instance->remove($rowId));

            return false;
        }

        $customer = $guard->user();

        $data = [
            'product_id' => $product->getKey(),
        ];

        if (is_added_to_wishlist($product->getKey())) {
            $customer->wishlist()->where($data)->delete();

            return false;
        }

        $customer->wishlist()->create($data);

        return true;
    }
}
