<div class="tp-product-sm-item-5 d-flex align-items-center">
    <div class="tp-product-sm-thumb-5 fix">
        <a href="{{ $product->url }}">
            {{ RvMedia::image($product->image, $product->name, 'thumb') }}
        </a>
    </div>
    <div class="tp-product-sm-content-5">
        @if (is_plugin_active('marketplace') && $product->store->getKey())
            <div class="tp-product-sm-tag-5">
                <a href="{{ $product->store->url }}">{{ $product->store->name }}</a>
            </div>
        @endif

        <h4 class="tp-product-sm-title-5">
            <a href="{{ $product->url }}">{{ $product->name }}</a>
        </h4>

        <div class="tp-product-sm-rating-5">
            @include(EcommerceHelper::viewPath('includes.rating-star'), ['avg' => $product->reviews_avg])
        </div>

        @include(EcommerceHelper::viewPath('includes.product-price'), [
            'priceWrapperClassName' => 'tp-product-sm-price-wrapper-5',
            'priceClassName' => 'tp-product-sm-price-5',
            'priceOriginalWrapperClassName' => '',
            'priceOriginalClassName' => 'tp-product-sm-price-5 old-price',
        ])
    </div>
</div>
