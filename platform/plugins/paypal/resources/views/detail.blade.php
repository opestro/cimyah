@if ($payment)
    @php
        $result = $payment->result;
        $purchaseUnits = $result->purchase_units;
        $purchaseUnit = Arr::get($purchaseUnits, 0);
        $payer = $result->payer;
        $shipping = $purchaseUnit->shipping;
    @endphp

    <div class="my-3">
        <div
            class="alert alert-success"
            role="alert"
        >
            <p class="mb-2">{{ trans('plugins/payment::payment.payment_id') }}: <strong>{{ $result->id }}</strong></p>

            <p class="mb-2">
                {{ trans('plugins/payment::payment.details') }}:
                <strong>
                    @foreach ($purchaseUnits as $purchase)
                        {{ $purchase->amount->value }} {{ $purchase->amount->currency_code }} @if (!empty($purchase->description))
                            ({{ $purchase->description }})
                        @endif
                    @endforeach
                </strong>
            </p>

            <p class="mb-2">{{ trans('plugins/payment::payment.payer_name') }}
                : {{ $payer->name->given_name }} {{ $payer->name->surname }}</p>
            <p class="mb-2">{{ trans('plugins/payment::payment.email') }}: {{ $payer->email_address }}</p>
            @if (!empty($payer->phone) && $payer->phone->phone_number && $payer->phone->phone_number->national_number)
                <p class="mb-2">{{ trans('plugins/payment::payment.phone') }}:
                    {{ $payer->phone->phone_number->national_number }}</p>
            @endif
            <p class="mb-2">{{ trans('plugins/payment::payment.country') }}: {{ $payer->address->country_code }}</p>
            <p class="mb-0">
                {{ trans('plugins/payment::payment.shipping_address') }}:
                {{ implode(', ', (array) $shipping->name) }}, {{ implode(', ', (array) $shipping->address)  }}
            </p>
        </div>

        @php
            $refunds = [];
            $payments = $purchaseUnit->payments;
            if ($payments && !empty($payments->refunds)) {
                $refunds = $payments->refunds;
            }
        @endphp
        @if ($refunds)
            <br />
            <x-core::datagrid class="mb-2">
                <x-core::datagrid.item>
                    <x-slot:title>{{ trans('plugins/payment::payment.refunds.title') . ' (' . count((array) $refunds) . ')' }}</x-slot:title>
                </x-core::datagrid.item>
            </x-core::datagrid>

            @foreach ($refunds as $item)
                <div
                    class="alert alert-warning"
                    role="alert"
                >
                    <p>{{ trans('plugins/payment::payment.refunds.id') }}: {{ $item->id }}</p>
                    <p>{{ trans('plugins/payment::payment.amount') }}: {{ $item->amount->value }}
                        {{ $item->amount->currency_code }}</p>
                    <p>{{ trans('plugins/payment::payment.refunds.status') }}: {{ $item->status }}</p>
                    <p>{{ trans('plugins/payment::payment.refunds.breakdowns') }}: </p>
                    <div class="ms-4">
                        @foreach ($item->seller_payable_breakdown as $k => $breakdown)
                            @if (is_object($breakdown))
                                <p>{{ trans('plugins/payment::payment.refunds.' . $k) }}: {{ $breakdown->value }}
                                    {{ $breakdown->currency_code }}</p>
                            @endif
                        @endforeach
                    </div>
                    <p>{{ trans('plugins/payment::payment.refunds.create_time') }}:
                        {{ BaseHelper::formatDate($item->create_time) }}</p>
                    <p>{{ trans('plugins/payment::payment.refunds.update_time') }}:
                        {{ BaseHelper::formatDate($item->update_time) }}</p>
                </div>
                <br />
            @endforeach
        @endif

        @include('plugins/payment::partials.view-payment-source')
    </div>
@endif
