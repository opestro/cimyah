<div
    class="bb-product-attribute-swatch dropdown-swatches-wrapper attribute-swatches-wrapper"
    data-type="dropdown"
    data-slug="{{ $set->slug }}"
>
    <h4 class="bb-product-attribute-swatch-title">{{ $set->title }}:</h4>
    <div class="bb-product-attribute-swatch-list attribute-swatch">
        <select class="form-select product-filter-item">
            <option value="">{{ __('Select :name', ['name' => strtolower($set->title)]) }}</option>
            @foreach ($attributes->where('attribute_set_id', $set->id) as $attribute)
                <option
                    data-id="{{ $attribute->id }}"
                    data-slug="{{ $attribute->slug }}"
                    value="{{ $attribute->id }}"
                    @selected($selected->where('id', $attribute->id)->count())
                    @disabled(! $variationInfo->where('id', $attribute->id)->count())
                >
                    {{ $attribute->title }}
                </option>
            @endforeach
        </select>
    </div>
</div>
