@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    @php
        do_action(BASE_ACTION_TOP_FORM_CONTENT_NOTIFICATION, request(), $payment);
    @endphp

    <x-core::form :url="route('payment.update', $payment->id)" method="post">
        @method('PUT')

        <div class="row">
            <div class="col-md-9">
                <x-core::card>
                    <x-core::card.header>
                        <h4 class="card-title">{{ trans('plugins/payment::payment.information') }}</h4>

                        <div class="card-actions">
                            {!! apply_filters('payment-transaction-card-actions', null, $payment) !!}
                        </div>
                    </x-core::card.header>

                    <x-core::card.body>
                        <x-core::datagrid>
                            @if($payment->charge_id)
                                <x-core::datagrid.item>
                                    <x-slot:title>{{ trans('plugins/payment::payment.charge_id') }}</x-slot:title>
                                    {{ $payment->charge_id }}
                                </x-core::datagrid.item>
                            @endif

                            @if ($payment->customer_id && $payment->customer && $payment->customer_type && class_exists($payment->customer_type))
                                <x-core::datagrid.item>
                                    <x-slot:title>{{ trans('plugins/payment::payment.payer_name') }}</x-slot:title>
                                    <div class="d-flex align-items-center">
                                        @if($payment->customer->avatar_url)
                                            <span class="avatar avatar-xs me-2 rounded" style="background-image: url({{ $payment->customer->avatar_url }})"></span>
                                        @endif
                                        {{ $payment->customer->name }}
                                    </div>
                                </x-core::datagrid.item>

                                <x-core::datagrid.item>
                                    <x-slot:title>{{ trans('plugins/payment::payment.email') }}</x-slot:title>
                                    {{ $payment->customer->email }}
                                </x-core::datagrid.item>

                                @if ($payment->customer->phone)
                                    <x-core::datagrid.item>
                                        <x-slot:title>{{ trans('plugins/payment::payment.phone') }}</x-slot:title>
                                        {{ $payment->customer->phone }}
                                    </x-core::datagrid.item>
                                @endif
                            @endif

                            <x-core::datagrid.item>
                                <x-slot:title>{{ trans('plugins/payment::payment.payment_channel') }}</x-slot:title>
                                {{ $payment->payment_channel->label() }}
                            </x-core::datagrid.item>

                            <x-core::datagrid.item>
                                <x-slot:title>{{ trans('plugins/payment::payment.total') }}</x-slot:title>
                                {{ $payment->amount }} {{ $payment->currency }}
                            </x-core::datagrid.item>

                            <x-core::datagrid.item>
                                <x-slot:title>{{ trans('plugins/payment::payment.created_at') }}</x-slot:title>
                                {{ BaseHelper::formatDateTime($payment->created_at) }}
                            </x-core::datagrid.item>

                            <x-core::datagrid.item>
                                <x-slot:title>{{ trans('plugins/payment::payment.status') }}</x-slot:title>
                                {!! BaseHelper::clean($payment->status->toHtml()) !!}
                            </x-core::datagrid.item>
                        </x-core::datagrid>

                        {!! $detail !!}
                    </x-core::card.body>
                </x-core::card>

                @php
                    do_action(BASE_ACTION_META_BOXES, 'advanced', $payment);
                @endphp
            </div>

            <div class="col-md-3">
                @include('core/base::forms.partials.form-actions', [
                    'title' => trans('plugins/payment::payment.action'),
                ])

                <x-core::card class="meta-boxes mt-3">
                    <x-core::card.header>
                        <h4 class="card-title">
                            <label class="form-label required" for="status">
                                {{ trans('core/base::tables.status') }}
                            </label>
                        </h4>
                    </x-core::card.header>

                    <x-core::card.body>
                        {!! Form::customSelect('status', $paymentStatuses, $payment->status) !!}
                    </x-core::card.body>
                </x-core::card>

                @php
                    do_action(BASE_ACTION_META_BOXES, 'side', $payment);
                @endphp
            </div>
        </div>
    </x-core::form>
@endsection
