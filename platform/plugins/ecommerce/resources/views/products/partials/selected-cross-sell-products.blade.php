@foreach ($products as $product)
    <div class="list-group-item">
        <input
            type="hidden"
            name="cross_sale_products[{{ $product->id }}][id]"
            value="{{ $product->id }}"
        />
        <input
            type="hidden"
            name="cross_sale_products[{{ $product->id }}][is_variant]"
            value="0"
        />
        <div class="row align-items-center mb-3">
            <div class="col-auto">
                <span
                    class="avatar"
                    style="background-image: url('{{ RvMedia::getImageUrl($product->image, 'thumb', false, RvMedia::getDefaultImage()) }}')"
                ></span>
            </div>
            <div class="col text-truncate">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('products.edit', $product->id) }}" class="text-body" target="_blank">{{ $product->name }}</a>
                    @if ($includeVariation && $product->variationInfo->id)
                        - <div class="text-secondary text-truncate">
                            @foreach ($product->variationInfo->variationItems as $variationItem)
                                <span>
                                {{ $variationItem->attribute->title }}
                                    @if (!$loop->last)
                                        <span> / </span>
                                    @endif
                            </span>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div>
                    <span class="fw-semibold">{{ format_price($product->front_sale_price) }}</span>
                    @if($product->isOnSale())
                        /
                        <span class="text-danger text-decoration-line-through">{{ format_price($product->price) }}</span>
                    @endif
                </div>
            </div>
            <div class="col-auto">
                <a
                    href="javascript:void(0)"
                    class="text-decoration-none list-group-item-actions"
                    data-bb-toggle="product-delete-item"
                    data-bb-target="{{ $product->id }}"
                    title="{{ trans('plugins/ecommerce::products.delete') }}"
                >
                    <x-core::icon name="ti ti-x" class="text-secondary" />
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <x-core::form.text-input
                    :label="trans('plugins/ecommerce::products.price')"
                    :value="$product->pivot->price"
                    name="cross_sale_products[{{ $product->id }}][price]"
                />
            </div>
            <div class="col">
                <x-core::form.select
                    :label="trans('plugins/ecommerce::products.cross_sell_price_type.title')"
                    :options="\Botble\Ecommerce\Enums\CrossSellPriceType::labels()"
                    :value="$product->pivot->price_type"
                    name="cross_sale_products[{{ $product->id }}][price_type]"
                />
            </div>
        </div>

        @if($product->variations->isNotEmpty())
            <x-core::form.on-off.checkbox
                label="Apply for all variations"
                name="cross_sale_products[{{ $product->id }}][apply_to_all_variations]"
                :checked="$product->pivot->apply_to_all_variations"
                data-bb-toggle="collapse"
                data-bb-target="#product-variations-{{ $product->id }}"
                data-bb-reverse
            />

            <div class="list-group" id="product-variations-{{ $product->id }}" @style(['display: none' => $product->pivot->apply_to_all_variations])>
                @foreach($product->variations as $variationProduct)
                    @php($variationProduct->product->pivot = $crossSaleProducts->find($variationProduct->product->id)?->pivot)

                    <input
                        type="hidden"
                        name="cross_sale_products[{{ $variationProduct->product->id }}][id]"
                        value="1"
                    />
                    <input
                        type="hidden"
                        name="cross_sale_products[{{ $variationProduct->product->id }}][is_variant]"
                        value="1"
                    />
                    <div class="list-group-item">
                        <div class="row align-items-center mb-3">
                            <div class="col-auto">
                                <span
                                    class="avatar"
                                    style="background-image: url('{{ RvMedia::getImageUrl($variationProduct->image, 'thumb', false, RvMedia::getDefaultImage()) }}')"
                                ></span>
                            </div>

                            <div class="col text-truncate">
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('products.edit', $product->id) }}" class="text-body" target="_blank">{{ $variationProduct->product->name }}</a>

                                    @if ($variationProduct->product->variationInfo->id)
                                        - <div class="text-secondary text-truncate">
                                            @foreach ($variationProduct->product->variationInfo->variationItems as $variationItem)
                                                <span>
                                                    {{ $variationItem->attribute->title }}
                                                    @if (!$loop->last)
                                                        <span> / </span>
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <span class="fw-semibold">{{ format_price($variationProduct->product->front_sale_price) }}</span>
                                    @if($variationProduct->product->isOnSale())
                                        /
                                        <span class="text-danger text-decoration-line-through">{{ format_price($variationProduct->product->price) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <x-core::form.text-input
                                    :label="trans('plugins/ecommerce::products.price')"
                                    :value="$variationProduct->product?->pivot?->price"
                                    name="cross_sale_products[{{ $variationProduct->product->id }}][price]"
                                />
                            </div>

                            <div class="col">
                                <x-core::form.select
                                    :label="trans('plugins/ecommerce::products.cross_sell_price_type.title')"
                                    :options="\Botble\Ecommerce\Enums\CrossSellPriceType::labels()"
                                    :value="$variationProduct->product?->pivot?->price_type"
                                    name="cross_sale_products[{{ $variationProduct->product->id }}][price_type]"
                                />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endforeach
