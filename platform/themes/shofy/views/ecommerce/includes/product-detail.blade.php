@if ($product->brand_id)
    <div class="tp-product-details-category">
        <span>
            <a href="{{ $product->brand->url }}">{{ $product->brand->name }}</a>
        </span>
    </div>
@endif

<a href="{{ $product->url }}">
    <h1 class="tp-product-details-title">{{ $product->name }}</h1>
</a>

<div class="tp-product-details-inventory d-flex align-items-center mb-10">
    @if (is_plugin_active('marketplace') && $product->store_id)
        <div class="tp-product-details-stock mb-10">
            <span><a href="{{ $product->store->url }}">{{ $product->store->name }}</a></span>
        </div>
    @endif

    @if (EcommerceHelper::isReviewEnabled())
        <div class="tp-product-details-rating-wrapper d-flex align-items-center mb-10">
            <div class="tp-product-details-rating">
                @include(EcommerceHelper::viewPath('includes.rating-star'), ['avg' => $product->reviews_avg])
            </div>
            <div class="tp-product-details-reviews">
                <a href="{{ $product->url }}#product-review" data-bb-toggle="scroll-to-review">{{ __('(:count reviews)', ['count' => number_format($product->reviews_count)]) }}</a>
            </div>
        </div>
    @endif
</div>

{!! apply_filters('ecommerce_before_product_description', null, $product) !!}

@if ($product->description)
    <div class="tp-product-details-description mb-20">
        {!! BaseHelper::clean($product->description) !!}
    </div>
@endif

{!! apply_filters('ecommerce_after_product_description', null, $product) !!}

@include(EcommerceHelper::viewPath('includes.product-price'), [
    'priceWrapperClassName' => 'tp-product-details-price-wrapper mb-20',
    'priceClassName' => 'tp-product-details-price new-price',
    'priceOriginalWrapperClassName' => '',
    'priceOriginalClassName' => 'tp-product-details-price old-price',
])

