<x-plugins-ecommerce::box-search-advanced
    type="product"
    :placeholder="trans('plugins/ecommerce::products.search_products')"
    :shown="$products->isNotEmpty()"
    template="selected_product_list_template"
>
    <input
        name="products"
        type="hidden"
        value="@if ($flashSale->id) {{ implode(',', array_filter($flashSale->products()->allRelatedIds()->toArray())) }} @endif"
    />

    <x-slot:items>
        @foreach ($products as $index => $product)
            <div class="list-group-item" data-product-id="{{ $product->id }}">
                <div class="row align-items-center mb-3">
                    <div class="col-auto">
                    <span
                        class="avatar"
                        style="background-image: url('{{ RvMedia::getImageUrl($product->image, 'thumb', false, RvMedia::getDefaultImage()) }}')"
                    ></span>
                    </div>
                    <div class="col text-truncate">
                        <a href="{{ route('products.edit', $product->id) }}" class="text-body d-block" target="_blank">
                            {{ $product->name }} ({{ format_price($product->sale_price ?: $product->price) }})
                        </a>
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
                <div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <x-core::form.text-input
                                    :label="trans('plugins/ecommerce::products.price')"
                                    class="input-mask-number"
                                    name="products_extra[{{ $index }}][price]"
                                    :data-thousands-separator="EcommerceHelper::getThousandSeparatorForInputMask()"
                                    :data-decimal-separator="EcommerceHelper::getDecimalSeparatorForInputMask()"
                                    :value="$product->pivot->price"
                                    :required="true"
                                />
                            </div>
                        </div>
                        <div class="col-6">
                            <x-core::form.text-input
                                :label="trans('plugins/ecommerce::products.quantity')"
                                class="input-mask-number"
                                name="products_extra[{{ $index }}][quantity]"
                                :data-thousands-separator="EcommerceHelper::getThousandSeparatorForInputMask()"
                                data-decimal-separator="EcommerceHelper::getDecimalSeparatorForInputMask()"
                                :value="$product->pivot->quantity"
                            />
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </x-slot:items>
</x-plugins-ecommerce::box-search-advanced>

@push('footer')
<x-core::custom-template id="selected_product_list_template">
    <div class="list-group-item" data-product-id="__id__">
        <div class="row align-items-center mb-3">
            <div class="col-auto">
            <span
                class="avatar"
                style="background-image: url('__image__')"
            ></span>
            </div>
            <div class="col text-truncate">
                <a href="__url__" class="text-body d-block" target="_blank">
                    __name__
                </a>
                <div class="text-secondary text-truncate">
                    __attributes__
                </div>
            </div>
            <div class="col-auto">
                <a
                    href="javascript:void(0)"
                    class="text-decoration-none list-group-item-actions"
                    data-bb-toggle="product-delete-item"
                    data-bb-target="__id__"
                    title="{{ trans('plugins/ecommerce::products.delete') }}"
                >
                    <x-core::icon name="ti ti-x" class="text-secondary" />
                </a>
            </div>
        </div>
        <div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group mb-3">
                        <x-core::form.text-input
                            :label="trans('plugins/ecommerce::products.price')"
                            class="input-mask-number"
                            name="products_extra[__index__][price]"
                            :data-thousands-separator="EcommerceHelper::getThousandSeparatorForInputMask()"
                            :data-decimal-separator="EcommerceHelper::getDecimalSeparatorForInputMask()"
                            value="__price__"
                            :required="true"
                        />
                    </div>
                </div>
                <div class="col-6">
                    <x-core::form.text-input
                        :label="trans('plugins/ecommerce::products.quantity')"
                        class="input-mask-number"
                        name="products_extra[__index__][quantity]"
                        :data-thousands-separator="EcommerceHelper::getThousandSeparatorForInputMask()"
                        data-decimal-separator="EcommerceHelper::getDecimalSeparatorForInputMask()"
                        :value="1"
                    />
                </div>
            </div>
        </div>
    </div>
</x-core::custom-template>
@endpush
