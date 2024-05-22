<div @class(['tp-product-item-3 mb-50', $class ?? null])>
    <div class="tp-product-thumb-3 mb-15 fix p-relative z-index-1">
        <a href="{{ $product->url }}">
            {{ RvMedia::image($product->image, $product->name, 'medium', true) }}
        </a>

        @include(Theme::getThemeNamespace('views.ecommerce.includes.product.style-3.badges'))

        @include(Theme::getThemeNamespace('views.ecommerce.includes.product.style-3.actions'))
    </div>
    <div class="tp-product-content-3">
        {!! apply_filters('ecommerce_before_product_item_content_renderer', null, $product) !!}

        @if (is_plugin_active('marketplace') && $product->store)
            <div class="tp-product-tag-3">
                <a href="{{ $product->store->url }}">{{ $product->store->name }}</a>
            </div>
        @endif

        <h3 class="tp-product-title-3 text-truncate">
            <a href="{{ $product->url }}" title="{{ $product->name }}">
                {!! BaseHelper::clean($product->name) !!}
            </a>
        </h3>

        @include(Theme::getThemeNamespace('views.ecommerce.includes.product.style-1.rating'))

        @include(Theme::getThemeNamespace('views.ecommerce.includes.product.style-3.price'))

        {!! apply_filters('ecommerce_after_product_item_content_renderer', null, $product) !!}
    </div>
</div>
