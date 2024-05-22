@php
    $dataForFilter = EcommerceHelper::dataForFilter($category ?? null);
    [$categories, $brands, $tags, $rand, $categoriesRequest, $urlCurrent, $categoryId, $maxFilterPrice] = $dataForFilter;

    $brands = $brands->whereIn('id', request()->input('brands', []));
    $tags = $tags->whereIn('id', request()->input('tags', []));
    $categories = $categories->whereIn('id', request()->input('categories', []));

    $attributeSets = app(\Botble\Ecommerce\Supports\RenderProductAttributeSetsOnSearchPageSupport::class)->getAttributeSets();
@endphp

@if($brands->isNotEmpty() || $tags->isNotEmpty() || $categories->isNotEmpty() || request()->input('attributes', []))
    <div class="bb-product-filter-result">
        @foreach($brands as $brand)
            <a href="{{ request()->fullUrlWithQuery([...request()->except('brands'), 'brands' => array_diff(request()->input('brands', []), [$brand->id])]) }}" class="bb-product-filter-clear">
                <x-core::icon name="ti ti-x" />
                {{ $brand->name }}
            </a>
        @endforeach

        @foreach($tags as $tag)
            <a href="{{ request()->fullUrlWithQuery([...request()->except('tags'), 'tags' => array_diff(request()->input('tags', []), [$tag->id])]) }}" class="bb-product-filter-clear">
                <x-core::icon name="ti ti-x" />
                {{ $tag->name }}
            </a>
        @endforeach

        @foreach($categories as $category)
            <a href="{{ request()->fullUrlWithQuery([...request()->except('categories'), 'categories' => array_diff(request()->input('categories', []), [$category->id])]) }}" class="bb-product-filter-clear">
                <x-core::icon name="ti ti-x" />
                {{ $category->name }}
            </a>
        @endforeach

        @foreach($attributeSets as $attributeSet)
            @foreach(request()->input('attributes', []) as $slug => $values)
                @continue($slug !== $attributeSet->slug)
                @foreach($values as $value)
                    @php
                        $attribute = $attributeSet->attributes->where('id', $value)->first();
                    @endphp

                    @if($attribute)
                        <a href="{{ request()->fullUrlWithQuery([...request()->except('attributes'), "attributes[{$slug}]" => array_diff(request()->input("attributes.{$slug}", []), [$value])]) }}" class="bb-product-filter-clear">
                            <x-core::icon name="ti ti-x" />
                            <span>{{ $attributeSet->title }}:</span> {{ $attribute->title }}
                        </a>
                    @endif
                @endforeach
            @endforeach
        @endforeach

        <a href="{{ request()->url() }}" class="bb-product-filter-clear-all">
            <x-core::icon name="ti ti-x" />
            {{ __('Clear all') }}
        </a>
    </div>
@endif
