@if(request()->ajax() && isset($products))
    @if($products->isNotEmpty())
        <section class="tp-cross-sale-product">
            <div class="container">
                <div class="tp-section-title-wrapper-6 text-center mb-40">
                    <h3 class="section-title tp-section-title-6">{{ __('Bought Together') }}</h3>
                </div>

                <div class="row">
                    <div class="tp-product-cross-sale-slider">
                        <div class="tp-product-cross-sale-slider-active swiper-container mb-10">
                            <div class="swiper-wrapper">
                                @foreach ($products as $product)
                                    <div class="swiper-slide">
                                        <div class="tp-product-item-3 mb-50">
                                            <div class="tp-product-thumb-3 mb-15 fix p-relative z-index-1">
                                                <a href="{{ $product->url }}">
                                                    {{ RvMedia::image($product->image, $product->name, 'medium', true) }}
                                                </a>

                                                @include(Theme::getThemeNamespace('views.ecommerce.includes.product.badges'))
                                            </div>
                                            <div class="tp-product-content-3">
                                                <h3 class="tp-product-title-3 text-truncate">
                                                    <a href="{{ $product->url }}" title="{{ $product->name }}">
                                                        {!! BaseHelper::clean($product->name) !!}
                                                    </a>
                                                </h3>

                                                @include(Theme::getThemeNamespace('views.ecommerce.includes.product.style-1.price'))

                                                @if(EcommerceHelper::isCartEnabled())
                                                    <button
                                                        type="button"
                                                        @if($hasVariations = $product->hasVariations)
                                                            data-bb-toggle="quick-shop"
                                                        data-url="{{ route('public.ajax.quick-shop', ['slug' => $product->slug, 'reference_product' => $parentProduct->slug]) }}"
                                                        @else
                                                            data-bb-toggle="add-to-cart"
                                                        data-url="{{ route('public.cart.add-to-cart') }}"
                                                        data-id="{{ $product->id }}"
                                                        {!! EcommerceHelper::jsAttributes('add-to-cart', $product) !!}
                                                        @endif
                                                        class="tp-product-cross-sale-btn"
                                                    >
                                                        @if ($hasVariations)
                                                            {{ __('Select Options') }}
                                                        @else
                                                            {{ __('Buy now at :price', ['price' => $product->price()->displayAsText()]) }}
                                                        @endif
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tp-cross-sale-swiper-scrollbar tp-swiper-scrollbar"></div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@else
    <div data-bb-toggle="block-lazy-loading" data-url="{{ route('public.ajax.cross-sale-products', $product) }}" class="position-relative" style="min-height: 14rem">
        <div class="loading-spinner"></div>
    </div>
@endif