<x-core::form :url="route('public.cart.add-to-cart')" method="POST" class="product-form">
    <input type="hidden" name="id" value="{{ ($product->is_variation || !$product->defaultVariation->product_id) ? $product->id : $product->defaultVariation->product_id }}" />

    @if ($product->variations->isNotEmpty())
        {!! render_product_swatches($product, ['selected' => $selectedAttrs]) !!}
    @endif

    {!! render_product_options($product) !!}

    @include(Theme::getThemeNamespace('views.ecommerce.includes.product-availability'))

    @if (isset($flashSale))
        <div class="tp-product-details-countdown justify-content-between flex-wrap mt-25 mb-25">
            <h4 class="tp-product-details-countdown-title">
                <x-core::icon name="ti ti-flame" />
                {{ __('Flash Sale end in:') }}
            </h4>
            <div class="tp-product-details-countdown-time" data-countdown data-date="{{ $flashSale->end_date }}">
                <ul>
                    <li><span data-days>0</span>D</li>
                    <li><span data-hours>0</span>H</li>
                    <li><span data-minutes>0</span>M</li>
                    <li><span data-seconds>0</span>S</li>
                </ul>
            </div>
        </div>
    @endif

    {!! apply_filters(ECOMMERCE_PRODUCT_DETAIL_EXTRA_HTML, null, $product) !!}

    @if (EcommerceHelper::isCartEnabled())
        @php
            $isOutOfStock = $product->isOutOfStock();
        @endphp
        <div @class(['tp-product-details-action-wrapper mt-3', 'tp-cart-disabled' => $isOutOfStock])>
            <h3 class="tp-product-details-action-title">{{ __('Quantity') }}</h3>
            <div class="tp-product-details-action-item-wrapper d-flex align-items-center">
                <div class="tp-product-details-quantity">
                    <div class="tp-product-quantity mb-15 mr-15">
                        <span class="tp-cart-minus" data-bb-toggle="decrease-qty">
                            <svg width="11" height="2" viewBox="0 0 11 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 1H10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <input class="tp-cart-input" type="number" name="qty" min="1" value="1" max="{{ $product->with_storehouse_management ? $product->quantity : 1000 }}" @readonly($isOutOfStock) />
                        <span class="tp-cart-plus" data-bb-toggle="increase-qty">
                            <svg width="11" height="12" viewBox="0 0 11 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 6H10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M5.5 10.5V1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="tp-product-details-add-to-cart mb-15 w-100">
                    <button
                        type="submit"
                        name="add-to-cart"
                        @class(['tp-product-details-add-to-cart-btn w-100', 'btn-disabled' => $isOutOfStock])
                        @disabled($isOutOfStock)
                        data-bb-toggle="add-to-cart-in-form"
                        {!! EcommerceHelper::jsAttributes('add-to-cart-in-form', $product) !!}
                    >
                        {{ __('Add To Cart') }}
                    </button>
                </div>
            </div>
            @if (EcommerceHelper::isQuickBuyButtonEnabled())
                <button
                    type="submit"
                    name="checkout"
                    @class(['tp-product-details-buy-now-btn w-100', 'btn-disabled' => $isOutOfStock])
                    @disabled($isOutOfStock)
                >{{ __('Buy Now') }}</button>
            @endif
        </div>
    @endif

    <div class="tp-product-details-action-sm">
        @if (EcommerceHelper::isCompareEnabled())
            <button
                type="button"
                @class(['tp-product-details-action-sm-btn', 'active' => is_product_in_compare($product->original_product->id)])
                data-bb-toggle="add-to-compare" title="Add to compare"
                data-url="{{ route('public.compare.add', $product) }}"
                data-remove-url="{{ route('public.compare.remove', $product) }}"
            >
                <svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 3.16431H10.8622C12.0451 3.16431 12.9999 4.08839 12.9999 5.23315V7.52268" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M3.25177 0.985168L1 3.16433L3.25177 5.34354" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12.9999 12.5983H3.13775C1.95486 12.5983 1 11.6742 1 10.5295V8.23993" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M10.748 14.7774L12.9998 12.5983L10.748 10.4191" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                {{ __('Compare') }}
            </button>
        @endif
        @if (EcommerceHelper::isWishlistEnabled())
            <button
                type="button"
                @class(['tp-product-details-action-sm-btn', 'active' => is_product_in_wishlist($product->original_product->id)])
                data-bb-toggle="add-to-wishlist" title="Add to wishlist"
                data-url="{{ route('public.wishlist.add', $product) }}"
            >
                <svg width="17" height="16" viewBox="0 0 17 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M2.33541 7.54172C3.36263 10.6766 7.42094 13.2113 8.49945 13.8387C9.58162 13.2048 13.6692 10.6421 14.6635 7.5446C15.3163 5.54239 14.7104 3.00621 12.3028 2.24514C11.1364 1.8779 9.77578 2.1014 8.83648 2.81432C8.64012 2.96237 8.36757 2.96524 8.16974 2.81863C7.17476 2.08487 5.87499 1.86999 4.69024 2.24514C2.28632 3.00549 1.68259 5.54167 2.33541 7.54172ZM8.50115 15C8.4103 15 8.32018 14.9784 8.23812 14.9346C8.00879 14.8117 2.60674 11.891 1.29011 7.87081C1.28938 7.87081 1.28938 7.8701 1.28938 7.8701C0.462913 5.33895 1.38316 2.15812 4.35418 1.21882C5.7492 0.776121 7.26952 0.97088 8.49895 1.73195C9.69029 0.993159 11.2729 0.789057 12.6401 1.21882C15.614 2.15956 16.5372 5.33966 15.7115 7.8701C14.4373 11.8443 8.99571 14.8088 8.76492 14.9332C8.68286 14.9777 8.592 15 8.50115 15Z"
                        fill="currentColor"
                    />
                    <path
                        d="M8.49945 13.8387L8.42402 13.9683L8.49971 14.0124L8.57526 13.9681L8.49945 13.8387ZM14.6635 7.5446L14.5209 7.4981L14.5207 7.49875L14.6635 7.5446ZM12.3028 2.24514L12.348 2.10211L12.3478 2.10206L12.3028 2.24514ZM8.83648 2.81432L8.92678 2.93409L8.92717 2.9338L8.83648 2.81432ZM8.16974 2.81863L8.25906 2.69812L8.25877 2.69791L8.16974 2.81863ZM4.69024 2.24514L4.73548 2.38815L4.73552 2.38814L4.69024 2.24514ZM8.23812 14.9346L8.16727 15.0668L8.16744 15.0669L8.23812 14.9346ZM1.29011 7.87081L1.43266 7.82413L1.39882 7.72081H1.29011V7.87081ZM1.28938 7.8701L1.43938 7.87009L1.43938 7.84623L1.43197 7.82354L1.28938 7.8701ZM4.35418 1.21882L4.3994 1.36184L4.39955 1.36179L4.35418 1.21882ZM8.49895 1.73195L8.42 1.85949L8.49902 1.90841L8.57801 1.85943L8.49895 1.73195ZM12.6401 1.21882L12.6853 1.0758L12.685 1.07572L12.6401 1.21882ZM15.7115 7.8701L15.5689 7.82356L15.5686 7.8243L15.7115 7.8701ZM8.76492 14.9332L8.69378 14.8011L8.69334 14.8013L8.76492 14.9332ZM2.19287 7.58843C2.71935 9.19514 4.01596 10.6345 5.30013 11.744C6.58766 12.8564 7.88057 13.6522 8.42402 13.9683L8.57487 13.709C8.03982 13.3978 6.76432 12.6125 5.49626 11.517C4.22484 10.4185 2.97868 9.02313 2.47795 7.49501L2.19287 7.58843ZM8.57526 13.9681C9.12037 13.6488 10.4214 12.8444 11.7125 11.729C12.9999 10.6167 14.2963 9.17932 14.8063 7.59044L14.5207 7.49875C14.0364 9.00733 12.7919 10.4 11.5164 11.502C10.2446 12.6008 8.9607 13.3947 8.42364 13.7093L8.57526 13.9681ZM14.8061 7.59109C15.1419 6.5613 15.1554 5.39131 14.7711 4.37633C14.3853 3.35729 13.5989 2.49754 12.348 2.10211L12.2576 2.38816C13.4143 2.75381 14.1347 3.54267 14.4905 4.48255C14.8479 5.42648 14.8379 6.52568 14.5209 7.4981L14.8061 7.59109ZM12.3478 2.10206C11.137 1.72085 9.72549 1.95125 8.7458 2.69484L8.92717 2.9338C9.82606 2.25155 11.1357 2.03494 12.2577 2.38821L12.3478 2.10206ZM8.74618 2.69455C8.60221 2.8031 8.40275 2.80462 8.25906 2.69812L8.08043 2.93915C8.33238 3.12587 8.67804 3.12163 8.92678 2.93409L8.74618 2.69455ZM8.25877 2.69791C7.225 1.93554 5.87527 1.71256 4.64496 2.10213L4.73552 2.38814C5.87471 2.02742 7.12452 2.2342 8.08071 2.93936L8.25877 2.69791ZM4.64501 2.10212C3.39586 2.49722 2.61099 3.35688 2.22622 4.37554C1.84299 5.39014 1.85704 6.55957 2.19281 7.58826L2.478 7.49518C2.16095 6.52382 2.15046 5.42513 2.50687 4.48154C2.86175 3.542 3.58071 2.7534 4.73548 2.38815L4.64501 2.10212ZM8.50115 14.85C8.43415 14.85 8.36841 14.8341 8.3088 14.8023L8.16744 15.0669C8.27195 15.1227 8.38645 15.15 8.50115 15.15V14.85ZM8.30897 14.8024C8.19831 14.7431 6.7996 13.9873 5.26616 12.7476C3.72872 11.5046 2.07716 9.79208 1.43266 7.82413L1.14756 7.9175C1.81968 9.96978 3.52747 11.7277 5.07755 12.9809C6.63162 14.2373 8.0486 15.0032 8.16727 15.0668L8.30897 14.8024ZM1.29011 7.72081C1.31557 7.72081 1.34468 7.72745 1.37175 7.74514C1.39802 7.76231 1.41394 7.78437 1.42309 7.8023C1.43191 7.81958 1.43557 7.8351 1.43727 7.84507C1.43817 7.8504 1.43869 7.85518 1.43898 7.85922C1.43913 7.86127 1.43923 7.8632 1.43929 7.865C1.43932 7.86591 1.43934 7.86678 1.43936 7.86763C1.43936 7.86805 1.43937 7.86847 1.43937 7.86888C1.43937 7.86909 1.43937 7.86929 1.43938 7.86949C1.43938 7.86959 1.43938 7.86969 1.43938 7.86979C1.43938 7.86984 1.43938 7.86992 1.43938 7.86994C1.43938 7.87002 1.43938 7.87009 1.28938 7.8701C1.13938 7.8701 1.13938 7.87017 1.13938 7.87025C1.13938 7.87027 1.13938 7.87035 1.13938 7.8704C1.13938 7.8705 1.13938 7.8706 1.13938 7.8707C1.13938 7.8709 1.13938 7.87111 1.13938 7.87131C1.13939 7.87173 1.13939 7.87214 1.1394 7.87257C1.13941 7.87342 1.13943 7.8743 1.13946 7.8752C1.13953 7.87701 1.13962 7.87896 1.13978 7.88103C1.14007 7.88512 1.14059 7.88995 1.14151 7.89535C1.14323 7.90545 1.14694 7.92115 1.15585 7.93861C1.16508 7.95672 1.18114 7.97896 1.20762 7.99626C1.2349 8.01409 1.26428 8.02081 1.29011 8.02081V7.72081ZM1.43197 7.82354C0.623164 5.34647 1.53102 2.26869 4.3994 1.36184L4.30896 1.0758C1.23531 2.04755 0.302663 5.33142 1.14679 7.91665L1.43197 7.82354ZM4.39955 1.36179C5.7527 0.932384 7.22762 1.12136 8.42 1.85949L8.57791 1.60441C7.31141 0.820401 5.74571 0.619858 4.30881 1.07585L4.39955 1.36179ZM8.57801 1.85943C9.73213 1.14371 11.2694 0.945205 12.5951 1.36192L12.685 1.07572C11.2763 0.632908 9.64845 0.842602 8.4199 1.60447L8.57801 1.85943ZM12.5948 1.36184C15.4664 2.27018 16.3769 5.34745 15.5689 7.82356L15.8541 7.91663C16.6975 5.33188 15.7617 2.04893 12.6853 1.07581L12.5948 1.36184ZM15.5686 7.8243C14.9453 9.76841 13.2952 11.4801 11.7526 12.7288C10.2142 13.974 8.80513 14.7411 8.69378 14.8011L8.83606 15.0652C8.9555 15.0009 10.3826 14.2236 11.9413 12.9619C13.4957 11.7037 15.2034 9.94602 15.8543 7.91589L15.5686 7.8243ZM8.69334 14.8013C8.6337 14.8337 8.56752 14.85 8.50115 14.85V15.15C8.61648 15.15 8.73201 15.1217 8.83649 15.065L8.69334 14.8013Z"
                        fill="currentColor"
                    />
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M12.8384 6.93209C12.5548 6.93209 12.3145 6.71865 12.2911 6.43693C12.2427 5.84618 11.8397 5.34743 11.266 5.1656C10.9766 5.07361 10.8184 4.76962 10.9114 4.48718C11.0059 4.20402 11.3129 4.05023 11.6031 4.13934C12.6017 4.45628 13.3014 5.32371 13.3872 6.34925C13.4113 6.64606 13.1864 6.90622 12.8838 6.92993C12.8684 6.93137 12.8538 6.93209 12.8384 6.93209Z"
                        fill="currentColor"
                    />
                    <path
                        d="M12.8384 6.93209C12.5548 6.93209 12.3145 6.71865 12.2911 6.43693C12.2427 5.84618 11.8397 5.34743 11.266 5.1656C10.9766 5.07361 10.8184 4.76962 10.9114 4.48718C11.0059 4.20402 11.3129 4.05023 11.6031 4.13934C12.6017 4.45628 13.3014 5.32371 13.3872 6.34925C13.4113 6.64606 13.1864 6.90622 12.8838 6.92993C12.8684 6.93137 12.8538 6.93209 12.8384 6.93209"
                        stroke="currentColor"
                        stroke-width="0.3"
                    />
                </svg>
                {{ __('Add Wishlist') }}
            </button>
        @endif
    </div>
</x-core::form>

<div class="tp-product-details-query">
    <div class="tp-product-details-query-item " @style(['display: none' => ! $product->sku])>
        <span>{{ __('SKU:') }}</span>
        <a data-bb-value="product-sku">{{ $product->sku }}</a>
    </div>
    @if ($product->categories->isNotEmpty())
        <div class="tp-product-details-query-item">
            <span>{{ __('Category:') }}</span>
            @foreach($product->categories as $category)
                <a href="{{ $category->url }}">{{ $category->name }}</a><span class="me-1">@if (!$loop->last),@endif</span>
            @endforeach
        </div>
    @endif
    @if ($product->tags->isNotEmpty())
        <div class="tp-product-details-query-item">
            <span>{{ __('Tag:') }}</span>
            @foreach($product->tags as $tag)
                <a href="{{ $tag->url }}">{{ $tag->name }}</a><span class="me-1">@if (!$loop->last),@endif</span>
            @endforeach
        </div>
    @endif
</div>
