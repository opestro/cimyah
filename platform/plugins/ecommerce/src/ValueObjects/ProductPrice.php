<?php

namespace Botble\Ecommerce\ValueObjects;

use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductVariation;
use Illuminate\Database\Eloquent\Collection;

class ProductPrice
{
    protected mixed $state;

    protected Collection $variations;

    protected ?Product $minimumVariation;

    protected ?Product $maximumVariation;

    public function __construct(
        protected Product $product,
    ) {
    }

    public static function make(Product $product): static
    {
        return new static($product);
    }

    public function getPrice(): float
    {
        $price = $this->product->front_sale_price_with_taxes != $this->product->price_with_taxes
            ? $this->product->front_sale_price_with_taxes
            : $this->product->price_with_taxes;

        return $this->applyFilters('price', 'value', (float) $price);
    }

    public function displayAsText(): string
    {
        $priceText = format_price($this->getPrice());

        return $this->applyFilters('price', 'display_as_text', $priceText);
    }

    public function displayAsHtml(...$args): string
    {
        return view(EcommerceHelper::viewPath('products.partials.price'), [
            'product' => $this->product,
            ...$args,
        ])->render();
    }

    public function getPriceOriginal(): float
    {
        return $this->applyFilters(
            'price_original',
            'value',
            (float) $this->product->price_with_taxes
        );
    }

    public function displayPriceOriginalAsText(): string
    {
        $priceText = format_price($this->getPriceOriginal());

        return $this->applyFilters('original_price', 'display_as_text', $priceText);
    }

    public function getPriceMinimum(): float
    {
        $minimumVariation = $this->getMinimumVariation();

        return $minimumVariation
            ? $minimumVariation->price()->getPrice()
            : $this->getPrice();
    }

    public function displayPriceMinimumAsText(): string
    {
        return format_price($this->getPriceMinimum());
    }

    public function getPriceMaximum(): float
    {
        $maximumVariation = $this->getMaximumVariation();

        return $maximumVariation
            ? $maximumVariation->price()->getPrice()
            : $this->getPrice();
    }

    public function displayPriceMaximumAsText(): string
    {
        return format_price($this->getPriceMaximum());
    }

    protected function getVariations(): Collection
    {
        $this->product->loadMissing('variations.product');

        return $this->variations ??= $this->product->variations;
    }

    protected function getMinimumVariation(): ?Product
    {
        return $this->minimumVariation ??= $this
            ->getVariations()
            ->sortBy(function (ProductVariation $productVariation) {
                return $productVariation->product->price()->getPrice();
            })
        ->first()
        ?->product;
    }

    protected function getMaximumVariation(): ?Product
    {
        return $this->maximumVariation ??= $this
            ->getVariations()
            ->sortByDesc(function (ProductVariation $productVariation) {
                return $productVariation->product->price()->getPrice();
            })
            ->first()
            ?->product;
    }

    protected function applyFilters(string $name, string $kind, mixed $value): mixed
    {
        return apply_filters(
            "product_prices_{$name}_$kind",
            $value,
            $this->product
        );
    }
}
