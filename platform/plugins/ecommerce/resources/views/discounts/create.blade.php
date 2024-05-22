@extends(BaseHelper::getAdminMasterLayoutTemplate())

@push('header')
    @include('plugins/ecommerce::discounts.partials.trans')
@endpush

@section('content')
    <x-core::form>
        <discount-component
            currency="{{ get_application_currency()->symbol }}"
        ></discount-component>
    </x-core::form>
@stop

@push('footer')
    {!! $jsValidation !!}
@endpush
