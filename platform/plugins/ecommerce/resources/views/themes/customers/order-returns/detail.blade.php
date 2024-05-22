@extends(EcommerceHelper::viewPath('customers.master'))

@section('title', __('Return Product(s)'))

@section('content')
    @php
        Theme::set('pageName', __('Return Product(s)'));
    @endphp

    @include(EcommerceHelper::viewPath('includes.order-return-detail'))
@stop
