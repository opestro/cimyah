<div class="card bb-store-item">
    <div class="bb-store-item-content">
        <a href="{{ $store->url }}">
            <h4>{{ $store->name }}</h4>
        </a>

        @if (EcommerceHelper::isReviewEnabled())
            <div class="d-flex align-items-center gap-1 bb-store-item-rating">
                @include(EcommerceHelper::viewPath('includes.rating-star'), ['avg' => $store->reviews()->avg('star')])
                <a href="{{ $store->url }}" class="small">{{ __('(:count reviews)', ['count' => number_format($store->reviews->count())]) }}</a>
            </div>
        @endif

        @if($store->full_address)
            <p class="bb-store-item-info text-truncate">
                <x-core::icon name="ti ti-map-pin" />{{ $store->full_address }}
            </p>
        @endif

        @if (!MarketplaceHelper::hideStorePhoneNumber() && $store->phone)
            <p class="bb-store-item-info">
                <x-core::icon name="ti ti-phone" />
                <a href="tel:{{ $store->phone }}">{{ $store->phone }}</a>
            </p>
        @endif

        @if (!MarketplaceHelper::hideStoreEmail() && $store->email)
            <p class="bb-store-item-info">
                <x-core::icon name="ti ti-mail" />
                {{ Html::mailto($store->email) }}
            </p>
        @endif
    </div>

    <div class="bb-store-item-footer">
        <div class="bb-store-item-logo">
            <a href="{{ $store->url }}">
                {{ RvMedia::image($store->logo, $store->name, useDefaultImage: true) }}
            </a>
        </div>

        <div class="bb-store-item-action">
            <a href="{{ $store->url }}" class="btn btn-primary">
                <x-core::icon name="ti ti-building-store" />
                {{ __('Visit Store') }}
            </a>
        </div>
    </div>
</div>
