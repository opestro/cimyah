@php
    $ads = null;

    if (is_plugin_active('ads') && $shortcode->ads) {
        $ads = \Botble\Ads\Models\Ads::query()
            ->wherePublished()
            ->where('key', $shortcode->ads)
            ->first();
    }
@endphp

<section class="tp-product-sm-area pt-30 pb-30">
    <div class="container">
        @if($ads)
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="tp-product-side-banner-thumb">
                        {!! Theme::partial('shortcodes.ads.includes.item', ['item' => $ads]) !!}
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-6">
                    @endif
                    <div class="row">
                        @foreach($groups as $group)
                            <div @class(['col-md-4' => ! $ads, 'col-md-6' => $ads])>
                                <div class="tp-product-sm-wrapper-5 mb-60">
                                    <h3 class="tp-product-sm-section-title">
                                        {{ $group['title'] }}
                                        {!! Theme::partial('section-title-shape') !!}
                                    </h3>

                                    <div class="tp-product-sm-item-wrapper-5">
                                        @foreach($group['products'] as $product)
                                            @include(Theme::getThemeNamespace('views.ecommerce.includes.product.style-1.small'))
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($ads)
                </div>
            </div>
        @endif
    </div>
</section>
