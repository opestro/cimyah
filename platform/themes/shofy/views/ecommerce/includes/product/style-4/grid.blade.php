<div @class(['tp-product-item-4 p-relative mb-40', $class ?? null])>
    <div class="tp-product-thumb-4 w-img fix">
        <a href="{{ $product->url }}">
            {{ RvMedia::image($product->image, $product->name, 'medium', true) }}
        </a>

        @include(Theme::getThemeNamespace('views.ecommerce.includes.product.badges'))

        @include(Theme::getThemeNamespace('views.ecommerce.includes.product.style-4.actions'))
    </div>
    <div class="tp-product-content-4">
        {!! apply_filters('ecommerce_before_product_item_content_renderer', null, $product) !!}

        @if (is_plugin_active('marketplace') && $product->store)
            <div class="tp-product-info-4">
                <a href="{{ $product->store->url }}">{{ $product->store->name }}</a>
            </div>
        @endif

        <h3 class="tp-product-title-4 text-truncate">
            <a href="{{ $product->url }}" title="{{ $product->name }}">
                {!! BaseHelper::clean($product->name) !!}
            </a>
        </h3>

        <div class="tp-product-price-inner-4">
            @include(Theme::getThemeNamespace('views.ecommerce.includes.product.style-1.rating'))

            @include(Theme::getThemeNamespace('views.ecommerce.includes.product.style-4.price'))

            @if (EcommerceHelper::isCartEnabled())
                <div class="tp-product-price-add-to-cart">
                    <button
                        type="button"
                        class="tp-product-add-to-cart-4"
                        @if($hasVariations = $product->variations->isNotEmpty())
                            data-bb-toggle="quick-shop"
                            data-url="{{ route('public.ajax.quick-shop', $product->slug) }}"
                        @else
                            data-bb-toggle="add-to-cart"
                            data-url="{{ route('public.cart.add-to-cart') }}"
                            data-id="{{ $product->original_product->id }}"
                            {!! EcommerceHelper::jsAttributes('add-to-cart', $product) !!}
                        @endif
                    >
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.76447 1L3.23047 3.541" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M10.2305 1L12.7645 3.541" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M1 5.09507C1 3.80007 1.693 3.69507 2.554 3.69507H13.446C14.307 3.69507 15 3.80007 15 5.09507C15 6.60007 14.307 6.49507 13.446 6.49507H2.554C1.693 6.49507 1 6.60007 1 5.09507Z"
                                stroke="currentColor"
                                stroke-width="1.5"
                            />
                            <path d="M6.42969 9.3999V11.8849" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M9.65234 9.3999V11.8849" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path
                                d="M2.05078 6.6001L3.03778 12.6481C3.26178 14.0061 3.80078 15.0001 5.80278 15.0001H10.0238C12.2008 15.0001 12.5228 14.0481 12.7748 12.7321L13.9508 6.6001"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                            />
                        </svg>
                        @if ($hasVariations)
                            {{ __('Select Options') }}
                        @else
                            {{ __('Add To Cart') }}
                        @endif
                    </button>
                </div>
            @endif
        </div>

        {!! apply_filters('ecommerce_after_product_item_content_renderer', null, $product) !!}
    </div>
</div>
