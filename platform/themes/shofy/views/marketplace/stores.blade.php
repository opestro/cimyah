@php
    Theme::layout('full-width');
    Theme::set('pageTitle', __('Stores'));
@endphp

<div class="tp-page-area pt-30 pb-120">
    <div class="container">
        <div class="tp-shop-top mb-45">
            <div class="tp-shop-top-left d-flex flex-wrap gap-3 justify-content-between align-items-center">
                <div class="tp-shop-top-result">
                    <p>{{ __('Showing :from-:to of :total stores', ['from' => $stores->firstItem(), 'to' => $stores->lastItem(), 'total' => $stores->total()]) }}</p>
                </div>

                <x-core::form :url="route('public.stores')" method="get">
                    <div class="tp-sidebar-search-input">
                        <input type="search" name="q" placeholder="{{ __('Search...') }}" value="{{ BaseHelper::stringify(old('q', request()->query('q'))) }}">
                        <button type="submit" title="Search">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.11111 15.2222C12.0385 15.2222 15.2222 12.0385 15.2222 8.11111C15.2222 4.18375 12.0385 1 8.11111 1C4.18375 1 1 4.18375 1 8.11111C1 12.0385 4.18375 15.2222 8.11111 15.2222Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M16.9995 17L13.1328 13.1333" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>
                </x-core::form>
            </div>
        </div>

        <div class="row g-4 mb-40">
            @foreach ($stores as $store)
                @php
                    $coverImage = $store->getMetaData('background', true);
                @endphp

                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                    @include('plugins/marketplace::themes.includes.store-item')
                </div>
            @endforeach
        </div>

        {{ $stores->withQueryString()->links(Theme::getThemeNamespace('partials.pagination')) }}
    </div>
</div>
