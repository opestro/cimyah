@php
    $groupedCategories = ProductCategoryHelper::getProductCategoriesWithUrl()->groupBy('parent_id');

    $currentCategories = $groupedCategories->get(0);
@endphp

@if($currentCategories)
    @switch($style ?? 1)
        @case(5)
            <ul @class(['tp-submenu' => $hasChildren])>
                @foreach ($currentCategories as $category)
                    @php
                        $hasChildren = $groupedCategories->has($category->id);
                    @endphp

                    <li @class(['has-dropdown' => $hasChildren])>
                        <a href="{{ $category->url }}">
                            {!! Theme::partial('header.categories-item', ['category' => $category]) !!}
                        </a>

                        @if($hasChildren && $currentCategories = $groupedCategories->get($category->id))
                            {!! Theme::partial('header.categories-dropdown', ['currentCategories' => $currentCategories, 'hasChildren' => $hasChildren, 'groupedCategories' => $groupedCategories]) !!}
                        @endif
                    </li>
                @endforeach
            </ul>

            @break
        @default
            <ul>
                @foreach ($currentCategories as $category)
                    @php
                        $hasChildren = $groupedCategories->has($category->id);
                        $hasMegaMenu = $hasChildren && $category->image;
                    @endphp

                    <li @class(['has-dropdown' => $hasChildren])>
                        <a href="{{ route('public.single', $category->url) }}" @class(['has-mega-menu' => $hasMegaMenu])>
                            {!! Theme::partial('header.categories-item', ['category' => $category]) !!}
                        </a>

                        @if($hasChildren && $currentCategories = $groupedCategories->get($category->id))
                            @php
                                $hasMegaMenu = $groupedCategories->has($currentCategories->first()->id) && $currentCategories->first()->image;
                            @endphp

                            <ul @class(['tp-submenu', 'mega-menu' => $hasMegaMenu])>
                                @foreach ($currentCategories as $childCategory)
                                    @php
                                        $hasChildren = $groupedCategories->has($childCategory->id);
                                        $hasMegaMenu = $hasChildren && $childCategory->image;
                                    @endphp

                                    <li @class(['has-dropdown' => $hasChildren && !$hasMegaMenu])>
                                        <a href="{{ route('public.single', $childCategory->url) }}" @class(['mega-menu-title' => $hasMegaMenu && $hasChildren])>
                                            {!! Theme::partial('header.categories-item', ['category' => $childCategory]) !!}
                                        </a>

                                        @if ($hasChildren)
                                            <ul @class(['tp-submenu' => ! $hasMegaMenu])>
                                                @foreach ($groupedCategories->get($childCategory->id) as $item)
                                                    @if($loop->first && $childCategory->image && $hasMegaMenu)
                                                        <li>
                                                            <a href="{{ route('public.single', $childCategory->url) }}">
                                                                <img src="{{ RvMedia::getImageUrl($childCategory->image) }}" alt="{{ $childCategory->name }}">
                                                            </a>
                                                        </li>
                                                    @endif
                                                    <li>
                                                        <a href="{{ route('public.single', $item->url) }}">
                                                            {!! Theme::partial('header.categories-item', ['category' => $item]) !!}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>

            @break
    @endswitch
@endif
