@extends(EcommerceHelper::viewPath('customers.master'))

@section('title',  __('Request Return Product(s)'))

@section('content')
    @include(EcommerceHelper::viewPath('includes.order-return-form'))
@stop
