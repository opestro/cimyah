@if(EcommerceHelper::isReviewEnabled())
    <div class="tp-product-rating d-flex align-items-center">
        <div class="tp-product-rating-icon tp-product-rating-icon-2">
            @include(EcommerceHelper::viewPath('includes.rating-star'), ['avg' => $product->reviews_avg])
        </div>
        <div class="tp-product-rating-text">
            <a href="{{ $product->url }}#product-review" data-bb-toggle="scroll-to-review">
                <span class="d-none d-sm-block">{{ __('(:count reviews)', ['count' => number_format($product->reviews_count)]) }}</span>
                <span class="d-block d-sm-none">{{ __('(:count)', ['count' => number_format($product->reviews_count)]) }}</span>
            </a>
        </div>
    </div>
@endif
