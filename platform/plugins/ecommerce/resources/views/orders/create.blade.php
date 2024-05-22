@extends(BaseHelper::getAdminMasterLayoutTemplate())

@push('header')
    <script>
        'use strict';

        window.trans = window.trans || {};

        window.trans.order = {{ Js::from(trans('plugins/ecommerce::order')) }};
    </script>
@endpush

@section('content')
    <create-order
        :currency="'{{ get_application_currency()->symbol }}'"
        :zip_code_enabled="{{ (int) EcommerceHelper::isZipCodeEnabled() }}"
        :use_location_data="{{ (int) EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation() }}"
        :is_tax_enabled={{ (int) EcommerceHelper::isTaxEnabled() }}
        :sub_amount_label="'{{ format_price(0) }}'"
        :tax_amount_label="'{{ format_price(0) }}'"
        :promotion_amount_label="'{{ format_price(0) }}'"
        :discount_amount_label="'{{ format_price(0) }}'"
        :shipping_amount_label="'{{ format_price(0) }}'"
        :total_amount_label="'{{ format_price(0) }}'"
    ></create-order>
@endsection
