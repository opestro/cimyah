<div class="tp-product-badge">
    @if ($product->isOutOfStock())
        <span class="product-out-stock">{{ __('Out Of Stock') }}</span>
    @else
        @if ($product->productLabels->isNotEmpty())
            @foreach ($product->productLabels as $label)
                <span @style(["background-color: $label->color !important" => $label->color])>{{ $label->name }}</span>
            @endforeach
        @else
            @if ($product->front_sale_price !== $product->price)
                <span class="product-sale">{{ get_sale_percentage($product->price, $product->front_sale_price) }}</span>
            @endif
        @endif
    @endif
</div>
