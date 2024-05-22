@extends(EcommerceHelper::viewPath('customers.master'))

@section('title', __('Add a new address'))

@section('content')
    {!! Form::open(['route' => 'customer.address.create']) !!}
        @include(EcommerceHelper::viewPath('customers.address.form'), ['address' => new Botble\Ecommerce\Models\Address(), 'form'])
    {!! Form::close() !!}
@endsection
