<x-core::button
    tag="a"
    :href="route('ecommerce.invoice.generate-invoice', ['invoice' => $invoice, 'type' => 'print'])"
    icon="ti ti-printer"
    target="_blank"
>
    {{ trans('plugins/ecommerce::invoice.print') }}
</x-core::button>

<x-core::button
    tag="a"
    :href="route('ecommerce.invoice.generate-invoice', ['invoice' => $invoice, 'type' => 'download'])"
    icon="ti ti-download"
    target="_blank"
>
    {{ trans('plugins/ecommerce::invoice.download') }}
</x-core::button>
