@php
    EcommerceHelper::registerThemeAssets();
    $version = get_cms_version();
    Theme::asset()->add('lightgallery-css', 'vendor/core/plugins/ecommerce/libraries/lightgallery/css/lightgallery.min.css', version: $version);
    Theme::asset()->add('slick-css', 'vendor/core/plugins/ecommerce/libraries/slick/slick.css', version: $version);
    Theme::asset()->container('footer')->add('lightgallery-js', 'vendor/core/plugins/ecommerce/libraries/lightgallery/js/lightgallery.min.js', ['jquery'], version: $version);
    Theme::asset()->container('footer')->add('slick-js', 'vendor/core/plugins/ecommerce/libraries/slick/slick.min.js', ['jquery'], version: $version);

    $galleryStyle = theme_option('ecommerce_product_gallery_image_style', 'vertical');
@endphp

<div @class(['bb-product-gallery', 'bb-product-gallery-' . $galleryStyle])>
    <div class="bb-product-gallery-images">
        @foreach ($productImages as $image)
            <a href="{{ RvMedia::getImageUrl($image) }}">
                {{ RvMedia::image($image, $product->name) }}
            </a>
        @endforeach
    </div>
    <div class="bb-product-gallery-thumbnails" data-vertical="{{ $galleryStyle === 'vertical' ? 1 : 0 }}">
        @foreach ($productImages as $image)
            <div>
                {{ RvMedia::image($image, $product->name, 'thumb') }}
            </div>
        @endforeach
    </div>
</div>
