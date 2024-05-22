<section class="tp-product-sm-area">
    <div class="container">
        <div class="row g-4">
            @foreach($groups as $group)
                <div class="col-xl-{{ 12 / count($groups) }} col-md-6">
                    <div class="tp-product-sm-list mb-50">
                        <div class="tp-section-title-wrapper">
                            <h3 class="section-title tp-section-title tp-section-title-sm">
                                {{ $group['title'] }}
                                {!! Theme::partial('section-title-shape') !!}
                            </h3>
                        </div>

                        <div class="tp-product-sm-wrapper">
                            @foreach($group['products'] as $product)
                                @include(Theme::getThemeNamespace('views.ecommerce.includes.product.style-1.small'))
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
