<div @class(['tp-product-item-5 p-relative white-bg mb-40', $class ?? null])>
    <div class="tp-product-thumb-5 w-img fix mb-15">
        <a href="{{ $product->url }}">
            {{ RvMedia::image($product->image, $product->name, $style === 2 ? 'thumb' : 'medium', true) }}
        </a>

        @include(Theme::getThemeNamespace('views.ecommerce.includes.product.badges'))

        @include(Theme::getThemeNamespace('views.ecommerce.includes.product.style-5.actions'))
    </div>
    <div class="tp-product-content-5">
        {!! apply_filters('ecommerce_before_product_item_content_renderer', null, $product) !!}

        @if (is_plugin_active('marketplace') && $product->store->getKey())
            <div class="tp-product-tag-5">
                <span><a href="{{ $product->store->url }}">{{ $product->store->name }}</a></span>
            </div>
        @endif

        <h3 class="tp-product-title-2 text-truncate">
            <a href="{{ $product->url }}" title="{!! $name = BaseHelper::clean($product->name) !!}">{!! $name !!}</a>
        </h3>

        @include(Theme::getThemeNamespace('views.ecommerce.includes.product.style-5.rating'))

        @include(Theme::getThemeNamespace('views.ecommerce.includes.product.style-5.price'))

        {!! apply_filters('ecommerce_after_product_item_content_renderer', null, $product) !!}
    </div>
</div>
