@php
    $key = mt_rand();
@endphp

<div
    class="product-attributes product-attribute-swatches"
    id="product-attributes-{{ $product->id }}"
    data-target="{{ route('public.web.get-variation-by-attributes', $product->getKey()) }}"
>
    @php
        $variationInfo = $productVariationsInfo;
        $variationNextIds = [];
    @endphp

    @foreach ($attributeSets as $set)
        @if (! $loop->first)
            @php
                $variationInfo = $productVariationsInfo->where('attribute_set_id', $set->id)->whereIn('variation_id', $variationNextIds);
            @endphp
        @endif

        @if (view()->exists($layout = "plugins/ecommerce::themes.attributes._layouts.$set->display_layout"))
            @include($layout)
        @else
            @include(EcommerceHelper::viewPath('attributes._layouts.dropdown'))
        @endif
        @php
            [$variationNextIds] = handle_next_attributes_in_product($attributes->where('attribute_set_id', $set->id), $productVariationsInfo, $set->id, $selected->pluck('id')->toArray(), $loop->index, $variationNextIds);
        @endphp
    @endforeach
</div>
