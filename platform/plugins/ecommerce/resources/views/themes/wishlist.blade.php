<section class="wishlist-area pt-50 pb-50">
    <div class="container">
        @if ($products->total() && $products->loadMissing(['options', 'options.values']))
            <div class="cart-list mb-45 mw-100 overflow-x-auto">
                <table class="table">
                    <thead class="table-light">
                    <tr>
                        <th colspan="2" class="cart-header-product">{{ __('Product') }}</th>
                        <th class="cart-header-price">{{ __('Price') }}</th>
                        <th>{{ __('Action') }}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td class="cart-img align-middle">
                                <a href="{{ $product->original_product->url }}">
                                    {{ RvMedia::image($product->image, $product->name, 'thumb') }}
                                </a>
                            </td>
                            <td class="ps-3 align-middle">
                                <div class="cart-title">
                                    <a href="{{ $product->original_product->url }}" class="ms-0 fw-bold">
                                        {{ $product->name }}

                                        <span @class(['small', 'text-danger' => $product->isOutOfStock(), 'text-success' => ! $product->isOutOfStock()])>
                                            @if ($product->isOutOfStock())
                                                ({{ __('Out of stock') }})
                                            @else
                                                ({{ __('In stock') }})
                                            @endif
                                        </span>
                                    </a>
                                </div>

                                @if (is_plugin_active('marketplace') && $product->original_product->store->id)
                                    <div class="small">
                                        <span>{{ __('Vendor:') }}</span>
                                        <a href="{{ $product->original_product->store->url }}" class="fw-medium">{{ $product->original_product->store->name }}</a>
                                    </div>
                                @endif

                                @if ($product->sku)
                                    <div class="small">
                                        <span>{{ __('SKU:') }}</span>
                                        <span>{{ $product->sku }}</span>
                                    </div>
                                @endif
                            </td>
                            <td class="cart-price align-middle">
                                @include(EcommerceHelper::viewPath('includes.product-price'))
                            </td>

                            <td class="cart-add-to-cart align-middle">
                                <button
                                    title="{{ __('Add To Cart') }}"
                                    type="submit"
                                    class="btn btn-primary bb-btn-product-actions-icon"
                                    data-bb-toggle="add-to-cart"
                                    data-url="{{ route('public.cart.add-to-cart') }}"
                                    data-id="{{ $product->original_product->id }}"
                                    {!! EcommerceHelper::jsAttributes('add-to-cart', $product) !!}
                                >
                                    <x-core::icon name="ti ti-shopping-cart"/>
                                    <span>{{ __('Add To Cart') }}</span>
                                </button>
                            </td>

                            <td class="cart-action align-middle">
                                <button class="cart-action-btn btn btn-icon btn-danger bb-btn-product-actions-icon" data-bb-toggle="remove-from-wishlist" data-url="{{ route('public.wishlist.remove', $product) }}">
                                    <x-core::icon name="ti ti-x"/>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="cart-bottom">
                <div class="row align-items-end">
                    <div class="col-xl-6 col-md-4">
                        <div class="cart-update">
                            <a href="{{ route('public.cart') }}" class="btn btn-primary bb-btn-link-icon">
                                <x-core::icon name="ti ti-logout-2"/>
                                {{ __('Go To Cart') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            @include(EcommerceHelper::viewPath('includes.empty-state'), ['title' => __('Your wishlist list is empty')])
        @endif
    </div>
</section>
