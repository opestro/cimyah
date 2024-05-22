@if ($products->isNotEmpty())
    <div class="bb-quick-search-content">
        <div class="bb-quick-search-list">
            @foreach ($products as $product)
                <a class="bb-quick-search-item" href="{{ $product->url }}">
                    <div class="bb-quick-search-item-image">
                        {{ RvMedia::image($product->image, $product->name, 'thumb', useDefaultImage: true, attributes: ['loading' => false]) }}
                    </div>
                    <div class="bb-quick-search-item-info">
                        <div class="bb-quick-search-item-name">
                            {{ $product->name }}
                        </div>

                        @if (EcommerceHelper::isReviewEnabled())
                            <div class="bb-quick-search-item-rating">
                                @include(EcommerceHelper::viewPath('includes.rating-star'), ['avg' => $product->reviews_avg])
                                <span>({{ $product->reviews_count }})</span>
                            </div>
                        @endif

                        @include(EcommerceHelper::viewPath('includes.product-price'), [
                            'priceWrapperClassName' => 'bb-quick-search-item-price',
                            'priceClassName' => 'new-price',
                            'priceOriginalWrapperClassName' => '',
                            'priceOriginalClassName' => 'old-price',
                        ])
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <div class="bb-quick-search-view-all">
        <a href="#" onclick="event.preventDefault(); document.getElementById('bb-form-quick-search').submit();">{{ __('View all results') }}</a>
    </div>
@else
    <div class="bb-quick-search-empty">
        {{ __('No results found!') }}
    </div>
@endif
