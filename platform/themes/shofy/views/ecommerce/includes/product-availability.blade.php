<div class="number-items-available">
    @if ($product->isOutOfStock())
        <span class="text-danger">{{ __('Out of stock') }}</span>
    @else
        @if (! $productVariation)
            <span class="text-danger">{{ __('Not available') }}
        @else
            @if ($productVariation->isOutOfStock())
                <span class="text-danger">{{ __('Out of stock') }}</span>
            @elseif (! $productVariation->with_storehouse_management || $productVariation->quantity < 1)
                <span class="text-success">{{ __('Available') }}</span>
            @elseif ($productVariation->quantity)
                <span class="text-success">
                    @if (EcommerceHelper::showNumberOfProductsInProductSingle())
                        @if ($productVariation->quantity !== 1)
                            {{ __(':number products available', ['number' => $productVariation->quantity]) }}
                        @else
                            {{ __(':number product available', ['number' => $productVariation->quantity]) }}
                        @endif
                    @else
                        {{ __('In stock') }}
                    @endif
                </span>
           @endif
       @endif
    @endif
</div>
