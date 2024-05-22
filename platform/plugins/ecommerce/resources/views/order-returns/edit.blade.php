@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row">
        <div class="col-md-8">
            @include('plugins/ecommerce::order-returns.partials.order-information', [
                'productEditRouteName' => 'products.edit',
                'orderReturnEditRouteName' => 'order_returns.edit',
            ])
        </div>

        <div class="col-md-4">
            @include('plugins/ecommerce::order-returns.partials.customer-information')
        </div>
    </div>
@endsection
