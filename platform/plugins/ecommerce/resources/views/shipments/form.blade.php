<x-core::card class="mb-3">
    <x-core::card.header>
        <x-core::card.title>
            {{ trans('plugins/ecommerce::shipping.additional_shipment_information') }}
        </x-core::card.title>
    </x-core::card.header>
    <x-core::card.body>
        {!! Botble\Ecommerce\Forms\ShipmentInfoForm::createFromModel($shipment)->renderForm() !!}
    </x-core::card.body>
</x-core::card>
