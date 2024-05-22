@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div id="main-order-content">
        @include('plugins/ecommerce::orders.partials.canceled-alert', compact('order'))

        <div class="row row-cards">
            <div class="col-md-9">
                <x-core::card class="mb-3">
                    <x-core::card.header class="justify-content-between">
                        <x-core::card.title>
                            {{ trans('plugins/ecommerce::order.order_information') }} {{ $order->code }}
                        </x-core::card.title>

                        @if ($order->completed_at)
                            <x-core::badge color="info" class="d-flex align-items-center gap-1">
                                <x-core::icon name="ti ti-shopping-cart-check"></x-core::icon>
                                {{ trans('plugins/ecommerce::order.completed') }}
                            </x-core::badge>
                        @else
                            <x-core::badge color="warning" class="d-flex align-items-center gap-1">
                                <x-core::icon name="ti ti-shopping-cart"></x-core::icon>
                                {{ trans('plugins/ecommerce::order.uncompleted') }}
                            </x-core::badge>
                        @endif
                    </x-core::card.header>

                    <x-core::table :hover="false" :striped="false" class="order-products-table">
                        <x-core::table.body>
                            @foreach ($order->products as $orderProduct)
                                @php
                                    $product = $orderProduct->product->original_product;
                                @endphp

                                <x-core::table.body.row>
                                    <x-core::table.body.cell style="width: 80px">
                                        <img
                                            src="{{ RvMedia::getImageUrl($orderProduct->product_image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                            alt="{{ $orderProduct->product_name }}"
                                        >
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell style="width: 45%">
                                        <div class="d-flex align-items-center flex-wrap">
                                            <a
                                                href="{{ $product && $product->id && Auth::user()->hasPermission('products.edit') ? route('products.edit', $product->id) : '#' }}"
                                                title="{{ $orderProduct->product_name }}"
                                                target="_blank"
                                                class="me-2"
                                            >
                                                {{ $orderProduct->product_name }}
                                            </a>

                                            @if ($sku = Arr::get($orderProduct->options, 'sku') ?: ($product && $product->sku ? $product->sku : null))
                                                <p class="mb-0">({{ trans('plugins/ecommerce::order.sku') }}: <strong>{{ $sku }}</strong>)</p>
                                            @endif
                                        </div>

                                        @if ($attributes = Arr::get($orderProduct->options, 'attributes'))
                                            <div>
                                                <small>{{ $attributes }}</small>
                                            </div>
                                        @endif

                                        @if (!empty($orderProduct->product_options) && is_array($orderProduct->product_options))
                                            {!! render_product_options_html($orderProduct->product_options, $orderProduct->price) !!}
                                        @endif

                                        @include(
                                            EcommerceHelper::viewPath('includes.cart-item-options-extras'),
                                            ['options' => $orderProduct->options]
                                        )

                                        {!! apply_filters(ECOMMERCE_ORDER_DETAIL_EXTRA_HTML, null) !!}

                                        @if ($order->shipment->id)
                                            <p class="text-muted mb-1">
                                                {{ $orderProduct->qty }}
                                                {{ trans('plugins/ecommerce::order.completed') }}
                                            </p>
                                            <ul class="list-unstyled ms-1 small">
                                                <li>
                                                    <span class="bull">↳</span>
                                                    <span class="black">{{ trans('plugins/ecommerce::order.shipping') }}</span>
                                                    <a
                                                        class="text-underline bold-light"
                                                        href="{{ route('ecommerce.shipments.edit', $order->shipment->id) }}"
                                                        title="{{ $order->shipping_method_name }}"
                                                        target="_blank"
                                                    >{{ $order->shipping_method_name }}</a>
                                                </li>

                                                @if (is_plugin_active('marketplace') && $order->store->name)
                                                    <li class="ws-nm">
                                                        <span class="bull">↳</span>
                                                        <span
                                                            class="black">{{ trans('plugins/marketplace::store.store') }}</span>
                                                        <a
                                                            class="fw-semibold text-decoration-underline"
                                                            href="{{ $order->store->url }}"
                                                            target="_blank"
                                                        >{{ $order->store->name }}</a>
                                                    </li>
                                                @endif
                                            </ul>
                                        @endif
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        {{ format_price($orderProduct->price) }}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        x
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        {{ $orderProduct->qty }}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        {{ format_price($orderProduct->price * $orderProduct->qty) }}
                                    </x-core::table.body.cell>
                                </x-core::table.body.row>
                            @endforeach
                        </x-core::table.body>
                    </x-core::table>

                    <x-core::card.body>
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <x-core::table :hover="false" :striped="false" class="table-borderless text-end">
                                    <x-core::table.body>
                                        <x-core::table.body.row>
                                            <x-core::table.body.cell>{{ trans('plugins/ecommerce::order.quantity') }}</x-core::table.body.cell>
                                            <x-core::table.body.cell>
                                                {{ number_format($order->products->sum('qty')) }}
                                            </x-core::table.body.cell>
                                        </x-core::table.body.row>
                                        <x-core::table.body.row>
                                            <x-core::table.body.cell>
                                                {{ trans('plugins/ecommerce::order.sub_amount') }}</x-core::table.body.cell>
                                            <x-core::table.body.cell>
                                                {{ format_price($order->sub_total) }}
                                            </x-core::table.body.cell>
                                        </x-core::table.body.row>
                                        <x-core::table.body.row>
                                            <x-core::table.body.cell>
                                                {{ trans('plugins/ecommerce::order.discount') }}
                                                @if ($order->coupon_code)
                                                    <p class="mb-0">
                                                        {!! trans('plugins/ecommerce::order.coupon_code', [
                                                            'code' => Html::tag('strong', $order->coupon_code)->toHtml(),
                                                        ]) !!}
                                                    </p>
                                                @elseif ($order->discount_description)
                                                    <p class="mb-0">{{ $order->discount_description }}</p>
                                                @endif
                                            </x-core::table.body.cell>
                                            <x-core::table.body.cell>
                                                {{ format_price($order->discount_amount) }}
                                            </x-core::table.body.cell>
                                        </x-core::table.body.row>
                                        <x-core::table.body.row>
                                            <x-core::table.body.cell>
                                                <p class="mb-1">{{ trans('plugins/ecommerce::order.shipping_fee') }}</p>
                                                <span class="small d-block">{{ $order->shipping_method_name }}</span>
                                                <span class="small d-block">{{ $weight }} {{ ecommerce_weight_unit(true) }}</span>
                                            </x-core::table.body.cell>
                                            <x-core::table.body.cell>
                                                {{ format_price($order->shipping_amount) }}
                                            </x-core::table.body.cell>
                                        </x-core::table.body.row>
                                        @if (EcommerceHelper::isTaxEnabled())
                                            <x-core::table.body.row>
                                                <x-core::table.body.cell>
                                                    {{ trans('plugins/ecommerce::order.tax') }}
                                                </x-core::table.body.cell>
                                                <x-core::table.body.cell>
                                                    {{ format_price($order->tax_amount) }}
                                                </x-core::table.body.cell>
                                            </x-core::table.body.row>
                                        @endif
                                        <x-core::table.body.row>
                                            <x-core::table.body.cell>
                                                {{ trans('plugins/ecommerce::order.total_amount') }}
                                            </x-core::table.body.cell>
                                            <x-core::table.body.cell>
                                                @if (is_plugin_active('payment') && $order->payment->id)
                                                    <span @class(['text-warning' => $order->payment->status != Botble\Payment\Enums\PaymentStatusEnum::COMPLETED]) class="text-warning">
                                                        {{ format_price($order->amount) }}
                                                    </span>
                                                @else
                                                    {{ format_price($order->amount) }}
                                                @endif
                                            </x-core::table.body.cell>
                                        </x-core::table.body.row>

                                        <x-core::table.body.row>
                                            <x-core::table.body.cell>
                                                {{ trans('plugins/ecommerce::order.paid_amount') }}
                                            </x-core::table.body.cell>
                                            <x-core::table.body.cell>
                                                @if (is_plugin_active('payment') && $order->payment->id)
                                                    <a
                                                        href="{{ route('payment.show', $order->payment->id) }}"
                                                        target="_blank"
                                                    >
                                                        <span>{{ format_price($order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::COMPLETED ? $order->payment->amount : 0) }}</span>
                                                    </a>
                                                @else
                                                    <span>{{ format_price(is_plugin_active('payment') && $order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::COMPLETED ? $order->payment->amount : 0) }}</span>
                                                @endif
                                            </x-core::table.body.cell>
                                        </x-core::table.body.row>

                                        @if (is_plugin_active('payment') && $order->payment->id)
                                            <x-core::table.body.row>
                                                <x-core::table.body.cell>
                                                    {{ trans('plugins/ecommerce::order.payment_method') }}
                                                </x-core::table.body.cell>
                                                <x-core::table.body.cell>
                                                    <a href="{{ route('payment.show', $order->payment->id) }}" target="_blank">
                                                        {{ $order->payment->payment_channel->label() }}

                                                        <x-core::icon name="ti ti-external-link" />
                                                    </a>
                                                </x-core::table.body.cell>
                                            </x-core::table.body.row>

                                            <x-core::table.body.row>
                                                <x-core::table.body.cell>
                                                    {{ trans('plugins/ecommerce::order.payment_status_label') }}
                                                </x-core::table.body.cell>
                                                <x-core::table.body.cell>
                                                    {!! BaseHelper::clean($order->payment->status->toHtml()) !!}
                                                </x-core::table.body.cell>
                                            </x-core::table.body.row>
                                        @endif

                                        @if ($order->proof_file && Storage::disk('local')->exists($order->proof_file))
                                            <x-core::table.body.row>
                                                <x-core::table.body.cell>
                                                    {{ trans('plugins/ecommerce::order.payment_proof') }}
                                                </x-core::table.body.cell>
                                                <x-core::table.body.cell>
                                                    <a href="{{ route('orders.download-proof', $order->id) }}" target="_blank">
                                                        {{ $order->proof_file }}
                                                    </a>
                                                </x-core::table.body.cell>
                                            </x-core::table.body.row>
                                        @endif

                                        {!! apply_filters('ecommerce_admin_order_extra_info', null, $order) !!}

                                        <x-core::table.body.row>
                                            <td colspan="2">
                                                <hr class="my-0">
                                            </td>
                                        </x-core::table.body.row>

                                        @if (is_plugin_active('payment') && $order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::REFUNDED)
                                            <x-core::table.body.row class="hidden">
                                                <x-core::table.body.cell>
                                                    {{ trans('plugins/ecommerce::order.refunded_amount') }}
                                                </x-core::table.body.cell>
                                                <x-core::table.body.cell>
                                                    <span>{{ format_price($order->payment->amount) }}</span>
                                                </x-core::table.body.cell>
                                            </x-core::table.body.row>
                                        @endif
                                        <x-core::table.body.row class="hidden">
                                            <x-core::table.body.cell>
                                                {{ trans('plugins/ecommerce::order.amount_received') }}
                                            </x-core::table.body.cell>
                                            <x-core::table.body.cell>
                                                {{ format_price(is_plugin_active('payment') && $order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::COMPLETED ? $order->amount : 0) }}
                                            </x-core::table.body.cell>
                                        </x-core::table.body.row>
                                    </x-core::table.body>
                                </x-core::table>

                                <div class="btn-list justify-content-end my-3">
                                    @if ($order->isInvoiceAvailable())
                                        <x-core::button
                                            tag="a"
                                            href="{{ route('orders.generate-invoice', $order->id) }}?type=print"
                                            target="_blank"
                                            icon="ti ti-printer"
                                        >
                                            {{ trans('plugins/ecommerce::order.print_invoice') }}
                                        </x-core::button>
                                        <x-core::button
                                            tag="a"
                                            :href="route('orders.generate-invoice', $order->id)"
                                            target="_blank"
                                            icon="ti ti-download"
                                        >
                                            {{ trans('plugins/ecommerce::order.download_invoice') }}
                                        </x-core::button>
                                    @else
                                        <x-core::button
                                            tag="a"
                                            :href="route('orders.invoice.generate', $order->id)"
                                            target="_blank"
                                            icon="ti ti-file-type-pdf"
                                        >
                                            {{ trans('plugins/ecommerce::order.generate_invoice') }}
                                        </x-core::button>
                                    @endif
                                </div>

                                <form action="{{ route('orders.edit', $order->id) }}">
                                    <x-core::form.textarea
                                        :label="trans('plugins/ecommerce::order.note')"
                                        name="description"
                                        :placeholder="trans('plugins/ecommerce::order.add_note')"
                                        :value="$order->description"
                                        class="textarea-auto-height"
                                    />

                                    <x-core::button type="button" class="btn-update-order">
                                        {{ trans('plugins/ecommerce::order.save') }}
                                    </x-core::button>
                                </form>
                            </div>
                        </div>
                    </x-core::card.body>

                    <div class="list-group list-group-flush">
                        @if ($order->status != Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED || $order->is_confirmed)
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                <div class="text-uppercase">
                                    <x-core::icon name="ti ti-check" @class(['text-success' => $order->is_confirmed]) />
                                    @if ($order->is_confirmed)
                                        {{ trans('plugins/ecommerce::order.order_was_confirmed') }}
                                    @else
                                        {{ trans('plugins/ecommerce::order.confirm_order') }}
                                    @endif
                                </div>
                                @if (!$order->is_confirmed)
                                    <form action="{{ route('orders.confirm') }}">
                                        <input name="order_id" type="hidden" value="{{ $order->id }}">
                                        <x-core::button type="button" color="info" class="btn-confirm-order">
                                            {{ trans('plugins/ecommerce::order.confirm') }}
                                        </x-core::button>
                                    </form>
                                @endif
                            </div>
                        @endif
                        @if ($order->status == Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED || is_plugin_active('payment') && $order->payment->id)
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                @if ($order->status == Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED)
                                    <div class="d-flex align-items-start gap-1">
                                        <x-core::icon name="ti ti-circle-off" />
                                        <div>
                                            <span class="text-uppercase">{{ trans('plugins/ecommerce::order.order_was_canceled') }}</span>

                                            @if($order->cancellation_reason)
                                                <div class="text-muted small">
                                                    {{ trans('plugins/ecommerce::order.cancellation_reason', ['reason' => $order->cancellation_reason_message]) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @elseif (is_plugin_active('payment') && $order->payment->id)
                                    <div class="text-uppercase">
                                        @if (!$order->payment->status || $order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::PENDING)
                                            <x-core::icon name="ti ti-credit-card" />
                                        @elseif (
                                            $order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::COMPLETED
                                            || $order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::PENDING
                                        )
                                            <x-core::icon name="ti ti-check" class="text-success" />
                                        @endif

                                        @if (!$order->payment->status || $order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::PENDING)
                                            {{ trans('plugins/ecommerce::order.pending_payment') }}
                                        @elseif ($order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::COMPLETED)
                                            {{ trans('plugins/ecommerce::order.payment_was_accepted', ['money' => format_price($order->payment->amount - $order->payment->refunded_amount)]) }}
                                        @elseif ($order->payment->amount - $order->payment->refunded_amount == 0)
                                            {{ trans('plugins/ecommerce::order.payment_was_refunded') }}
                                        @endif
                                    </div>

                                    <div class="btn-list">
                                        @if (!$order->payment->status || in_array($order->payment->status, [Botble\Payment\Enums\PaymentStatusEnum::PENDING]))
                                            <x-core::button
                                                type="button"
                                                color="info"
                                                class="btn-trigger-confirm-payment"
                                                :data-target="route('orders.confirm-payment', $order->id)"
                                            >
                                                {{ trans('plugins/ecommerce::order.confirm_payment') }}
                                            </x-core::button>
                                        @endif
                                        @if (
                                            $order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::COMPLETED
                                            && (
                                                $order->payment->amount - $order->payment->refunded_amount > 0
                                                || $order->products->sum('qty') - $order->products->sum('restock_quantity') > 0
                                            )
                                        )
                                            <x-core::button type="button" class="btn-trigger-refund">
                                                {{ trans('plugins/ecommerce::order.refund') }}
                                            </x-core::button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if (EcommerceHelper::countDigitalProducts($order->products) != $order->products->count())
                            <div class="p-3 d-flex justify-content-between align-items-center">
                                @if ($order->status == Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED && !$order->shipment->id)
                                    <div class="text-uppercase">
                                        <x-core::icon name="ti ti-check" class="text-success" />
                                        <span>{{ trans('plugins/ecommerce::order.all_products_are_not_delivered') }}</span>
                                    </div>
                                @else
                                    @if ($order->shipment->id)
                                        <div class="text-uppercase">
                                            <x-core::icon name="ti ti-check" class="text-success" />
                                            <span>{{ trans('plugins/ecommerce::order.delivery') }}</span>
                                        </div>
                                    @else
                                        <div class="text-uppercase">
                                            <x-core::icon name="ti ti-truck" />
                                            <span>{{ trans('plugins/ecommerce::order.delivery') }}</span>
                                        </div>

                                        <x-core::button
                                            type="button"
                                            class="btn-trigger-shipment"
                                            color="info"
                                            :data-target="route('orders.get-shipment-form', $order->id)"
                                        >
                                            {{ trans('plugins/ecommerce::order.delivery') }}
                                        </x-core::button>
                                    @endif
                                @endif
                            </div>

                            @if (! $order->shipment->id)
                                <div class="shipment-create-wrap" style="display: none;"></div>
                            @else
                                @include('plugins/ecommerce::orders.shipment-detail', [
                                    'shipment' => $order->shipment,
                                ])
                            @endif
                        @endif
                    </div>
                </x-core::card>

                <x-core::card>
                    <x-core::card.header>
                        <x-core::card.title>
                            {{ trans('plugins/ecommerce::order.history') }}
                        </x-core::card.title>
                    </x-core::card.header>

                    <x-core::card.body>
                        <ul class="steps steps-vertical" id="order-history-wrapper">
                            @foreach ($order->histories()->orderByDesc('id')->get() as $history)
                                <li @class(['step-item', 'user-action' => $history->user_id])>
                                    <div class="h4 m-0">
                                        @if (in_array($history->action, ['confirm_payment', 'refund']))
                                            <a
                                                class="show-timeline-dropdown text-primary"
                                                data-target="#history-line-{{ $history->id }}"
                                                href="javascript:void(0)"
                                            >
                                                {{ OrderHelper::processHistoryVariables($history) }}
                                            </a>
                                        @else
                                            {{ OrderHelper::processHistoryVariables($history) }}
                                        @endif
                                    </div>
                                    <div class="text-secondary">{{ BaseHelper::formatDateTime($history->created_at) }}</div>
                                    @if ($history->action == 'refund' && Arr::get($history->extras, 'amount', 0) > 0)
                                        <div
                                            class="timeline-dropdown bg-body mt-2 rounded-2"
                                            style="display: none"
                                            id="history-line-{{ $history->id }}"
                                        >
                                            <x-core::table :striped="false" :hover="false" class="w-100">
                                                <x-core::table.body>
                                                    <x-core::table.body.row>
                                                        <x-core::table.body.cell>
                                                            {{ trans('plugins/ecommerce::order.order_number') }}
                                                        </x-core::table.body.cell>
                                                        <x-core::table.body.cell>
                                                            <a
                                                                href="{{ route('orders.edit', $order->id) }}"
                                                                title="{{ $order->code }}"
                                                            >
                                                                {{ $order->code }}
                                                            </a>
                                                        </x-core::table.body.cell>
                                                    </x-core::table.body.row>
                                                    <x-core::table.body.row>
                                                        <x-core::table.body.cell>
                                                            {{ trans('plugins/ecommerce::order.description') }}
                                                        </x-core::table.body.cell>
                                                        <x-core::table.body.cell>
                                                            {{ $history->description . ' ' . trans('plugins/ecommerce::order.from') . ' ' . $order->payment->payment_channel->label() }}
                                                        </x-core::table.body.cell>
                                                    </x-core::table.body.row>
                                                    <x-core::table.body.row>
                                                        <x-core::table.body.cell>
                                                            {{ trans('plugins/ecommerce::order.amount') }}
                                                        </x-core::table.body.cell>
                                                        <x-core::table.body.cell>
                                                            {{ format_price(Arr::get($history->extras, 'amount', 0)) }}
                                                        </x-core::table.body.cell>
                                                    </x-core::table.body.row>
                                                    <x-core::table.body.row>
                                                        <x-core::table.body.cell>
                                                            {{ trans('plugins/ecommerce::order.status') }}
                                                        </x-core::table.body.cell>
                                                        <x-core::table.body.cell>
                                                            {{ trans('plugins/ecommerce::order.successfully') }}
                                                        </x-core::table.body.cell>
                                                    </x-core::table.body.row>
                                                    <x-core::table.body.row>
                                                        <x-core::table.body.cell>
                                                            {{ trans('plugins/ecommerce::order.transaction_type') }}
                                                        </x-core::table.body.cell>
                                                        <x-core::table.body.cell>
                                                            {{ trans('plugins/ecommerce::order.refund') }}</x-core::table.body.cell>
                                                    </x-core::table.body.row>
                                                    @if (trim($history->user->name))
                                                        <x-core::table.body.row>
                                                            <x-core::table.body.cell>
                                                                {{ trans('plugins/ecommerce::order.staff') }}
                                                            </x-core::table.body.cell>
                                                            <x-core::table.body.cell>
                                                                {{ $history->user->name}}
                                                            </x-core::table.body.cell>
                                                        </x-core::table.body.row>
                                                    @endif
                                                    <x-core::table.body.row>
                                                        <x-core::table.body.cell>
                                                            {{ trans('plugins/ecommerce::order.refund_date') }}
                                                        </x-core::table.body.cell>
                                                        <x-core::table.body.cell>
                                                            {{ BaseHelper::formatDateTime($history->created_at) }}
                                                        </x-core::table.body.cell>
                                                    </x-core::table.body.row>
                                                </x-core::table.body>
                                            </x-core::table>
                                        </div>
                                    @endif
                                    @if (is_plugin_active('payment') && $history->action == 'confirm_payment' && $order->payment)
                                        <div
                                            class="timeline-dropdown bg-body mt-2 rounded-2"
                                            style="display: none"
                                            id="history-line-{{ $history->id }}"
                                        >
                                            <x-core::table :striped="false" :hover="false" class="w-100">
                                                <x-core::table.body.row>
                                                    <x-core::table.body.cell>
                                                        {{ trans('plugins/ecommerce::order.order_number') }}
                                                    </x-core::table.body.cell>
                                                    <x-core::table.body.cell>
                                                        <a
                                                            href="{{ route('orders.edit', $order->id) }}"
                                                            title="{{ $order->code }}"
                                                        >
                                                            {{ $order->code }}
                                                        </a>
                                                    </x-core::table.body.cell>
                                                </x-core::table.body.row>
                                                <x-core::table.body.row>
                                                    <x-core::table.body.cell>
                                                        {{ trans('plugins/ecommerce::order.description') }}
                                                    </x-core::table.body.cell>
                                                    <x-core::table.body.cell>{!! trans('plugins/ecommerce::order.mark_payment_as_confirmed', [
                                                                'method' => $order->payment->payment_channel->label(),
                                                            ]) !!}
                                                    </x-core::table.body.cell>
                                                </x-core::table.body.row>
                                                <x-core::table.body.row>
                                                    <x-core::table.body.cell>
                                                        {{ trans('plugins/ecommerce::order.transaction_amount') }}
                                                    </x-core::table.body.cell>
                                                    <x-core::table.body.cell>
                                                        {{ format_price($order->payment->amount) }}
                                                    </x-core::table.body.cell>
                                                </x-core::table.body.row>
                                                <x-core::table.body.row>
                                                    <x-core::table.body.cell>
                                                        {{ trans('plugins/ecommerce::order.payment_gateway') }}
                                                    </x-core::table.body.cell>
                                                    <x-core::table.body.cell>
                                                        {{ $order->payment->payment_channel->label() }}
                                                    </x-core::table.body.cell>
                                                </x-core::table.body.row>
                                                <x-core::table.body.row>
                                                    <x-core::table.body.cell>
                                                        {{ trans('plugins/ecommerce::order.status') }}
                                                    </x-core::table.body.cell>
                                                    <x-core::table.body.cell>
                                                        {{ trans('plugins/ecommerce::order.successfully') }}
                                                    </x-core::table.body.cell>
                                                </x-core::table.body.row>
                                                <x-core::table.body.row>
                                                    <x-core::table.body.cell>
                                                        {{ trans('plugins/ecommerce::order.transaction_type') }}
                                                    </x-core::table.body.cell>
                                                    <x-core::table.body.cell>
                                                        {{ trans('plugins/ecommerce::order.confirm') }}
                                                    </x-core::table.body.cell>
                                                </x-core::table.body.row>
                                                @if (trim($history->user->name))
                                                    <x-core::table.body.row>
                                                        <x-core::table.body.cell>
                                                            {{ trans('plugins/ecommerce::order.staff') }}
                                                        </x-core::table.body.cell>
                                                        <x-core::table.body.cell>
                                                            {{ $history->user->name }}
                                                        </x-core::table.body.cell>
                                                    </x-core::table.body.row>
                                                @endif
                                                <x-core::table.body.row>
                                                    <x-core::table.body.cell>
                                                        {{ trans('plugins/ecommerce::order.payment_date') }}
                                                    </x-core::table.body.cell>
                                                    <x-core::table.body.cell>
                                                        {{ BaseHelper::formatDateTime($history->created_at) }}
                                                    </x-core::table.body.cell>
                                                </x-core::table.body.row>
                                            </x-core::table>
                                        </div>
                                    @endif
                                    @if ($history->action == 'send_order_confirmation_email')
                                        <x-core::button
                                            type="button"
                                            color="primary"
                                            :outlined="true"
                                            class="btn-trigger-resend-order-confirmation-modal position-absolute top-0 end-0 d-print-none"
                                            :data-action="route('orders.send-order-confirmation-email', $history->order_id)"
                                        >
                                            {{ trans('plugins/ecommerce::order.resend') }}
                                        </x-core::button>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </x-core::card.body>
                </x-core::card>
            </div>

            <div class="col-md-3">
                <x-core::card>
                    <x-core::card.header>
                        <x-core::card.title>
                            {{ trans('plugins/ecommerce::order.customer_label') }}
                        </x-core::card.title>
                    </x-core::card.header>

                    <x-core::card.body class="p-0">
                        <div class="p-3">
                            <div class="mb-3">
                                <span class="avatar avatar-lg avatar-rounded" style="background-image: url('{{ $order->user->id ? $order->user->avatar_url : $order->address->avatar_url }}')"></span>
                            </div>

                            @php
                                $userInfo = $order->user;
                            @endphp

                            @if ($userInfo->id)
                                <p class="mb-1">
                                    <x-core::icon name="ti ti-inbox" />
                                    {{ $userInfo->orders()->count() }}
                                    {{ trans('plugins/ecommerce::order.orders') }}
                                </p>
                            @endif

                            <p class="mb-1 fw-semibold">{{ $userInfo->name }}</p>

                            @if ($userInfo->email)
                                <p class="mb-1">
                                    <a href="mailto:{{ $userInfo->email }}">
                                        {{ $userInfo->email }}
                                    </a>
                                </p>
                            @endif

                            @if ($userInfo->phone)
                                <p class="mb-1">
                                    <a href="tel:{{ $userInfo->phone }}">
                                        {{ $userInfo->phone }}
                                    </a>
                                </p>
                            @endif

                            @if ($order->user->id)
                                <p class="mb-1">{{ trans('plugins/ecommerce::order.have_an_account_already') }}</p>
                            @else
                                <p class="mb-1">{{ trans('plugins/ecommerce::order.dont_have_an_account_yet') }}</p>
                            @endif
                        </div>

                        @if (
                            $order->shippingAddress->country
                            || $order->shippingAddress->state
                            || $order->shippingAddress->city
                            || $order->shippingAddress->address
                            || $order->shippingAddress->email
                            || $order->shippingAddress->phone
                        )
                            @if (EcommerceHelper::countDigitalProducts($order->products) != $order->products->count())
                                <div class="hr my-1"></div>

                                <div class="p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4>{{ trans('plugins/ecommerce::order.shipping_info') }}</h4>
                                        @if ($order->status != Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED)
                                            <a
                                                class="btn-trigger-update-shipping-address btn-action text-decoration-none"
                                                href="#"
                                                data-placement="top"
                                                data-bs-toggle="tooltip"
                                                data-bs-original-title="{{ trans('plugins/ecommerce::order.update_address') }}"
                                            >
                                                <x-core::icon name="ti ti-pencil" />
                                            </a>
                                        @endif
                                    </div>

                                    <dl class="shipping-address-info mb-0">
                                        @include(
                                            'plugins/ecommerce::orders.shipping-address.detail',
                                            ['address' => $order->shippingAddress]
                                        )
                                    </dl>
                                </div>
                            @endif

                            @if (
                                EcommerceHelper::isBillingAddressEnabled()
                                && $order->billingAddress->id
                                && $order->billingAddress->id != $order->shippingAddress->id
                            )
                                <div class="p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4>{{ trans('plugins/ecommerce::order.billing_address') }}</h4>
                                    </div>

                                    <dl class="shipping-address-info mb-0">
                                        @include(
                                            'plugins/ecommerce::orders.shipping-address.detail',
                                            ['address' => $order->billingAddress]
                                        )
                                    </dl>
                                </div>
                            @endif
                        @endif

                        @if ($order->taxInformation)
                            <div class="hr my-1"></div>

                            <div class="p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4>{{ trans('plugins/ecommerce::order.tax_info.name') }}</h4>
                                    @if ($order->status !== Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED)
                                        <div class="flexbox-auto-content-right text-end">
                                            <a
                                                class="btn-trigger-update-tax-information"
                                                href="#"
                                                data-placement="top"
                                                data-bs-toggle="tooltip"
                                                data-bs-original-title="{{ trans('plugins/ecommerce::order.tax_info.update') }}"
                                            >
                                                <x-core::icon name="ti ti-pencil" />
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <dl class="shipping-address-info mb-0">
                                    @include('plugins/ecommerce::orders.tax-information.detail', [
                                        'tax' => $order->taxInformation,
                                    ])
                                </dl>
                            </div>
                        @endif

                        @if ($order->referral->exists())
                            <div class="hr my-1"></div>

                            <div class="p-3">
                                <h4>{{ trans('plugins/ecommerce::order.referral') }}</h4>

                                <dl class="mb-0">
                                    @foreach (['ip', 'landing_domain', 'landing_page', 'landing_params', 'referral', 'gclid', 'fclid', 'utm_source', 'utm_campaign', 'utm_medium', 'utm_term', 'utm_content', 'referrer_url', 'referrer_domain'] as $field)
                                        @if ($order->referral->{$field})
                                            <dt>{{ trans('plugins/ecommerce::order.referral_data.' . $field) }}</dt>
                                            <dd>{{ $order->referral->{$field} }}</dd>
                                        @endif
                                    @endforeach
                                </dl>
                            </div>
                        @endif

                        @if (is_plugin_active('marketplace') && $order->store->name)
                            <div class="hr my-1"></div>

                            <div class="p-3">
                                <h4 class="mb-2">{{ trans('plugins/marketplace::store.store') }}</h4>
                                <a href="{{ $order->store->url }}" target="_blank">{{ $order->store->name }}</a>
                            </div>
                        @endif
                    </x-core::card.body>

                    <x-core::card.footer>
                        <div class="btn-list">
                            <x-core::button
                                tag="a"
                                :href="route('orders.reorder', ['order_id' => $order->id])"
                            >
                                {{ trans('plugins/ecommerce::order.reorder') }}
                            </x-core::button>
                            @if ($order->canBeCanceledByAdmin())
                                <x-core::button
                                    type="button"
                                    :data-target="route('orders.cancel', $order->id)"
                                    class="btn-trigger-cancel-order"
                                >
                                    {{ trans('plugins/ecommerce::order.cancel') }}
                                </x-core::button>
                            @endif
                        </div>
                    </x-core::card.footer>
                </x-core::card>
            </div>
        </div>
    </div>
@endsection

@pushif($order->status != Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED, 'footer')
    <x-core::modal.action
        id="resend-order-confirmation-email-modal"
        :title="trans('plugins/ecommerce::order.resend_order_confirmation')"
        :description="trans('plugins/ecommerce::order.resend_order_confirmation_description', [
            'email' => $order->user->email ?: $order->address->email,
        ])"
        :submit-button-attrs="['id' => 'confirm-resend-confirmation-email-button']"
        :submit-button-label="trans('plugins/ecommerce::order.send')"
    />

    <x-core::modal.action
        id="cancel-shipment-modal"
        type="warning"
        :title="trans('plugins/ecommerce::order.cancel_shipping_confirmation')"
        :description="trans('plugins/ecommerce::order.cancel_shipping_confirmation_description')"
        :submit-button-attrs="['id' => 'confirm-cancel-shipment-button']"
        :submit-button-label="trans('plugins/ecommerce::order.confirm')"
    />

    <x-core::modal
        id="update-shipping-address-modal"
        :title="trans('plugins/ecommerce::order.update_address')"
        button-id="confirm-update-shipping-address-button"
        :button-label="trans('plugins/ecommerce::order.update')"
        size="md"
    >
        @include('plugins/ecommerce::orders.shipping-address.form', [
            'address' => $order->address,
            'orderId' => $order->id,
            'url' => route('orders.update-shipping-address', $order->address->id ?? 0),
        ])
    </x-core::modal>

    @if ($order->taxInformation)
        <x-core::modal
            id="update-tax-information-modal"
            :title="trans('plugins/ecommerce::order.tax_info.update')"
            button-id="confirm-update-tax-information-button"
            :button-label="trans('plugins/ecommerce::order.update')"
            size="md"
        >
            @include('plugins/ecommerce::orders.tax-information.form', [
                'tax' => $order->taxInformation,
                'orderId' => $order->id,
            ])
        </x-core::modal>
    @endif

    <x-core::modal.action
        id="cancel-order-modal"
        type="warning"
        :title="trans('plugins/ecommerce::order.cancel_order_confirmation')"
        :description="trans('plugins/ecommerce::order.cancel_order_confirmation_description')"
        :submit-button-attrs="['id' => 'confirm-cancel-order-button']"
        :submit-button-label="trans('plugins/ecommerce::order.cancel_order')"
    />

    @if (is_plugin_active('payment'))
        <x-core::modal.action
            id="confirm-payment-modal"
            type="info"
            :title="trans('plugins/ecommerce::order.confirm_payment')"
            :description="trans('plugins/ecommerce::order.confirm_payment_confirmation_description', [
                'method' => $order->payment->payment_channel->label(),
            ])"
            :submit-button-attrs="['id' => 'confirm-payment-order-button']"
            :submit-button-label="trans('plugins/ecommerce::order.confirm_payment')"
        />

        <x-core::modal
            id="confirm-refund-modal"
            :title="trans('plugins/ecommerce::order.refund')"
            button-id="confirm-refund-payment-button"
            size="lg"
        >
            <x-slot:button-label>
                {{ trans('plugins/ecommerce::order.confirm_payment') }}
                <span class="refund-amount-text ms-1">{{ format_price($order->payment->amount - $order->payment->refunded_amount) }}</span>
            </x-slot:button-label>
            @include('plugins/ecommerce::orders.refund.modal', [
                'order' => $order,
                'url' => route('orders.refund', $order->id),
            ])
        </x-core::modal>
    @endif
    @if ($order->shipment && $order->shipment->id)
        @include('plugins/ecommerce::shipments.partials.update-status-modal', [
            'shipment' => $order->shipment,
        ])
    @endif
@endpushif
