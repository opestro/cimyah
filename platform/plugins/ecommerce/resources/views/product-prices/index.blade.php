@extends($layout ?? BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core::alert type="warning">
        {!! BaseHelper::clean(trans('plugins/ecommerce::product-prices.warning_prices')) !!}
    </x-core::alert>

    @include('core/table::base-table')
@endsection
