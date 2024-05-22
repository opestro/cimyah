<x-core::button
    type="button"
    icon="ti ti-code"
    data-bs-toggle="collapse"
    data-bs-target="#collapse-response-source"
    class="mb-3"
>
    {{ trans('plugins/payment::payment.view_response_source') }}
</x-core::button>

<div class="collapse" id="collapse-response-source">
    <pre>{{ json_encode($payment, JSON_PRETTY_PRINT) }}</pre>
</div>
