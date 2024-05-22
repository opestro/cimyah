<div class="tp-product-details-social">
    <span>{{ __('Share:') }}</span>

    {!! Theme::renderSocialSharing($product->url, SeoHelper::getDescription(), $product->image) !!}
</div>
