<section
    class="content-page single-product-content pt-50 pb-50"
    id="product-detail-page"
>
    @include(EcommerceHelper::viewPath('includes.product-detail'))

    <div class="card product-detail-tabs mt-5">
        <ul class="nav nav-pills nav-fill bb-product-content-tabs p-4 pb-0">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" aria-current="page" href="#tab_description">{{ __('Description') }}</a>
            </li>

            @if (EcommerceHelper::isReviewEnabled())
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab_reviews">{{ __('Reviews :number', [
                        'number' => $product->reviews_count > 1 ? sprintf('(%s)', $product->reviews_count) : null
                    ]) }}
                    </a>
                </li>
            @endif
        </ul>

        <div class="bb-product-content-tabs-wrapper tab-content container p-4">
            <div
                class="tab-pane fade in active show"
                id="tab_description"
                role="tabpanel"
            >
                <div class="row">
                    <div class="col-md-12">
                        <div class="ck-content">
                            {!! BaseHelper::clean($product->content) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="tab-pane fade"
                id="tab_additional_information"
                role="tabpanel"
            >
                <h6
                    class="product-collapse-title"
                    data-bs-toggle="collapse"
                    data-bs-target="#tab_additional_information-coll"
                >{{ theme_option('product-bonus-title') }}</h6>
                <div
                    class="container product-collapse collapse"
                    id="tab_additional_information-coll"
                >

                    {!! theme_option('product-bonus') !!}

                </div>
            </div>

            @if (EcommerceHelper::isReviewEnabled())
                <div class="tab-pane fade" id="tab_reviews" role="tabpanel" aria-labelledby="nav-review-tab" tabindex="0">
                    <div class="tp-product-details-review-wrapper pt-60" id="product-reviews">
                        @include(EcommerceHelper::viewPath('includes.reviews'))
                    </div>
                </div>
            @endif
        </div>
    </div>

    @php
        $relatedProducts = get_related_products($product);
    @endphp

    @if ($relatedProducts->isNotEmpty())
        <div class="container mt-5">
            <h2>{{ __('Related products') }}</h2>

            <div class="row">
                @include(EcommerceHelper::viewPath('includes.product-items'), ['products' => $relatedProducts])
            </div>
        </div>
    @endif
</section>
