<x-core::card.body class="d-print-none">
    <x-core::datagrid>
        <x-core::datagrid.item>
            <x-slot:title>
                {{ trans('plugins/ecommerce::shipping.shipping') }}
            </x-slot:title>
            <a
                href="{{ route('ecommerce.shipments.edit', $shipment->id) }}"
                target="_blank"
            >
                <h4>{{ get_shipment_code($shipment->id) }}</h4>
            </a>
        </x-core::datagrid.item>

        <x-core::datagrid.item>
            <x-slot:title>
                {{ trans('plugins/ecommerce::shipping.status') }}
            </x-slot:title>
            {!! BaseHelper::clean($shipment->status->toHtml()) !!}
        </x-core::datagrid.item>

        <x-core::datagrid.item>
            <x-slot:title>
                {{ trans('plugins/ecommerce::shipping.shipping_method') }}
            </x-slot:title>
            {{ $shipment->order->shipping_method_name }}
        </x-core::datagrid.item>

        <x-core::datagrid.item>
            <x-slot:title>
                {{ trans('plugins/ecommerce::shipping.weight_unit', ['unit' => ecommerce_weight_unit()]) }}
            </x-slot:title>
            {{ $shipment->weight }} {{ ecommerce_weight_unit() }}
        </x-core::datagrid.item>

        <x-core::datagrid.item>
            <x-slot:title>
                {{ trans('plugins/ecommerce::shipping.updated_at') }}
            </x-slot:title>
            {{ $shipment->updated_at }}
        </x-core::datagrid.item>

        @if((float) $shipment->cod_amount)
            <x-core::datagrid.item>
                <x-slot:title>
                    {{ trans('plugins/ecommerce::shipping.cod_amount') }}
                </x-slot:title>
                {{ format_price($shipment->cod_amount) }}
            </x-core::datagrid.item>
        @endif

        @if ($shipment->note)
            <x-core::datagrid.item>
                <x-slot:title>
                    {{ trans('plugins/ecommerce::shipping.delivery_note') }}
                </x-slot:title>
                {{ $shipment->note }}
            </x-core::datagrid.item>
        @endif
    </x-core::datagrid>
</x-core::card.body>

@if (
    auth()->user()->hasPermission( 'ecommerce.shipments.edit')
    && $shipment->status != Botble\Ecommerce\Enums\ShippingStatusEnum::CANCELED
    && $order->status != Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED
)
    <x-core::card.footer class="shipment-actions-wrapper btn-list">
        @if (in_array($shipment->status, [
            Botble\Ecommerce\Enums\ShippingStatusEnum::NOT_APPROVED,
            Botble\Ecommerce\Enums\ShippingStatusEnum::APPROVED,
        ]))
            <x-core::button
                type="button"
                class="btn-cancel-shipment"
                :data-action="route('orders.cancel-shipment', $shipment->id)"
            >
                {{ trans('plugins/ecommerce::shipping.cancel_shipping') }}
            </x-core::button>
        @endif

        <x-core::button
            type="button"
            class="btn-trigger-update-shipping-status"
            icon="ti ti-truck-delivery"
        >
            {{ trans('plugins/ecommerce::shipping.update_shipping_status') }}
        </x-core::button>

        <x-core::button tag="a" :href="route('ecommerce.shipments.print', $shipment)" target="_blank" icon="ti ti-printer">
            {{ trans('plugins/ecommerce::shipping.shipping_label.print_shipping_label') }}
        </x-core::button>

        {!! apply_filters('shipment_buttons_detail_order', null, $shipment) !!}
    </x-core::card.footer>
@endif
