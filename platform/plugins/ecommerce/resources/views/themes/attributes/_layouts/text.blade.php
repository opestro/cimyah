<div
    class="bb-product-attribute-swatch text-swatches-wrapper attribute-swatches-wrapper"
    data-type="text"
    data-slug="{{ $set->slug }}"
>
    <h4 class="bb-product-attribute-swatch-title">{{ $set->title }}:</h4>
    <ul class="bb-product-attribute-swatch-list text-swatch attribute-swatch">
        @foreach ($attributes->where('attribute_set_id', $set->id) as $attribute)
            <li
                data-slug="{{ $attribute->slug }}"
                data-id="{{ $attribute->id }}"
                @class([
                    'bb-product-attribute-swatch-item attribute-swatch-item',
                    'disabled' => ! $variationInfo->where('id', $attribute->id)->count(),
                ])
            >
                <label>
                    <input
                        name="attribute_{{ $set->slug }}_{{ $key }}"
                        data-slug="{{ $attribute->slug }}"
                        type="radio"
                        value="{{ $attribute->id }}"
                        @checked($selected->where('id', $attribute->id)->count())
                        class="product-filter-item"
                    >
                    <span>{{ $attribute->title }}</span>
                </label>
            </li>
        @endforeach
    </ul>
</div>
