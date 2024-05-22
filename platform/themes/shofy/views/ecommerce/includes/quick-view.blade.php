<div class="bb-product-detail tp-product-modal-content d-lg-flex align-items-start">
    <button type="button" class="tp-product-modal-close-btn" data-bs-toggle="modal" data-bs-target="#product-quick-view-modal">
        <x-core::icon name="ti ti-x" />
    </button>

    <div class="tp-product-details-thumb-wrapper tp-tab">
        <div class="bb-quick-view-gallery-images">
            @foreach ($productImages as $image)
                <a href="{{ RvMedia::getImageUrl($image) }}">
                    {{ RvMedia::image($image, $product->name, 'medium') }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="tp-product-details-wrapper">
        @include(Theme::getThemeNamespace('views.ecommerce.includes.product-detail'))
    </div>
</div>
