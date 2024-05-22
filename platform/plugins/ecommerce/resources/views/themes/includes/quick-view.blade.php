<div class="bb-quick-view-content">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 mb-30 image">
            <div class="wrapper">
                {{ RvMedia::image($product->image, $product->name) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 mb-30">
            <div class="bb-product-page-content">
                <h2 class="product-title mb-2">{{ $product->name }}</h2>
                @if (EcommerceHelper::isReviewEnabled())
                    @include(EcommerceHelper::viewPath('includes.rating'))
                @endif

                @include(EcommerceHelper::viewPath('includes.product-price'))

                {!! apply_filters('ecommerce_before_product_description', null, $product) !!}
                <p
                    class="product-description"
                    id="detail-description"
                >
                    {!! $product->description !!}
                </p>
                {!! apply_filters('ecommerce_after_product_description', null, $product) !!}

                <div class="text-warning"></div>
                <div class="row product-filters">
                    @if ($product->variations()->count() > 0)
                        {!! render_product_swatches($product, [
                            'selected' => $selectedAttrs,
                        ]) !!}
                    @endif
                </div>
                <form class="single-variation-wrap" data-bb-toggle="product-form" action="{{ route('public.cart.add-to-cart') }}" method="post">
                    @csrf
                    {!! apply_filters(ECOMMERCE_PRODUCT_DETAIL_EXTRA_HTML, null) !!}
                    <input
                        id="hidden-product-is_out_of_stock"
                        name="product_is_out_of_stock"
                        type="hidden"
                        value="{{ $product->isOutOfStock() }}"
                    />
                    <input
                        id="hidden-product-id"
                        name="id"
                        type="hidden"
                        value="{{ $product->id }}"
                    />

                    <div class="d-flex gap-4 mb-3">
                        @include(EcommerceHelper::viewPath('includes.product-quantity'))
                        <button
                            type="submit"
                            name="add-to-cart"
                            class="bb-product-details-add-to-cart-btn btn btn-primary bb-btn-product-actions-icon"
                            @disabled($product->isOutOfStock())
                            data-bb-toggle="add-to-cart-in-form"
                            {!! EcommerceHelper::jsAttributes('add-to-cart-in-form', $product) !!}
                        >
                            <x-core::icon name="ti ti-shopping-cart"/>
                            {{ __('Add To Cart') }}
                        </button>
                    </div>

                    @if(EcommerceHelper::isWishlistEnabled() || EcommerceHelper::isCompareEnabled())
                        <div class="d-flex gap-4 mb-3">
                            @if (EcommerceHelper::isCompareEnabled())
                                <button
                                    @class(['btn bb-btn-compare bb-btn-product-actions-icon', 'active' => EcommerceHelper::isProductInCompare($product->original_product->id)])
                                    style="border: 0 !important;"
                                    data-bb-toggle="add-to-compare" title="Add to compare"
                                    data-url="{{ route('public.compare.add', $product) }}"
                                    data-remove-url="{{ route('public.compare.remove', $product) }}"
                                >
                                    <x-core::icon name="ti ti-refresh"/>
                                    {{ __('Compare') }}
                                </button>
                            @endif
                            @if (EcommerceHelper::isWishlistEnabled())
                                <button
                                    class="btn bb-btn-wishlist bb-btn-product-actions-icon"
                                    data-bb-toggle="add-to-wishlist" title="Add to wishlist"
                                    data-url="{{ route('public.wishlist.add', $product) }}"
                                >
                                    <x-core::icon name="ti ti-heart"/>
                                    {{ __('Add Wishlist') }}
                                </button>
                            @endif
                        </div>
                    @endif
                </form>
                <div class="bb-product-meta">
                    @if ($product->sku)
                        <span>{{ __('SKU') }} : <span
                                class="sku"
                                id="product-sku"
                                itemprop="sku"
                            >{{ $product->sku }}</span></span>
                    @endif
                    <span>
                    <span
                        id="is-out-of-stock">{{ !$product->isOutOfStock() ? __('In stock') : __('Out of stock') }}</span>
                </span>

                    @if (!$product->categories->isEmpty())
                        <span>{{ __('Categories') }} :
                        @foreach ($product->categories as $category)
                                <a href="{{ $category->url }}"> {{ $category->name }}
                                    @if (!$loop->last), @endif
                            </a>
                            @endforeach
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
