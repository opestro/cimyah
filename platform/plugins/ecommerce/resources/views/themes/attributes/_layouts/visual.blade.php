<div
    class="bb-product-attribute-swatch visual-swatches-wrapper attribute-swatches-wrapper"
    data-type="visual"
    data-slug="{{ $set->slug }}"
>
    <h4 class="bb-product-attribute-swatch-title">{{ $set->title }}:</h4>
    <ul class="bb-product-attribute-swatch-list visual-swatch color-swatch attribute-swatch">
        @foreach ($attributes->where('attribute_set_id', $set->id) as $attribute)
            <li
                data-slug="{{ $attribute->slug }}"
                data-id="{{ $attribute->id }}"
                data-bs-toggle="tooltip" data-bs-title="Disabled tooltip"
                @class([
                    'bb-product-attribute-swatch-item attribute-swatch-item',
                    'disabled' => ! $variationInfo->where('id', $attribute->id)->count(),
                ])
            >
                <label>
                    <input
                        type="radio"
                        name="attribute_{{ $set->slug }}_{{ $key }}"
                        data-slug="{{ $attribute->slug }}"
                        value="{{ $attribute->id }}"
                        @checked($selected->where('id', $attribute->id)->count())
                        class="product-filter-item"
                    >
                    <span style="{{ $attribute->getAttributeStyle() }}"></span>
                    <div class="bb-product-attribute-swatch-item-tooltip">{{ $attribute->title }}</div>
                </label>
            </li>
        @endforeach
    </ul>
</div>
