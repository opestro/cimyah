@extends(BaseHelper::getAdminMasterLayoutTemplate())

@push('header-action')
    <x-core::button
        tag="a"
        href="{{ route('ecommerce.invoice.generate-invoice', ['invoice' => $invoice, 'type' => 'print']) }}"
        target="_blank"
        icon="ti ti-printer"
    >
        {{ trans('plugins/ecommerce::invoice.print') }}
    </x-core::button>

    <x-core::button
        href="{{ route('ecommerce.invoice.generate-invoice', ['invoice' => $invoice, 'type' => 'download']) }}"
        tag="a"
        target="_blank"
        icon="ti ti-download"
    >
        {{ trans('plugins/ecommerce::invoice.download') }}
    </x-core::button>
@endpush

@section('content')
    <div class="row">
        <x-core::card>
            <x-core::card.body>
                <div class="row align-items-center">
                    <div class="col-md-6">
                        @if ($invoice->company_logo)
                            <img
                                src="{{ RvMedia::getImageUrl($invoice->company_logo) }}"
                                alt="{{ $invoice->company_name }}"
                                style="max-height: 150px;"
                            >
                        @endif
                    </div>
                    <div class="col-md-6 text-end">
                        <h2 class="mb-0 uppercase">{{ trans('plugins/ecommerce::invoice.heading') }}</h2>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-6">
                    </div>
                    <div class="col-6 text-end">
                        <ul class="list-unstyled">
                            @if ($customerName = $invoice->customer_name)
                                <li>{{ $customerName }}</li>
                            @endif
                            @if ($customerEmail = $invoice->customer_email)
                                <li>{{ $customerEmail }}</li>
                            @endif
                            @if ($customerPhone = $invoice->customer_phone)
                                <li>{{ $customerPhone }}</li>
                            @endif
                            @if ($customerAddress = $invoice->customer_address)
                                <li>{{ $customerAddress }}</li>
                            @endif

                            @if ($customerTaxID = $invoice->customer_tax_id)
                                <li>{{ __('Tax ID:') }} {{ $customerTaxID }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="invoice-info">
                    <div class="mb-3">
                        <div class="row">
                            <div class="hr"></div>
                            <x-core::datagrid class="mb-3">
                                <x-core::datagrid.item>
                                    <x-slot:title>
                                        {{ trans('plugins/ecommerce::invoice.detail.code') }}
                                    </x-slot:title>
                                    {{ $invoice->code }}
                                </x-core::datagrid.item>
                                @if ($invoice->created_at)
                                    <x-core::datagrid.item>
                                        <x-slot:title>
                                            {{ trans('plugins/ecommerce::invoice.detail.issue_at') }}
                                        </x-slot:title>
                                        {{ $invoice->created_at->translatedFormat('j F, Y') }}
                                    </x-core::datagrid.item>
                                @endif
                                @if (is_plugin_active('payment') && $invoice->payment->payment_channel->label())
                                    <x-core::datagrid.item>
                                        <x-slot:title>
                                            {{ trans('plugins/ecommerce::invoice.payment_method') }}
                                        </x-slot:title>
                                        {{ $invoice->payment->payment_channel->label() }}
                                    </x-core::datagrid.item>
                                @endif
                            </x-core::datagrid>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <x-core::table>
                            <x-core::table.header>
                                <x-core::table.header.cell>#</x-core::table.header.cell>
                                <x-core::table.header.cell>
                                    {{ __('Image') }}
                                </x-core::table.header.cell>
                                <x-core::table.header.cell>
                                    {{ __('Product') }}
                                </x-core::table.header.cell>
                                <x-core::table.header.cell>
                                    {{ __('Amount') }}
                                </x-core::table.header.cell>
                                <x-core::table.header.cell>
                                    {{ __('Quantity') }}
                                </x-core::table.header.cell>
                                <x-core::table.header.cell>
                                    {{ __('Total') }}
                                </x-core::table.header.cell>
                            </x-core::table.header>

                            <x-core::table.body>
                                @foreach ($invoice->items as $invoiceItem)
                                    <x-core::table.body.row>
                                        @php
                                            $product = get_products([
                                            'condition' => [
                                                'ec_products.id' => $invoiceItem->reference_id,
                                            ],
                                            'take'   => 1,
                                            'select' => [
                                                'ec_products.id',
                                                'ec_products.images',
                                                'ec_products.name',
                                                'ec_products.price',
                                                'ec_products.sale_price',
                                                'ec_products.sale_type',
                                                'ec_products.start_date',
                                                'ec_products.end_date',
                                                'ec_products.sku',
                                                'ec_products.is_variation',
                                                'ec_products.status',
                                                'ec_products.order',
                                                'ec_products.created_at',
                                            ],
                                            ]);
                                        @endphp
                                            <x-core::table.body.cell>
                                                {{ $loop->iteration }}
                                            </x-core::table.body.cell>
                                            <x-core::table.body.cell>
                                                <img
                                                    src="{{ RvMedia::getImageUrl($invoiceItem->image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                                    alt="{{ $invoiceItem->name }}"
                                                    width="50"
                                                >
                                            </x-core::table.body.cell>
                                            <x-core::table.body.cell>
                                                @if($product && $product->original_product?->url)
                                                    <a href="{{ $product->original_product->url }}">{!! BaseHelper::clean($invoiceItem->name) !!}</a>
                                                @else
                                                    {!! BaseHelper::clean($invoiceItem->name) !!}
                                                @endif
                                                @if ($sku = Arr::get($invoiceItem->options, 'sku'))
                                                    ({{ $sku }})
                                                @endif

                                                @if ($attributes = Arr::get($invoiceItem->options, 'attributes'))
                                                    <p class="mb-0 small">
                                                        {{ $attributes }}
                                                    </p>
                                                @elseif ($product && $product->is_variation)
                                                    <p class="small">
                                                        @php
                                                            $attributes = get_product_attributes($product->id);
                                                        @endphp

                                                        @foreach ($attributes as $attribute)
                                                            {{ $attribute->attribute_set_title }}: {{ $attribute->title }}@if (!$loop->last), @endif
                                                        @endforeach
                                                    </p>
                                                @endif

                                                @include(
                                                    EcommerceHelper::viewPath('includes.cart-item-options-extras'),
                                                    ['options' => $invoiceItem->options]
                                                )

                                                @if (is_plugin_active('marketplace') && ($product = $invoiceItem->reference) && $product->original_product->store->id)
                                                    <p class="mb-0 small">
                                                        {{ __('Sold by') }}
                                                        <a href="{{ $product->original_product->store->url }}" class="text-primary">
                                                            {{$product->original_product->store->name }}
                                                        </a>
                                                    </p>
                                                @endif
                                            </x-core::table.body.cell>
                                            <x-core::table.body.cell>
                                                {{ $invoiceItem->amount_format }}
                                            </x-core::table.body.cell>
                                            <x-core::table.body.cell>
                                                {{ $invoiceItem->qty }}
                                            </x-core::table.body.cell>
                                            <x-core::table.body.cell class="money">
                                                <strong>
                                                    {{ $invoiceItem->total_format }}
                                                </strong>
                                            </x-core::table.body.cell>
                                    </x-core::table.body.row>
                                @endforeach
                            </x-core::table.body>

                            <x-core::table.footer>
                                <x-core::table.body.row>
                                    <x-core::table.body.cell colspan="4"></x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        {{ trans('plugins/ecommerce::invoice.detail.quantity') }}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell class="fw-bold">
                                        {{ number_format($invoice->items->sum('qty')) }}
                                    </x-core::table.body.cell>
                                </x-core::table.body.row>
                                <x-core::table.body.row>
                                    <x-core::table.body.cell colspan="4"></x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        {{ trans('plugins/ecommerce::invoice.detail.sub_total') }}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell class="fw-bold">
                                        {{ format_price($invoice->sub_total) }}
                                    </x-core::table.body.cell>
                                </x-core::table.body.row>
                                @if ($invoice->tax_amount > 0)
                                    <x-core::table.body.row>
                                        <x-core::table.body.cell colspan="4"></x-core::table.body.cell>
                                        <x-core::table.body.cell>
                                            {{ trans('plugins/ecommerce::invoice.detail.tax') }}
                                        </x-core::table.body.cell>
                                        <x-core::table.body.cell class="fw-bold">
                                            {{ format_price($invoice->tax_amount) }}
                                        </x-core::table.body.cell>
                                    </x-core::table.body.row>
                                @endif
                                <x-core::table.body.row>
                                    <x-core::table.body.cell colspan="4"></x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        {{ trans('plugins/ecommerce::invoice.detail.shipping_fee') }}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell class="fw-bold">
                                        {{ format_price($invoice->shipping_amount) }}
                                    </x-core::table.body.cell>
                                </x-core::table.body.row>
                                <x-core::table.body.row>
                                    <x-core::table.body.cell colspan="4"></x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        {{ trans('plugins/ecommerce::invoice.detail.discount') }}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell class="fw-bold">
                                        {{ format_price($invoice->discount_amount) }}
                                    </x-core::table.body.cell>
                                </x-core::table.body.row>
                                <x-core::table.body.row>
                                    <x-core::table.body.cell colspan="4"></x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        {{ trans('plugins/ecommerce::invoice.detail.grand_total') }}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell class="fw-bold">
                                        {{ format_price($invoice->amount) }}
                                    </x-core::table.body.cell>
                                </x-core::table.body.row>
                                <x-core::table.body.row>
                                    <x-core::table.body.cell colspan="4"></x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        {{ trans('plugins/ecommerce::invoice.total_amount') }}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell class="fw-bold">
                                        <h3 class="mt-0 mb-0 text-danger">{{ format_price($invoice->amount) }}</h3>
                                    </x-core::table.body.cell>
                                </x-core::table.body.row>
                            </x-core::table.footer>
                        </x-core::table>
                    </div>

                    <div class="mt-3">
                        @if ($invoice->reference && $invoice->reference->id)
                            <x-core::form.label>
                                <small>
                                    {{ trans('plugins/ecommerce::invoice.detail.invoice_for') }}:
                                    <a href="{{ route('orders.edit', $invoice->reference->id) }}" target="_blank">
                                        {{ $invoice->reference->code }}
                                        <x-core::icon name="ti ti-external-link" />
                                    </a>
                                </small>
                            </x-core::form.label>
                        @endif

                        <p class="font-sm">
                            @if ($invoice->company_name)
                                <strong>{{ trans('plugins/ecommerce::invoice.detail.invoice_to') }}:</strong>
                                {{ $invoice->company_name }}<br>
                            @endif

                            @if ($invoice->customer_tax_id)
                                <strong>{{ trans('plugins/ecommerce::invoice.detail.tax_id') }}:</strong>
                                {{ $invoice->customer_tax_id }}<br>
                            @endif

                            {!! apply_filters('ecommerce_admin_invoice_extra_info', null, $invoice->reference) !!}
                        </p>
                    </div>
                </div>
            </x-core::card.body>
        </x-core::card>
    </div>
@endsection
