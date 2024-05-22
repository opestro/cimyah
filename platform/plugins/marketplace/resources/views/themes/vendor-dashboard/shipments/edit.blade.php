@extends(MarketplaceHelper::viewPath('vendor-dashboard.layouts.master'))
@section('content')
    <div class="row">
        <div class="col-md-9">
            @include('plugins/ecommerce::shipments.notification')

            @include('plugins/ecommerce::shipments.products', [
                'productEditRouteName' => 'marketplace.vendor.products.edit',
                'orderEditRouteName' => 'marketplace.vendor.orders.edit',
            ])

            @include('plugins/ecommerce::shipments.form', [
                'updateStatusRouteName' => 'marketplace.vendor.orders.update-shipping-status',
                'updateCodStatusRouteName' => 'marketplace.vendor.shipments.update-cod-status',
            ])

            @include('plugins/ecommerce::shipments.histories')
        </div>

        <div class="col-md-3">
            @include('plugins/ecommerce::shipments.information', [
                'orderEditRouteName' => 'marketplace.vendor.orders.edit',
            ])
        </div>
    </div>
@stop

@push('footer')
    @if(!$shipment->isCancelled)
        @include('plugins/ecommerce::shipments.partials.update-cod-status', [
            'shipment' => $shipment,
            'updateCodStatusUrl' => route('marketplace.vendor.shipments.update-cod-status', $shipment->id),
        ])

        @include('plugins/ecommerce::shipments.partials.update-status-modal', [
            'shipment' => $shipment,
            'updateShippingStatusUrl' => route('marketplace.vendor.orders.update-shipping-status', $shipment->id),
        ])
    @endif
@endpush
