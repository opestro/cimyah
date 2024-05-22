<div class="bb-product-detail">
    <a href="{{ $product->url }}">
        <h3 class="tp-product-details-title">{{ $product->name }}</h3>
    </a>

    @include(EcommerceHelper::viewPath('includes.product-price'), [
        'priceWrapperClassName' => 'tp-product-details-price-wrapper mb-20',
        'priceClassName' => 'tp-product-details-price new-price',
        'priceOriginalWrapperClassName' => '',
        'priceOriginalClassName' => 'tp-product-details-price old-price',
    ])

    <x-core::form :url="route('public.cart.add-to-cart')" method="POST" class="product-form">
        <input type="hidden" name="id" value="{{ ($product->is_variation || !$product->defaultVariation->product_id) ? $product->id : $product->defaultVariation->product_id }}" />

        @if ($product->variations->isNotEmpty())
            {!! render_product_swatches($product, ['selected' => $selectedAttrs]) !!}

            @include(Theme::getThemeNamespace('views.ecommerce.includes.product-availability'))
        @endif

        {!! render_product_options($product) !!}

        <div class="tp-product-details-action-wrapper mt-3 mb-0">
            <h3 class="tp-product-details-action-title">{{ __('Quantity') }}</h3>
            <div class="tp-product-details-action-item-wrapper d-flex align-items-center gap-3">
                <div class="tp-product-details-quantity">
                    <div class="tp-product-quantity">
                    <span class="tp-cart-minus" data-bb-toggle="decrease-qty">
                        <svg width="11" height="2" viewBox="0 0 11 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 1H10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                        <input class="tp-cart-input" type="number" name="qty" min="1" value="1" max="{{ $product->with_storehouse_management ? $product->quantity : 1000 }}" />
                        <span class="tp-cart-plus" data-bb-toggle="increase-qty">
                        <svg width="11" height="12" viewBox="0 0 11 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 6H10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M5.5 10.5V1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    </div>
                </div>
                @if (EcommerceHelper::isCartEnabled())
                    <div class="tp-product-details-add-to-cart w-100">
                        <button
                            type="submit"
                            name="add-to-cart"
                            class="tp-product-details-add-to-cart-btn w-100"
                            @disabled($product->isOutOfStock())
                            {!! EcommerceHelper::jsAttributes('add-to-cart-in-form', $product) !!}
                        >
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
