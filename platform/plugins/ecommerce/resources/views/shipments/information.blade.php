<x-core::card class="mb-3">
    <x-core::card.header>
        <x-core::card.title>
            {{ trans('plugins/ecommerce::shipping.shipment_information') }}
        </x-core::card.title>
    </x-core::card.header>

    <x-core::card.body>
        <dl class="d-flex flex-column gap-3">
            <div class="row">
                <dt class="col">
                    {{ trans('plugins/ecommerce::shipping.order_number') }}
                </dt>
                <dd class="col-auto">
                    @if ($orderEditRouteName)
                        <a href="{{ route($orderEditRouteName, $shipment->order->id) }}" target="_blank">
                            {{ $shipment->order->code }}
                            <x-core::icon name="ti ti-external-link" />
                        </a>
                    @else
                        {{ $shipment->order->code }}
                    @endif

                </dd>
            </div>
            <div class="row">
                <dt class="col">
                    {{ trans('plugins/ecommerce::shipping.shipping_method') }}
                </dt>
                <dd class="col-auto">
                    {{ OrderHelper::getShippingMethod($shipment->order->shipping_method) }}
                    @if ($shipment->order->shipping_option)
                        ({{ $shipment->order->shipping_method_name }})
                    @endif
                </dd>
            </div>
            <div class="row">
                <dt class="col">
                    {{ trans('plugins/ecommerce::shipping.shipping_fee') }}
                </dt>
                <dd class="col-auto">
                    {{ format_price($shipment->price) }}
                </dd>
            </div>
            @if ((float) $shipment->cod_amount)
                <div class="row">
                    <dt class="col">
                        {{ trans('plugins/ecommerce::shipping.cod_amount') }}
                    </dt>
                    <dd class="col-auto">
                        {{ format_price($shipment->cod_amount) }}
                    </dd>
                </div>
                <div class="row">
                    <dt class="col">
                        {{ trans('plugins/ecommerce::shipping.cod_status') }}
                    </dt>
                    <dd class="col-auto">
                        {!! BaseHelper::clean($shipment->cod_status->toHtml()) !!}
                    </dd>
                    @if ((float) $shipment->cod_amount && !$shipment->isCancelled)
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#update-shipping-cod-status-modal" class="fw-semibold d-inline-block small mt-n1">
                            <x-core::icon name="ti ti-pencil" size="sm" />
                            {{ trans('plugins/ecommerce::shipping.update_cod_status') }}
                        </a>
                    @endif
                </div>
            @endif
            <div class="row">
                <dt class="col">
                    {{ trans('plugins/ecommerce::shipping.shipping_status') }}
                </dt>
                <dd class="col-auto">
                    {!! BaseHelper::clean($shipment->status->toHtml()) !!}
                </dd>
                @if(!$shipment->isCancelled)
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#update-shipping-status-modal" class="fw-semibold d-inline-block small mt-n1">
                        <x-core::icon name="ti ti-pencil" size="sm" />
                        {{ trans('plugins/ecommerce::shipping.update_shipping_status') }}
                    </a>
                @endif
            </div>
        </dl>
    </x-core::card.body>
</x-core::card>

<x-core::card>
    <x-core::card.header>
        <x-core::card.title>
            {{ trans('plugins/ecommerce::shipping.customer_information') }}
        </x-core::card.title>
    </x-core::card.header>

    <x-core::card.body>
        <dl class="shipping-address-info">
            @include('plugins/ecommerce::orders.shipping-address.detail', [
                'address' => $shipment->order->address,
            ])
        </dl>
    </x-core::card.body>
</x-core::card>
