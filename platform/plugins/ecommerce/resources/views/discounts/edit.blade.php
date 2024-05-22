@extends(BaseHelper::getAdminMasterLayoutTemplate())

@push('header')
    @include('plugins/ecommerce::discounts.partials.trans')

    {!! JsValidator::formRequest(Botble\Ecommerce\Http\Requests\DiscountRequest::class) !!}
@endpush

@section('content')
    <x-core::form>
        <discount-component
            currency="{{ get_application_currency()->symbol }}"
            :discount="{{ $discount }}"
        ></discount-component>
    </x-core::form>
@endsection
