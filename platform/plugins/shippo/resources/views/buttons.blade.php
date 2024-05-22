@if (app(Botble\Shippo\Shippo::class)->canCreateTransaction($shipment))
    @php
        $url = route('ecommerce.shipments.shippo.show', $shipment->id);
        if (!is_in_admin(true) && is_plugin_active('marketplace')) {
            $url = route('marketplace.vendor.orders.shippo.show', $shipment->id);
        }
    @endphp
    <button
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#shippo-view-n-create-transaction"
        data-url="{{ $url }}"
        type="button"
    >
        <img
            src="{{ url('vendor/core/plugins/shippo/images/icon.svg') }}"
            alt="shippo"
            height="16"
            class="me-1"
            style="filter: brightness(0) invert(1);"
        >
        <span>{{ trans('plugins/shippo::shippo.transaction.view_and_create') }}</span>
    </button>

    <div
        class="modal fade"
        id="shippo-view-n-create-transaction"
        aria-labelledby="shippo-view-n-create-transaction-label"
        aria-hidden="true"
        tabindex="-1"
    >
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5
                        class="modal-title"
                        id="shippo-view-n-create-transaction-label"
                    >{{ trans('plugins/shippo::shippo.transaction.view_and_create') }}</h5>
                    <button
                        class="btn-close"
                        data-bs-dismiss="modal"
                        type="button"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
@endif

@if ($shipment->label_url)
    <a
        class="btn btn-success"
        href="{{ $shipment->label_url }}"
        target="_blank"
        rel="noopener noreferrer"
    >
        <i class="fa fa-print"></i>
        <span>{{ trans('plugins/shippo::shippo.print_label') }}</span>
    </a>
@endif
