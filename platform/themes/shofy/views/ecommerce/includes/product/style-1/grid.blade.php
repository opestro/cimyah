<div @class(['tp-product-item transition-3 mb-25', 'tp-product-item-3 tp-product-style-primary mb-50' => $style === 3, $class ?? null])>
    <div class="tp-product-thumb p-relative fix m-img">
        <a href="{{ $product->url }}">
            {{ RvMedia::image($product->image, $product->name, 'medium', true) }}
        </a>

        @include(Theme::getThemeNamespace('views.ecommerce.includes.product.badges'))

        @include(Theme::getThemeNamespace('views.ecommerce.includes.product.style-1.actions'))
    </div>

    <div class="tp-product-content">
        {!! apply_filters('ecommerce_before_product_item_content_renderer', null, $product) !!}

        @if (is_plugin_active('marketplace') && $product->store)
            <div class="tp-product-category">
                <a href="{{ $product->store->url }}">{{ $product->store->name }}</a>
            </div>
        @endif
        <h3 class="text-truncate tp-product-title">
            <a href="{{ $product->url }}" title="{{ $product->name }}">
                {!! BaseHelper::clean($product->name) !!}
            </a>
        </h3>

        @include(Theme::getThemeNamespace('views.ecommerce.includes.product.style-1.rating'))

        @include(Theme::getThemeNamespace('views.ecommerce.includes.product.style-1.price'))

        @if ($withCountdown ?? false)
            @include(Theme::getThemeNamespace('views.ecommerce.includes.product.countdown'), compact('endDate'))
        @endif

        {!! apply_filters('ecommerce_after_product_item_content_renderer', null, $product) !!}
    </div>
</div>
