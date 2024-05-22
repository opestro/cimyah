<section class="tp-product-area position-relative pb-90">
    <div class="container">
        {!! Theme::partial('section-title', ['shortcode' => $shortcode, 'class' => 'mb-40 text-center']) !!}

        <div class="row">
            <div class="col-xl-12">
                <div class="tp-product-tab-2 tp-tab mb-50 text-center">
                    <nav>
                        <div
                            class="nav nav-tabs justify-content-center"
                            id="productTab"
                            role="tablist"
                            data-ajax-url="{{ route('public.ajax.products', ['limit' => $shortcode->limit ?: 8]) }}"
                        >
                            @foreach($productTabs as $key => $tab)
                                @continue(! in_array($key, $selectedTabs) || (! EcommerceHelper::isReviewEnabled() && $key === 'top-rated'))

                                <button
                                    @class(['nav-link', 'active' => $loop->first])
                                    id="{{ $key }}-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#tab-pane"
                                    type="button"
                                    role="tab"
                                    aria-controls="tab-pane"
                                    @if ($loop->first) aria-selected="true" @endif
                                    data-bb-toggle="product-tab"
                                    data-bb-value="{{ $key }}"
                                >
                                    {{ $tab }}
                                    <span class="tp-product-tab-tooltip">0</span>
                                </button>
                            @endforeach
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="tab-content" id="productTabContent">
                    <div class="tab-pane fade show active" id="tab-pane" role="tabpanel" aria-labelledby="tab" tabindex="0"></div>
                </div>
            </div>
        </div>
    </div>
</section>
