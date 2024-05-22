<div class="product-rating d-flex align-items-center mb-3">
    <div class="product-rating-icon">
        @include(EcommerceHelper::viewPath('includes.rating-star'), ['avg' => $product->reviews_avg])
    </div>
    <div class="product-rating-text ms-2 fs-6">
        <a href="{{ $product->url }}#product-review" data-bb-toggle="scroll-to-review">
            <span class="d-none d-sm-block">{{ __('(:count reviews)', ['count' => number_format($product->reviews_count)]) }}</span>
            <span class="d-block d-sm-none">{{ __('(:count)', ['count' => number_format($product->reviews_count)]) }}</span>
        </a>
    </div>
</div>
