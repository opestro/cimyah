@php
    $isConfigurable = $product->variations()->count() > 0;
@endphp

<div class="card bb-product-item">
    <a title="{{ $product->name }}" href="{{ $product->url }}">
        <div class="wrapper">
            <div class="image">
                {!! RvMedia::image($product->image, $product->name, 'product-thumb', attributes: ['class' => 'card-img-top']) !!}
            </div>
        </div>
        <div class="card-body">
            <div class="card-title product-name fw-bold mb-3" >
                <a class="text-black" title="{{ $product->name }}" href="{{ $product->url }}">{{ $product->name }}</a>
            </div>

            @include(EcommerceHelper::viewPath('includes.product-price'))

            @if(EcommerceHelper::isReviewEnabled())
                @include(EcommerceHelper::viewPath('includes.rating'))
            @endif

            <div class="product-add-cart-btn-large-wrapper">
                @if (EcommerceHelper::isCartEnabled())
                    <button
                        type="button"
                        class="btn btn-primary bb-btn-product-actions-icon"
                        @if($isConfigurable)
                            data-bb-toggle="quick-shop"
                            data-url="{{ route('public.ajax.quick-shop', $product->slug) }}"
                        @else
                            data-bb-toggle="add-to-cart"
                            data-url="{{ route('public.cart.add-to-cart') }}"
                            data-id="{{ $product->original_product->id }}"
                            {!! EcommerceHelper::jsAttributes('add-to-cart', $product) !!}
                        @endif
                    >
                        <x-core::icon name="ti ti-shopping-cart"/>
                        <span class="tp-product-tooltip tp-product-tooltip-right">
                            @if ($isConfigurable)
                                {{ __('Select Options') }}
                            @else
                                {{ __('Add To Cart') }}
                            @endif
                </span>
                    </button>
                @endif
            </div>
        </div>
    </a>
</div>
