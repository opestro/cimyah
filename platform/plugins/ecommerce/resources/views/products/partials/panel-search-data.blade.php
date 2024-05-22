<x-core::card.body class="p-0">
    <div class="list-search-data">
        <div class="list-group list-group-flush overflow-auto" style="max-height: 25rem;">
            @if (!$availableProducts->isEmpty())
                @foreach ($availableProducts as $availableProduct)
                    <a
                        href="javascript:void(0);"
                        @class(['list-group-item list-group-item-action', 'selectable-item' => !$includeVariation])
                        @if (!$includeVariation)
                            data-name="{{ $availableProduct->name }}"
                            data-image="{{ RvMedia::getImageUrl($availableProduct->image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                            data-id="{{ $availableProduct->id }}"
                            data-url="{{ route('products.edit', $availableProduct->id) }}"
                            data-price="{{ $availableProduct->price }}"
                        @endif
                    >
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar" style="background-image: url('{{ RvMedia::getImageUrl($availableProduct->image, 'thumb', false, RvMedia::getDefaultImage()) }}')"></span>
                            </div>
                            <div class="col text-truncate">
                                <h4 class="text-body d-block mb-0">{{ $availableProduct->name }}</h4>
                            </div>
                            @if ($includeVariation)
                                <div class="col-auto">
                                    <ul>
                                        @foreach ($availableProduct->variations as $variation)
                                            <li
                                                class="product-variant selectable-item"
                                                data-name="{{ $availableProduct->name }}"
                                                data-image="{{ RvMedia::getImageUrl($variation->product->image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                                data-id="{{ $variation->product->id }}"
                                                data-url="{{ route('products.edit', $availableProduct->id) }}"
                                                data-price="{{ $availableProduct->price }}"
                                            >
                                                <a
                                                    class="color_green float-start"
                                                    href="#"
                                                >
                                                <span>
                                                    @foreach ($variation->variationItems as $variationItem)
                                                        {{ $variationItem->attribute->title }}
                                                        @if (!$loop->last)
                                                            /
                                                        @endif
                                                    @endforeach
                                                </span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            @else
                <div class="p-3">
                    <p class="text-muted my-0">{{ __('plugins/ecommerce::products.form.no_results') }}</p>
                </div>
            @endif
        </div>
    </div>
</x-core::card.body>

@if ($availableProducts->hasPages())
    <x-core::card.footer class="pb-0 d-flex justify-content-end">
        {{ $availableProducts->links() }}
    </x-core::card.footer>
@endif
