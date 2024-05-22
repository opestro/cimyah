<x-core::modal
    id="update-shipping-status-modal"
    :title="trans('plugins/ecommerce::shipping.update_shipping_status')"
    button-id="confirm-update-shipping-status-button"
    :button-label="trans('plugins/ecommerce::order.update')"
>
    <x-core::form :url="$updateShippingStatusUrl ?? route('ecommerce.shipments.update-status', $shipment->id)">
        <x-core::form.select
            :label="trans('plugins/ecommerce::shipping.status')"
            name="status"
            :options="Botble\Ecommerce\Enums\ShippingStatusEnum::labels()"
            :value="$shipment->status"
        />
    </x-core::form>
</x-core::modal>
