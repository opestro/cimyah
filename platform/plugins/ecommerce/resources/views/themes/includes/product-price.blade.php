@php
    $isDisplayPriceOriginal ??= true;
    $priceWrapperClassName ??= null;
    $priceClassName ??= null;
    $priceOriginalClassName ??= null;
    $priceOriginalWrapperClassName ??= null;
@endphp

<div class="{{ $priceWrapperClassName === null ? 'bb-product-price mb-3' : $priceWrapperClassName }}">
    <span
        class="{{ $priceClassName === null ? 'bb-product-price-text fw-bold' : $priceClassName }}"
        data-bb-value="product-price"
    >{{ $product->price()->displayAsText() }}</span>

    @if($isDisplayPriceOriginal && $product->isOnSale())
        @include(EcommerceHelper::viewPath('includes.product-prices.original'), [
            'priceWrapperClassName' => $priceOriginalWrapperClassName,
            'priceClassName' => $priceOriginalClassName,
        ])
    @endif
</div>
