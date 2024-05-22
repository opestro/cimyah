<div class="container">
    <h3>{{ __('Our Stores') }}</h3>

    <div class="row">
        @foreach ($stores as $store)
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                @include('plugins/marketplace::themes.includes.store-item')
            </div>
        @endforeach
    </div>

    {!! $stores->withQueryString()->links() !!}
</div>
