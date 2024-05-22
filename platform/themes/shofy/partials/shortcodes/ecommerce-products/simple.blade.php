<section class="tp-featured-product-area pt-70 pb-70">
    <div class="container">
        @if($shortcode->with_sidebar)
            <div class="row">
                <div class="col-xl-4 col-lg-5">
                    @include(Theme::getThemeNamespace('partials.shortcodes.ecommerce-products.partials.sidebar'))
                </div>
                <div class="col-xl-8 col-lg-7">
        @endif
            <div class="row gx-0">
                @foreach($products as $product)
                    <div class="col-lg-4 col-md-6">
                        <div class="tp-featured-item-3 text-center">
                            <div class="tp-featured-thumb-3 d-flex align-items-end justify-content-center">
                                {{ RvMedia::image($product->image, $product->name) }}
                            </div>
                            <div class="tp-featured-content-3">
                                <h3 class="tp-featured-title-3 text-truncate">
                                    <a href="{{ $product->url }}">
                                        {!! BaseHelper::clean($product->name) !!}
                                    </a>
                                </h3>
                                <p class="line-clamp-2">{!! BaseHelper::clean($product->description) !!}</p>
                                @include(EcommerceHelper::viewPath('includes.product-price'), [
                                    'priceWrapperClassName' => 'tp-featured-price-3',
                                    'priceClassName' => '',
                                    'priceOriginalWrapperClassName' => '',
                                    'priceOriginalClassName' => 'old-price',
                                ])
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @if($shortcode->with_sidebar)
                </div>
            </div>
        @endif
    </div>
</section>
