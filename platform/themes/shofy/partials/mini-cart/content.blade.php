@if (Cart::instance('cart')->isNotEmpty() && ($products = Cart::instance('cart')->products()) && $products->isNotEmpty())
    <x-core::form method="post" :url="route('public.cart.update')" class="cart-form">
        <div class="cartmini__widget">
            @foreach (Cart::instance('cart')->content() as $key => $cartItem)
                @if ($product = $products->find($cartItem->id))
                    <input type="hidden" name="items[{{ $key }}][rowId]" value="{{ $cartItem->rowId }}">

                    <div class="cartmini__widget-item">
                        <div class="cartmini__thumb">
                            <a href="{{ $product->original_product->url }}">
                                {{ RvMedia::image(Arr::get($cartItem->options, 'image', $product->original_product->image), $product->original_product->name, 'thumb') }}
                            </a>
                        </div>
                        <div class="cartmini__content">
                            <h5 class="cartmini__title">
                                <a href="{{ $product->original_product->url }}">{{ $product->original_product->name }}</a>
                            </h5>
                            @include(Theme::getThemeNamespace('views.ecommerce.includes.cart-quantity'))
                            <div class="cartmini__price-wrapper">
                            @include(EcommerceHelper::viewPath('includes.product-price'), [
                                'priceWrapperClassName' => 'cartmini__price',
                                'priceClassName' => '',
                                'priceOriginalWrapperClassName' => '',
                                'priceOriginalClassName' => 'small text-secondary text-decoration-line-through',
                            ])

                            </div>
                            <div class="small">{{ Arr::get($cartItem->options, 'attributes', '') }}</div>

                            @if (EcommerceHelper::isEnabledProductOptions() && !empty($cartItem->options['options']))
                                {!! render_product_options_html($cartItem->options['options'], $product->front_sale_price_with_taxes) !!}
                            @endif

                            @include(
                                EcommerceHelper::viewPath('includes.cart-item-options-extras'),
                                ['options' => $cartItem->options]
                            )
                        </div>
                        <a
                            href="{{ route('public.cart.remove', $cartItem->rowId) }}"
                            class="cartmini__del"
                            title="{{ __('Remove this item') }}"
                            data-bb-toggle="remove-from-cart"
                            {!! EcommerceHelper::jsAttributes('remove-from-cart', $product, ['data-product-quantity' => $cartItem->qty]) !!}
                        >
                            <x-core::icon name="ti ti-x" />
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    </x-core::form>
@else
    <div class="cartmini__empty text-center">
        <img src="{{ Theme::asset()->url('images/empty-cart.png') }}" alt="{{ __('Empty Cart') }}">
        <p>{{ __('Your Cart is empty') }}</p>
        <a href="{{ route('public.products') }}" class="tp-btn">{{ __('Go to Shop') }}</a>
    </div>
@endif
