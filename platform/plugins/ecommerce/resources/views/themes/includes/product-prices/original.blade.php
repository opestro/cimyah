@php
    $priceClassName ??= null;
    $priceWrapperClassName ??= null;
@endphp

<span class="{{ $priceWrapperClassName === null ? 'bb-product-price-text-old' : $priceWrapperClassName }}">
    <small>
        <del
            class="{{ $priceClassName === null ? 'text-muted' : $priceClassName }}"
            data-bb-value="product-original-price"
        >{{ $product->price()->displayPriceOriginalAsText() }}</del>
    </small>
</span>
