@if ($product->variations_count > 0 && ! $product->is_variation)
    <span class="text-muted">&mdash;</span>
@else
    <input
        type="number"
        value="{{ $product->{$type} ?: 0 }}"
        class="form-control"
        data-bb-toggle="product-bulk-change"
        data-url="{{ route('ecommerce.product-prices.update', ['product' => $product->id]) }}"
        data-column="{{ $type }}"
        data-id="product-price-{{ $type }}-{{ $product->id }}"
    />
@endif
