<div class="bb-product-detail">
    <a href="{{ $product->url }}">
        <h3 class="bb-product-details-title">{{ $product->name }}</h3>
    </a>

    @include(EcommerceHelper::viewPath('includes.product-price'))

    <x-core::form :url="route('public.cart.add-to-cart')" method="POST" data-bb-toggle="product-form" class="product-form">
        <input type="hidden" name="id" value="{{ ($product->is_variation || !$product->defaultVariation->product_id) ? $product->id : $product->defaultVariation->product_id }}" />

        @if ($product->variations->isNotEmpty())
            {!! render_product_swatches($product, ['selected' => $selectedAttrs]) !!}

            @include(EcommerceHelper::viewPath('includes.product-availability'))
        @endif

        {!! render_product_options($product) !!}

        <div class="bb-product-details-action-wrapper mt-3 mb-0">
            <div class="bb-product-details-action-item-wrapper d-flex gap-3">
                @include(EcommerceHelper::viewPath('includes.product-quantity'))

                @if (EcommerceHelper::isCartEnabled())
                    <div class="bb-product-details-add-to-cart w-100">
                        <button
                            type="submit"
                            name="add-to-cart"
                            class="bb-product-details-add-to-cart-btn w-100 bb-btn-product-actions-icon btn btn-primary justify-content-center h-100"
                            @disabled($product->isOutOfStock())
                            {!! EcommerceHelper::jsAttributes('add-to-cart-in-form', $product) !!}
                        >
                            <x-core::icon name="ti ti-shopping-cart"/>
                            {{ __('Add To Cart') }}
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </x-core::form>

    <a href="{{ $product->url }}" class="btn-link mt-3 d-inline-flex align-items-center gap-1">
        {{ __('View full details') }}
        <x-core::icon name="ti ti-arrow-right" />
    </a>
</div>
