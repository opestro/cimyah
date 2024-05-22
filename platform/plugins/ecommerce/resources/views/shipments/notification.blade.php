@if ($shipment->isCancelled)
    <x-core::alert type="warning">
        <x-slot:title>
            {{ trans('plugins/ecommerce::shipping.shipment_canceled') }}
        </x-slot:title>

        {{ trans('plugins/ecommerce::shipping.at') }}
        <i>{{ BaseHelper::formatDate($shipment->updated_at, 'H:i d/m/Y') }}</i>
    </x-core::alert>
@endif
