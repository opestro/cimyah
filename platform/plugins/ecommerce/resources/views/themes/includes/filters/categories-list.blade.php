@php
    $categoriesRequest ??= [];
    $categoryId ??= 0;
    $urlCurrent ??= url()->current();

    if (!isset($groupedCategories)) {
        $groupedCategories = $categories->groupBy('parent_id');
    }

    $currentCategories = $groupedCategories->get($parentId ?? 0);
@endphp

@if($currentCategories)
    <ul
        class="bb-product-filter-items"
        @if(
            in_array($categoryId, $categoriesRequest)
            || isset($category) && $categoryId == $category->id
        )
            style="display: block !important;"
        @endif
    >
        @foreach ($currentCategories as $category)
            @if (! empty($categoriesRequest) && $loop->first && ! $category->parent_id)
                <li class="bb-product-filter-item">
                    <a href="{{ route('public.products') }}" @class(['bb-product-filter-link', 'active' => empty($categoriesRequest)])>
                        <x-core::icon name="ti ti-chevron-left" />

                        {{ __('All categories') }}
                    </a>
                </li>
            @endif

            <li class="bb-product-filter-item">
                <a
                    href="{{ route('public.single', $category->url) }}"
                    @class(['bb-product-filter-link', 'active' => $categoryId == $category->id || $urlCurrent == route('public.single', $category->url)])
                    data-id="{{ $category->id }}"
                >
                    @if (!$category->parent_id)
                        @if ($category->icon_image)
                            {{ RvMedia::image($category->icon_image, $category->name) }}
                        @elseif ($category->icon)
                            {!! BaseHelper::renderIcon($category->icon) !!}
                        @endif
                        {{ $category->name }}
                    @else
                        {{ $category->name }}
                    @endif
                </a>

                @php
                    $hasChildren = $groupedCategories->has($category->id);
                @endphp

                @if ($hasChildren)
                    <button data-bb-toggle="toggle-product-categories-tree">
                        <x-core::icon name="ti ti-chevron-down" />
                    </button>

                    @include(EcommerceHelper::viewPath('includes.filters.categories-list'), [
                        'groupedCategories' => $groupedCategories,
                        'parentId' => $category->id,
                    ])
                @endif
            </li>
        @endforeach
    </ul>
@endif
