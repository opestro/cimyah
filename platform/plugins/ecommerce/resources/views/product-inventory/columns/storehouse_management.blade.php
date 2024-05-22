@if ($product->variations_count > 0 && ! $product->is_variation)
    <span class="text-muted">&mdash;</span>
@else
    <x-core::form.select
        data-bb-toggle="product-bulk-change"
        data-url="{{ route('ecommerce.product-inventory.update', ['product' => $product->id]) }}"
        data-column="with_storehouse_management"
        data-id="storehouse-management-{{ $product->id }}"
        tabindex="-1"
    >
        <option value="1" @selected($product->with_storehouse_management)>{{ trans('core/base::base.yes') }}</option>
        <option value="0" @selected(! $product->with_storehouse_management)>{{ trans('core/base::base.no') }}</option>
    </x-core::form.select>
@endif
