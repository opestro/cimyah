@if (! $product->is_variation)
    <div class="d-block">
        <div class="mb-1">
            {{ Html::link(route('products.edit', $product->id), $product->name, attributes: ['target' => '_blank', 'tabindex' => '-1']) }}
        </div>

        @if ($product->sku)
            <div class="d-block text-muted">{{ trans('plugins/ecommerce::products.sku_line', ['sku' => $product->sku]) }}</div>
        @endif
    </div>
@else
    <div class="d-flex align-items-start justify-content-start">
        <div class="me-1 text-muted">↳</div>
        <div>
            @if($product->variation_attributes)
                <div class="d-block text-success mb-1">{{ $product->variation_attributes }}</div>
            @endif

            @if ($product->sku)
                <div class="d-block text-muted">{{ trans('plugins/ecommerce::products.sku_line', ['sku' => $product->sku]) }}</div>
            @endif
        </div>
    </div>
@endif
