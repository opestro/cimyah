<section class="tp-search-area">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="tp-search-form">
                    <div class="mb-20 text-center tp-search-close">
                        <button class="tp-search-close-btn"></button>
                    </div>
                    <x-plugins-ecommerce::fronts.ajax-search>
                        <div class="mb-10 tp-search-input">
                            <x-plugins-ecommerce::fronts.ajax-search.input />
                            <button type="submit" title="Search"><x-core::icon name="ti ti-search" /></button>
                        </div>
                    </x-plugins-ecommerce::fronts.ajax-search>
                </div>
            </div>
        </div>
    </div>
</section>
