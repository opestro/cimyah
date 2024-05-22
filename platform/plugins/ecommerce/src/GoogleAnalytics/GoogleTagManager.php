<?php

namespace Botble\Ecommerce\GoogleAnalytics;

use Botble\Ecommerce\Cart\CartItem;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\Product;
use Botble\SeoHelper\Facades\SeoHelper;
use Illuminate\Support\Str;

class GoogleTagManager
{
    protected array $dataLayer = [];

    public function isEnabled(): bool
    {
        $type = setting('google_tag_manager_type', 'code');

        return get_ecommerce_setting('google_tag_manager_enabled', false)
            && ($type === 'code' && setting('google_tag_manager_code'))
            || ($type === 'id' && setting('google_tag_manager_id'));
    }

    public function viewItemList(array $items, string $name, array $attributes = []): self
    {
        $this->pushEvent('view_item_list', $items, [
            'item_list_id' => Str::snake($name),
            'item_list_name' => $name,
            ...$attributes,
        ]);

        return $this;
    }

    public function viewItem(Product $item, array $attributes = []): self
    {
        $this->pushEvent('view_item', [$item], [
            'currency' => get_application_currency()->title,
            'value' => $item->price,
            ...$attributes,
        ]);

        return $this;
    }

    public function addToCart(Product $item, int $quantity, float $value, array $attributes = []): self
    {
        $item->quantity = $quantity;

        $this->pushEvent('add_to_cart', [$item], [
            'currency' => get_application_currency()->title,
            'value' => $value,
            ...$attributes,
        ]);

        return $this;
    }

    public function viewCart(array $attributes = []): self
    {
        $cart = Cart::instance('cart');
        $products = $cart->products();

        $items = $cart->content()->map(function ($item) use ($products) {
            $product = $products->find($item->id)->original_product;

            return new GoogleTagItem(
                $item->id,
                $item->name,
                $item->price,
                $item->qty,
                [
                    ...$this->formatItemAttributes($product),
                    'item_variant' => $item->options->attributes,
                ]
            );
        })->values()->all();

        $this->pushEvent('view_cart', $items, [
            'currency' => get_application_currency()->title,
            'value' => $cart->rawSubTotal(),
            ...$attributes,
        ]);

        return $this;
    }

    public function removeFromCart(CartItem $cartItem, array $attributes = []): self
    {
        $product = Product::query()->find($cartItem->id)->original_product;
        $product->quantity = $cartItem->qty;

        $this->pushEvent('remove_from_cart', [$product->original_product], [
            'currency' => get_application_currency()->title,
            'value' => $cartItem->price * $cartItem->qty,
            ...$attributes,
        ]);

        return $this;
    }

    public function beginCheckout(array $items, float $value, ?string $coupon = null, array $attributes = []): self
    {
        $this->pushEvent('begin_checkout', $items, [
            'currency' => get_application_currency()->title,
            'value' => $value,
            'coupon' => $coupon,
            ...$attributes,
        ]);

        return $this;
    }

    public function purchase(Order $order, array $attributes = [], array $products = []): self
    {
        $products = $products ?: $order->getOrderProducts()->all();

        $this->pushEvent('purchase', $products, [
            'transaction_id' => $order->code,
            'currency' => get_application_currency()->title,
            'value' => $order->sub_total,
            'tax' => $order->tax_amount,
            'shipping' => $order->shipping_amount,
            'coupon' => $order->coupon_code,
            ...$attributes,
        ]);

        return $this;
    }

    public function refund(Order $order, array $attributes = []): self
    {
        $products = $order->getOrderProducts();

        $this->pushEvent('refund', $products->all(), [
            'transaction_id' => $order->code,
            'currency' => get_application_currency()->title,
            'value' => $order->sub_total,
            'tax' => $order->tax_amount,
            'shipping' => $order->shipping_amount,
            'coupon' => $order->coupon_code,
            ...$attributes,
        ]);

        return $this;
    }

    public function pushEvent(string $event, array $items, array $attributes = []): self
    {
        if (! $this->isEnabled()) {
            return $this;
        }

        $items = array_map(fn (GoogleTagItem $item) => $item->toArray(), $this->formatItems($items));

        $data = apply_filters('ecommerce.google_tag_manager.push_event', [
            ...$attributes,
            'items' => $items,
        ], $event, $items, $attributes);

        $this->dataLayer[$event] = $data;

        return $this;
    }

    public function render(): string
    {
        if (empty($this->dataLayer)) {
            return '';
        }

        $gtag = '';

        foreach ($this->dataLayer as $event => $data) {
            $gtag .= "gtag('event', '$event', " . json_encode($data) . ');';
        }

        return <<<HTML
            <script>
                if (typeof gtag !== "undefined") {
                    $gtag
                }
            </script>
        HTML;
    }

    public function pushScriptsToFooter(): void
    {
        if (! $this->isEnabled()) {
            return;
        }

        add_filter(THEME_FRONT_FOOTER, function (?string $html) {
            return $html . view(EcommerceHelper::viewPath('includes.gtm-script'))->render() . $this->render();
        }, 999);

        add_filter('ecommerce_checkout_footer', function (?string $html) {
            return $html . SeoHelper::meta()->getAnalytics()->render() . $this->render();
        }, 999);
    }

    public function formatItems(array $items): array
    {
        return array_map(function ($item) {
            if ($item instanceof GoogleTagItem) {
                return $item;
            }

            return new GoogleTagItem(
                id: $item->id,
                name: $item->name,
                price: $item->price ?: 0,
                quantity: $item->quantity ?? null,
                attributes: $this->formatItemAttributes($item),
            );
        }, $items);
    }

    public function formatItemAttributes(Product $product): array
    {
        $attributes = [];

        if ($product->brand) {
            $attributes['item_brand'] = $product->brand->name;
        }

        if ($product->categories) {
            foreach ($product->categories as $key => $category) {
                $keyName = $key === 0 ? '' : $key + 1;
                $attributes["item_category$keyName"] = $category->name;
            }
        }

        return $attributes;
    }
}
