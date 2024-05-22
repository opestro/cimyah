<div class="bb-shop-banner" @if ($coverImage) style="background-image: url('{{ RvMedia::getImageUrl($coverImage) }}');" @endif>
    <div class="bb-shop-banner-overlay"></div>
    <div class="container bb-shop-banner-content">
        {{ RvMedia::image($store->logo, $store->name, useDefaultImage: true, attributes: ['class' => 'bb-shop-banner-logo']) }}

        <div class="bb-shop-banner-info">
            <h2 class="bb-shop-banner-name">{{ $store->name }}</h2>

            @if (EcommerceHelper::isReviewEnabled())
                <div class="bb-shop-banner-rating">
                    @include(EcommerceHelper::viewPath('includes.rating-star'), ['avg' => $store->reviews()->avg('star'), 'size' => 80])
                    <small>{{ __('(:count reviews)', ['count' => number_format($store->reviews->count())]) }}</small>
                </div>
            @endif

            @if ($store->full_address || $store->phone || $store->email)
                <div class="bb-shop-banner-contact">
                    <div class="bb-shop-banner-address d-flex gap-1">
                        <x-core::icon name="ti ti-map-pin" />
                        {{ $store->full_address }}
                    </div>

                    @if (!MarketplaceHelper::hideStorePhoneNumber() && $store->phone)
                        <div class="bb-shop-banner-phone d-flex gap-1">
                            <x-core::icon name="ti ti-phone" />
                            <a href="tel:{{ $store->phone }}">{{ $store->phone }}</a>
                        </div>
                    @endif

                    @if (!MarketplaceHelper::hideStoreEmail() && $store->email)
                        <div class="bb-shop-banner-address d-flex gap-1">
                            <x-core::icon name="ti ti-mail" />
                            <a href="mailto:{{ $store->email }}">{{ $store->email }}</a>
                        </div>
                    @endif
                </div>
            @endif

            @if ($store->description)
                <div class="bb-shop-banner-description">
                    {!! BaseHelper::clean($store->description) !!}
                </div>
            @endif

            @if (!MarketplaceHelper::hideStoreSocialLinks() && ($socials = $store->getMetaData('socials', true)))
                <ul class="bb-shop-banner-socials">
                    @foreach ((array) ['facebook', 'instagram', 'x', 'youtube', 'linkedin'] as $social)
                        @continue(empty($link = Arr::get($socials, $social)))

                        <li>
                            <a href="{{ $link }}" target="_blank"><x-core::icon :name="'ti ti-brand-' . $social" /></a>
                        </li>
                    @endforeach

                    @if ($twitter = Arr::get($socials, 'twitter'))
                        <li>
                            <a href="{{ $twitter }}" target="_blank"><x-core::icon name="ti ti-brand-x" /></a>
                        </li>
                    @endif
                </ul>
            @endif
        </div>
    </div>
</div>
