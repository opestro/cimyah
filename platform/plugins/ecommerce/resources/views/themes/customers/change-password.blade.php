@extends(EcommerceHelper::viewPath('customers.layouts.account-settings'))

@section('title', __('Change password'))

@section('account-content')
    {!! $form->renderForm() !!}
@stop
