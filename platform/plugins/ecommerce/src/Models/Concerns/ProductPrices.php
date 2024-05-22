<?php

namespace Botble\Ecommerce\Models\Concerns;

use Botble\Ecommerce\Enums\DiscountTypeOptionEnum;
use Botble\Ecommerce\Facades\Discount as DiscountFacade;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Facades\FlashSale as FlashSaleFacade;
use Botble\Ecommerce\Services\Products\ProductPriceService;
use Botble\Ecommerce\ValueObjects\ProductPrice;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait ProductPrices
{
    protected float $originalPrice = 0;

    protected float $finalPrice = 0;

    protected ProductPrice $priceObject;

    public function price(): ProductPrice
    {
        return $this->priceObject ??= ProductPrice::make($this);
    }

    protected function frontSalePrice(): Attribute
    {
        return Attribute::get(
            fn () => app(ProductPriceService::class)->getPrice($this)
        );
    }

    protected function originalPrice(): Attribute
    {
        return Attribute::get(
            fn () => app(ProductPriceService::class)->getOriginalPrice($this)
        );
    }

    public function getFlashSalePrice(): float|false|null
    {
        if (! FlashSaleFacade::isEnabled()) {
            return 0;
        }

        $flashSale = FlashSaleFacade::getFacadeRoot()->flashSaleForProduct($this);

        if ($flashSale && $flashSale->pivot->quantity > $flashSale->pivot->sold) {
            return $flashSale->pivot->price;
        }

        return $this->price;
    }

    public function getDiscountPrice(): float|int|null
    {
        $promotion = DiscountFacade::getFacadeRoot()
            ->promotionForProduct([$this->id, $this->original_product->id]);

        if (! $promotion) {
            return $this->price;
        }

        $price = $this->price;
        switch ($promotion->type_option) {
            case DiscountTypeOptionEnum::SAME_PRICE:
                $price = $promotion->value;

                break;
            case DiscountTypeOptionEnum::AMOUNT:
                $price = $price - $promotion->value;
                if ($price < 0) {
                    $price = 0;
                }

                break;
            case DiscountTypeOptionEnum::PERCENTAGE:
                $price = $price - ($price * $promotion->value / 100);
                if ($price < 0) {
                    $price = 0;
                }

                break;
        }

        return $price;
    }

    protected function frontSalePriceWithTaxes(): Attribute
    {
        return Attribute::get(function (): ?float {
            if (! EcommerceHelper::isDisplayProductIncludingTaxes()) {
                return $this->front_sale_price;
            }

            return $this->front_sale_price + $this->front_sale_price * ($this->total_taxes_percentage / 100);
        });
    }

    protected function priceWithTaxes(): Attribute
    {
        return Attribute::get(function (): ?float {
            if (! EcommerceHelper::isDisplayProductIncludingTaxes()) {
                return $this->price;
            }

            return $this->price + $this->price * ($this->total_taxes_percentage / 100);
        });
    }

    protected function priceInTable(): Attribute
    {
        return Attribute::get(function () {
            $price = format_price($this->front_sale_price);

            if ($this->front_sale_price != $this->price) {
                $price .= sprintf(' <del class="text-danger">%s</del>', format_price($this->price));
            }

            return $price;
        });
    }

    protected function salePercent(): Attribute
    {
        return Attribute::get(function (): int {
            if ($this->front_sale_price == 0 && $this->price !== 0) {
                return 100;
            }

            if (! $this->front_sale_price || ! $this->price) {
                return 0;
            }

            return (int) round(($this->price - $this->front_sale_price) / $this->price * 100);
        });
    }

    public function isOnSale(): bool
    {
        return $this->front_sale_price !== $this->price;
    }

    public function getOriginalPrice(): float
    {
        return $this->originalPrice;
    }

    public function setOriginalPrice(?float $price): static
    {
        $this->originalPrice = (float) $price;

        return $this;
    }

    public function getFinalPrice(): float
    {
        return $this->finalPrice;
    }

    public function setFinalPrice(?float $price): static
    {
        $this->finalPrice = (float) $price;

        return $this;
    }
}
