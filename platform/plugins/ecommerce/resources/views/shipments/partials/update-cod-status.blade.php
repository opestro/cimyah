@if ((float) $shipment->cod_amount)
    <x-core::modal
        id="update-shipping-cod-status-modal"
        :title="trans('plugins/ecommerce::shipping.update_cod_status')"
        button-id="confirm-update-shipping-status-button"
        :button-label="trans('plugins/ecommerce::order.update')"
    >
        <x-core::form :url="$updateCodStatusUrl ?? route('ecommerce.shipments.update-cod-status', $shipment->id)">
            <x-core::form.select
                :label="trans('plugins/ecommerce::shipping.status')"
                name="status"
                :options="Botble\Ecommerce\Enums\ShippingCodStatusEnum::labels()"
                :value="$shipment->cod_status"
            />
        </x-core::form>
    </x-core::modal>
@endif
