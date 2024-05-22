@php
    EcommerceHelper::registerThemeAssets();

    $product->loadMissing('variationAttributeSwatchesForProductList');

    $variationAttributeSwatchesForProductList = $product->variationAttributeSwatchesForProductList
        ->where('display_layout', 'visual')
        ->unique('attribute_id');
@endphp

@if ($variationAttributeSwatchesForProductList->isNotEmpty())
    <ul class="bb-product-attribute-swatch-list visual-swatch color-swatch attribute-swatch mt-3">
        @foreach($variationAttributeSwatchesForProductList as $attribute)
            @php
                $attribute->setRelation('product', $product);
            @endphp
            <li class="bb-product-attribute-swatch-item attribute-swatch-item">
                <label>
                    <span style="background-color: {{ $attribute->color ?: '#000' }} !important;"></span>
                    <div class="bb-product-attribute-swatch-item-tooltip">{{ $attribute->attribute_title }}</div>
                </label>
            </li>
        @endforeach
    </ul>
@endif
