@extends(EcommerceHelper::viewPath('customers.master'))

@section('title', __('Address books'))

@section('content')
    <div class="dashboard-address">
        @if ($addresses->isNotEmpty())
            <div class="row row-cols-md-2 row-cols-1 g-3">
                @foreach ($addresses as $address)
                    @include(EcommerceHelper::viewPath('customers.address.item'), ['address' => $address])
                @endforeach
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-start mt-4">
            <a class="btn btn-primary" href="{{ route('customer.address.create') }}">
                {{ __('Add a new address') }}
            </a>
        </div>
    </div>
@endsection
