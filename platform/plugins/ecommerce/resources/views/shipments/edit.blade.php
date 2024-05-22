@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    @include('plugins/ecommerce::shipments.notification')

    <div class="row">
        <div class="col-md-9">
            @include('plugins/ecommerce::shipments.products', [
                'productEditRouteName' => Auth::user()->hasPermission('products.edit') ? 'products.edit' : '',
                'orderEditRouteName' => Auth::user()->hasPermission('orders.edit') ? 'orders.edit' : '',
            ])

            @include('plugins/ecommerce::shipments.form')

            @include('plugins/ecommerce::shipments.histories')
        </div>

        <div class="col-md-3">
            @include('plugins/ecommerce::shipments.information', [
                'orderEditRouteName' => Auth::user()->hasPermission('orders.edit') ? 'orders.edit' : '',
            ])

            <x-core::card class="mt-3">
                <x-core::card.header>
                    <x-core::card.title>
                        {{ trans('plugins/ecommerce::shipping.shipping_label.name') }}
                    </x-core::card.title>
                </x-core::card.header>

                <x-core::card.body>
                    <x-core::button tag="a" :href="route('ecommerce.shipments.print', $shipment)" target="_blank" icon="ti ti-printer">
                        {{ trans('plugins/ecommerce::shipping.shipping_label.print') }}
                    </x-core::button>
                </x-core::card.body>
            </x-core::card>
        </div>
    </div>
@endsection

@push('footer')
    @if(! $shipment->isCancelled)
        @include('plugins/ecommerce::shipments.partials.update-cod-status', [
            'shipment' => $shipment,
        ])

        @include('plugins/ecommerce::shipments.partials.update-status-modal', [
            'shipment' => $shipment,
        ])
    @endif
@endpush
