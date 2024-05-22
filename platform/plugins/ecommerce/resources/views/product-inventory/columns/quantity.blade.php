@if ($product->variations_count > 0 && ! $product->is_variation)
    <span class="text-muted">&mdash;</span>
@else
    <input
        type="number"
        value="{{ $product->quantity ?: 0 }}"
        class="form-control"
        data-bb-toggle="product-bulk-change"
        data-url="{{ route('ecommerce.product-inventory.update', ['product' => $product->id]) }}"
        data-column="quantity"
        data-target-id="storehouse-management-{{ $product->id }}"
        data-target-value="1"
        @style(['display: none;' => ! $product->with_storehouse_management])
    />

    <x-core::form.select
        data-bb-toggle="product-bulk-change"
        data-url="{{ route('ecommerce.product-inventory.update', ['product' => $product->id]) }}"
        data-column="stock_status"
        data-target-id="storehouse-management-{{ $product->id }}"
        data-target-value="0"
        @style(['display: none;' => $product->with_storehouse_management])
    >
        @foreach (Botble\Ecommerce\Enums\StockStatusEnum::labels() as $status => $label)
            <option value="{{ $status }}" @selected(($product->stock_status ?: 'in_stock') == $status)>{{ $label }}</option>
        @endforeach
    </x-core::form.select>

@endif
