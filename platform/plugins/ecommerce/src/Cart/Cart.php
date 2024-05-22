<?php

namespace Botble\Ecommerce\Cart;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Cart\Contracts\Buyable;
use Botble\Ecommerce\Cart\Exceptions\CartAlreadyStoredException;
use Botble\Ecommerce\Cart\Exceptions\UnknownModelException;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Closure;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Events\NullDispatcher;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Cart
{
    protected static Dispatcher $dispatcher;

    public const DEFAULT_INSTANCE = 'cart';

    protected string $instance;

    protected ?Collection $products = null;

    protected float $weight = 0;

    protected array $counts = [];

    public function __construct(protected SessionManager $session, Dispatcher $events)
    {
        static::$dispatcher = $events;

        $this->instance(self::DEFAULT_INSTANCE);
    }

    public function instance(string $instance = null): self
    {
        $instance = $instance ?: self::DEFAULT_INSTANCE;

        $this->instance = sprintf('%s.%s', 'cart', $instance);

        return $this;
    }

    public function getLastUpdatedAt(): ?CarbonInterface
    {
        return $this->session->get($this->instance . '_updated_at');
    }

    public function add($id, $name = null, $qty = null, $price = null, array $options = [])
    {
        if ($this->isMulti($id)) {
            return array_map(function ($item) {
                return $this->add($item);
            }, $id);
        }

        $cartItem = $this->createCartItem($id, $name, $qty, $price, $options);

        $content = $this->getContent();

        if ($content->has($cartItem->rowId)) {
            $cartItem->qty += $content->get($cartItem->rowId)->qty;
        }

        $content->put($cartItem->rowId, $cartItem);

        $this->putToSession($content);

        static::dispatchEvent('cart.added', $cartItem);

        return $cartItem;
    }

    public function addQuietly($id, $name = null, $qty = null, $price = null, array $options = [])
    {
        return static::withoutEvents(
            fn () => $this->add($id, $name, $qty, $price, $options)
        );
    }

    protected function isMulti($item): bool
    {
        if (! is_array($item)) {
            return false;
        }

        $item = reset($item);

        return is_array($item) || $item instanceof Buyable;
    }

    protected function createCartItem($id, $name, $qty, $price, array $options): CartItem
    {
        if (
            EcommerceHelper::isEnabledProductOptions() &&
            ($productOptions = Arr::get($options, 'options', [])) &&
            is_array($productOptions)
        ) {
            $price = $this->getPriceByOptions($price, $productOptions);
        }

        if ($id instanceof Buyable) {
            $cartItem = CartItem::fromBuyable($id, $qty ?: []);
            $cartItem->setQuantity($name ?: 1);
            $cartItem->associate($id);
        } elseif (is_array($id)) {
            $cartItem = CartItem::fromArray($id);
            $cartItem->setQuantity($id['qty']);
        } else {
            $cartItem = CartItem::fromAttributes($id, $name, $price, $options);
            $cartItem->setQuantity($qty);
        }

        $cartItem->setTaxRate($options['taxRate'] ?? 0);

        return $cartItem;
    }

    public function getPriceByOptions(float|int $price, array $options = []): float|int
    {
        $basePrice = $price;
        foreach (Arr::get($options, 'optionCartValue', []) as $value) {
            if (is_array($value)) {
                foreach ($value as $valueItem) {
                    if ($valueItem['affect_type'] == 1) {
                        $valueItem['affect_price'] = ($basePrice * $valueItem['affect_price']) / 100;
                    }
                    $price += $valueItem['affect_price'];
                }
            } else {
                if (Arr::get($value, 'option_type') == 'field') {
                    continue;
                }

                if ($value['affect_type'] == 1) {
                    $value['affect_price'] = ($basePrice * $value['affect_price']) / 100;
                }

                $price += $value['affect_price'];
            }
        }

        return $price;
    }

    protected function getContent(): Collection
    {
        return $this->session->has($this->instance)
            ? $this->session->get($this->instance)
            : new Collection();
    }

    public function putToSession($content): static
    {
        $this->setLastUpdatedAt();

        $this->session->put($this->instance, $content);

        return $this;
    }

    public function setLastUpdatedAt(): void
    {
        $this->session->put($this->instance . '_updated_at', Carbon::now());
    }

    public function update(string $rowId, int|Buyable|array $qty): bool|CartItem|null
    {
        $cartItem = $this->get($rowId);

        if ($qty instanceof Buyable) {
            $cartItem->updateFromBuyable($qty);
        } elseif (is_array($qty)) {
            $cartItem->updateFromArray($qty);
        } else {
            $cartItem->qty = $qty;
        }

        $content = $this->getContent();

        if ($rowId !== $cartItem->rowId) {
            $content->pull($rowId);

            if ($content->has($cartItem->rowId)) {
                $existingCartItem = $this->get($cartItem->rowId);
                $cartItem->setQuantity((int) $existingCartItem->qty + (int) $cartItem->qty);
            }
        }

        if ($cartItem->qty <= 0) {
            $this->remove($cartItem->rowId);

            return false;
        }

        $content->put($cartItem->rowId, $cartItem);

        $cartItem->updated_at = Carbon::now();

        static::dispatchEvent('cart.updated', $cartItem);

        $this->putToSession($content);

        return $cartItem;
    }

    public function updateQuietly($rowId, $qty)
    {
        return static::withoutEvents(fn () => $this->update($rowId, $qty));
    }

    public function get(string $rowId): ?CartItem
    {
        $content = $this->getContent();

        if (! $content->has($rowId)) {
            return null;
        }

        return $content->get($rowId);
    }

    public function remove(string $rowId): void
    {
        $cartItem = $this->get($rowId);

        $content = $this->getContent();

        $content->pull($cartItem->rowId);

        static::dispatchEvent('cart.removed', $cartItem);

        $this->putToSession($content);
    }

    public function removeQuietly($rowId)
    {
        return static::withoutEvents(fn () => $this->remove($rowId));
    }

    public function destroy(): void
    {
        $this->session->remove($this->instance);
    }

    public function count(): int
    {
        if (isset($this->counts[$this->instance])) {
            return $this->counts[$this->instance];
        }

        if ($this->instance == 'cart.wishlist' && auth('customer')->check()) {
            $this->counts[$this->instance] = auth('customer')->user()->wishlist()->count();
        } else {
            $content = $this->getContent();

            $this->counts[$this->instance] = $content->sum('qty');
        }

        return $this->counts[$this->instance];
    }

    public function isNotEmpty(): bool
    {
        return $this->getContent()->isNotEmpty();
    }

    public function isEmpty(): bool
    {
        return $this->getContent()->isEmpty();
    }

    public function countByItems($content): float|int
    {
        return $content->sum('qty');
    }

    public function rawTotal(): float
    {
        $content = $this->getContent();

        return $content->reduce(function ($total, ?CartItem $cartItem) {
            if (! $cartItem) {
                return 0;
            }

            if (! EcommerceHelper::isTaxEnabled()) {
                return $total + $cartItem->qty * $cartItem->price;
            }

            return $total + ($cartItem->qty * ($cartItem->priceTax == 0 ? $cartItem->price : $cartItem->priceTax));
        }, 0);
    }

    public function rawTotalByItems($content): float
    {
        return $content->reduce(function ($total, ?CartItem $cartItem) {
            if (! $cartItem) {
                return 0;
            }

            if (! EcommerceHelper::isTaxEnabled()) {
                return $total + $cartItem->qty * $cartItem->price;
            }

            return $total + ($cartItem->qty * ($cartItem->priceTax == 0 ? $cartItem->price : $cartItem->priceTax));
        }, 0);
    }

    public function rawTaxByItems($content): float
    {
        if (! EcommerceHelper::isTaxEnabled()) {
            return 0;
        }

        return $content->reduce(function ($tax, CartItem $cartItem) {
            return $tax + ($cartItem->qty * $cartItem->tax);
        }, 0);
    }

    public function rawSubTotal(): float
    {
        $content = $this->getContent();

        return $content->reduce(function ($subTotal, CartItem $cartItem) {
            return $subTotal + ($cartItem->qty * $cartItem->price);
        }, 0);
    }

    public function rawSubTotalByItems($content): float
    {
        return $content->reduce(function ($subTotal, CartItem $cartItem) {
            return $subTotal + ($cartItem->qty * $cartItem->price);
        }, 0);
    }

    public function rawQuantityByItemId($id): int
    {
        return $this->getContent()->reduce(function ($qty, CartItem $cartItem) use ($id) {
            return $cartItem->id == $id ? $qty + $cartItem->qty : $qty;
        }, 0);
    }

    public function rawTotalQuantity(): int
    {
        $content = $this->getContent();

        return $content->reduce(function ($qty, CartItem $cartItem) {
            return $qty + $cartItem->qty;
        }, 0);
    }

    public function search(Closure $search): Collection
    {
        $content = $this->getContent();

        return $content->filter($search);
    }

    public function associate(string $rowId, BaseModel $model): void
    {
        if (is_string($model) && ! class_exists($model)) {
            throw new UnknownModelException('The supplied model ' . $model . ' does not exist.');
        }

        $cartItem = $this->get($rowId);

        $cartItem->associate($model);

        $content = $this->getContent();

        $content->put($cartItem->rowId, $cartItem);

        $this->putToSession($content);
    }

    public function setTax(string $rowId, float $taxRate): void
    {
        $cartItem = $this->get($rowId);

        $cartItem->setTaxRate($taxRate);

        $cartItem->updated_at = Carbon::now();

        $content = $this->getContent();

        $content->put($cartItem->rowId, $cartItem);

        $this->putToSession($content);
    }

    public function store(string $identifier): void
    {
        $content = $this->getContent();

        if ($this->storedCartWithIdentifierExists($identifier)) {
            throw new CartAlreadyStoredException('A cart with identifier ' . $identifier . ' was already stored.');
        }

        $this->getConnection()->table($this->getTableName())->insert([
            'identifier' => $identifier,
            'instance' => $this->currentInstance(),
            'content' => serialize($content),
        ]);

        static::dispatchEvent('cart.stored');
    }

    public function storeQuietly($identifier)
    {
        return static::withoutEvents(fn () => $this->store($identifier));
    }

    protected function storedCartWithIdentifierExists(string $identifier): bool
    {
        return $this->getConnection()->table($this->getTableName())->where('identifier', $identifier)->exists();
    }

    protected function getConnection(): Connection
    {
        $connectionName = $this->getConnectionName();

        return app(DatabaseManager::class)->connection($connectionName);
    }

    protected function getConnectionName(): string
    {
        $connection = config('plugins.ecommerce.cart.database.connection');

        return empty($connection) ? config('database.default') : $connection;
    }

    protected function getTableName(): string
    {
        return config('plugins.ecommerce.cart.database.table', 'ec_cart');
    }

    public function currentInstance(): string
    {
        return str_replace('cart.', '', $this->instance);
    }

    public function restore(string $identifier): void
    {
        if (! $this->storedCartWithIdentifierExists($identifier)) {
            return;
        }

        $stored = $this->getConnection()->table($this->getTableName())
            ->where('identifier', $identifier)->first();

        $storedContent = unserialize($stored->content);

        $currentInstance = $this->currentInstance();

        $this->instance($stored->instance);

        $content = $this->getContent();

        foreach ($storedContent as $cartItem) {
            $content->put($cartItem->rowId, $cartItem);
        }

        static::dispatchEvent('cart.restored');

        $this->putToSession($content);

        $this->instance($currentInstance);

        $this->getConnection()->table($this->getTableName())
            ->where('identifier', $identifier)->delete();
    }

    public function restoreQuietly($identifier)
    {
        return static::withoutEvents(fn () => $this->restore($identifier));
    }

    public function __get($attribute)
    {
        if ($attribute === 'total') {
            return $this->total();
        }

        if ($attribute === 'tax') {
            return $this->tax();
        }

        if ($attribute === 'subtotal') {
            return $this->subtotal();
        }

        return null;
    }

    public function total(): string
    {
        $content = $this->getContent();

        $total = $content->reduce(function ($total, ?CartItem $cartItem) {
            if (! $cartItem) {
                return 0;
            }

            return $total + ($cartItem->qty * ($cartItem->priceTax == 0 ? $cartItem->price : $cartItem->priceTax));
        }, 0);

        return format_price($total);
    }

    public function tax(): float|string
    {
        if (! EcommerceHelper::isTaxEnabled()) {
            return 0;
        }

        return format_price($this->rawTax());
    }

    public function rawTax(): float
    {
        if (! EcommerceHelper::isTaxEnabled()) {
            return 0;
        }

        $content = $this->getContent();

        return $content->reduce(function ($tax, CartItem $cartItem) {
            return $tax + ($cartItem->qty * $cartItem->tax);
        }, 0);
    }

    public function subtotal(): string
    {
        $content = $this->getContent();

        $subTotal = $content->reduce(function ($subTotal, CartItem $cartItem) {
            return $subTotal + ($cartItem->qty * $cartItem->price);
        }, 0);

        return format_price($subTotal);
    }

    public function products(): Collection|EloquentCollection
    {
        if ($this->products) {
            return $this->products;
        }

        $cartContent = $this->instance('cart')->content();
        $productIds = $cartContent->pluck('id')->toArray();
        $products = collect();
        $weight = 0;
        if ($productIds) {
            $with = [
                'variationInfo',
                'variationInfo.configurableProduct',
                'variationInfo.configurableProduct.slugable',
                'variationProductAttributes',
            ];

            if (is_plugin_active('marketplace')) {
                $with = array_merge($with, [
                    'variationInfo.configurableProduct.store',
                    'variationInfo.configurableProduct.store.slugable',
                ]);
            }

            $products = app(ProductInterface::class)->getProducts([
                'condition' => [
                    ['ec_products.id', 'IN', $productIds],
                ],
                'with' => $with,
            ]);
        }

        $productsInCart = new EloquentCollection();

        if ($products->isNotEmpty()) {
            foreach ($cartContent as $cartItem) {
                $product = $products->firstWhere('id', $cartItem->id);
                if (! $product || $product->original_product->status != BaseStatusEnum::PUBLISHED) {
                    $this->remove($cartItem->rowId);
                } else {
                    $productInCart = clone $product;
                    $productInCart->cartItem = $cartItem;
                    $productsInCart->push($productInCart);
                    $weight += $product->weight * $cartItem->qty;
                }
            }
        }

        $weight = EcommerceHelper::validateOrderWeight($weight);

        $this->products = $productsInCart;
        $this->weight = $weight;

        if ($this->products->isEmpty()) {
            $this->instance('cart')->destroy();
        }

        return $this->products;
    }

    public function content(): Collection
    {
        if (empty($this->session->get($this->instance))) {
            return collect();
        }

        return $this->session->get($this->instance);
    }

    public function weight(): float
    {
        return EcommerceHelper::validateOrderWeight($this->weight);
    }

    public static function getEventDispatcher(): Dispatcher
    {
        return static::$dispatcher;
    }

    public static function setEventDispatcher(Dispatcher $dispatcher): void
    {
        static::$dispatcher = $dispatcher;
    }

    public static function withoutEvents(callable $callback)
    {
        $dispatcher = static::getEventDispatcher();

        static::setEventDispatcher(new NullDispatcher($dispatcher));

        try {
            return $callback();
        } finally {
            static::setEventDispatcher($dispatcher);
        }
    }

    protected static function dispatchEvent(string $event, $parameters = []): void
    {
        if (isset(static::$dispatcher)) {
            static::$dispatcher->dispatch($event, $parameters);
        }
    }

    public function refresh(): void
    {
        $cart = $this->instance('cart');

        if ($cart->isEmpty()) {
            return;
        }

        $ids = $cart->content()->pluck('id')->toArray();

        $products = get_products([
            'condition' => [
                ['ec_products.id', 'IN', $ids],
            ],
        ]);

        if ($products->isEmpty()) {
            return;
        }

        foreach ($cart->content() as $rowId => $cartItem) {
            $product = $products->firstWhere('id', $cartItem->id);
            if (! $product || $product->original_product->status != BaseStatusEnum::PUBLISHED) {
                $this->remove($cartItem->rowId);
            } else {
                $cart->removeQuietly($rowId);

                $parentProduct = $product->original_product;

                $options = $cartItem->options->toArray();
                $options['image'] = $product->image ?: $parentProduct->image;

                $options['taxRate'] = $cartItem->getTaxRate();

                $cart->addQuietly(
                    $cartItem->id,
                    $cartItem->name,
                    $cartItem->qty,
                    $product->front_sale_price,
                    $options
                );
            }
        }
    }

    public function taxClassesName(): string
    {
        $taxes = [];

        foreach ($this->content() as $cartItem) {
            if (! $cartItem->taxRate || ! $cartItem->options->taxClasses) {
                continue;
            }

            foreach ($cartItem->options->taxClasses as $taxName => $taxRate) {
                $taxes[] = $taxName . ' - ' . $taxRate . '%';
            }
        }

        return implode(', ', array_unique($taxes));
    }
}
