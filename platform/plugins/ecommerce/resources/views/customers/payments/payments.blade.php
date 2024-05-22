<x-core::table>
    <x-core::table.header>
        <x-core::table.header.cell>
            #
        </x-core::table.header.cell>
        <x-core::table.header.cell>
            {{ trans('plugins/ecommerce::payment.order') }}
        </x-core::table.header.cell>
        <x-core::table.header.cell>
            {{ trans('plugins/ecommerce::payment.charge_id') }}
        </x-core::table.header.cell>
        <x-core::table.header.cell>
            {{ trans('plugins/ecommerce::payment.amount') }}
        </x-core::table.header.cell>
        <x-core::table.header.cell>
            {{ trans('plugins/ecommerce::payment.payment_method') }}
        </x-core::table.header.cell>
        <x-core::table.header.cell>
            {{ trans('plugins/ecommerce::payment.status') }}
        </x-core::table.header.cell>
        @if (auth()->user()->hasPermission('payment.show'))
            <x-core::table.header.cell>
                {{ trans('plugins/ecommerce::payment.action') }}
            </x-core::table.header.cell>
        @endif
    </x-core::table.header>

    <x-core::table.body>
        @forelse ($payments as $payment)
            <x-core::table.body.row>
                <x-core::table.body.cell>
                    {{ $loop->iteration }}
                </x-core::table.body.cell>
                <x-core::table.body.cell class="text-start">
                    @if ($payment->order->id)
                        @if (auth()->user()->hasPermission('orders.edit'))
                            <a href="{{ route('orders.edit', $payment->order->id) }}" target="_blank">
                                {{ $payment->order->code }} <i class="fa fa-external-link"></i>
                            </a>
                        @else
                            {{ $payment->order->code }}
                        @endif
                    @else
                        &mdash;
                    @endif
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ $payment->charge_id }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ $payment->amount }} {{ $payment->currency }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    @if ($paymentMethod = $payment->payment_channel->label())
                        {{ $paymentMethod }}
                    @else
                        &mdash;
                    @endif
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {!! BaseHelper::clean($payment->status->toHtml()) !!}
                </x-core::table.body.cell>
                @if (auth()->user()->hasPermission('payment.show'))
                    <x-core::table.body.cell>
                            <x-core::button
                                tag="a"
                                :href="route('payment.show', $payment->id)"
                                target="_blank"
                                size="sm"
                                :tooltip="trans('core/base::forms.view_new_tab')"
                                icon="ti ti-external-link"
                                :icon-only="true"
                            />
                    </x-core::table.body.cell>
                @endif
            </x-core::table.body.row>
        @empty
            <x-core::table.body.row class="text-center text-muted">
                <x-core::table.body.cell colspan="7">
                    {{ trans('plugins/ecommerce::payment.no_data') }}
                </x-core::table.body.cell>
            </x-core::table.body.row>
        @endforelse
    </x-core::table.body>
</x-core::table>

@push('footer')
    <x-core::modal.action
        id="edit-payment-modal"
        type="info"
        :title="trans('plugins/ecommerce::payment.edit_payment')"
        :submit-button-attrs="['id' => 'confirm-edit-payment-button']"
        :submit-button-label="trans('plugins/ecommerce::payment.save')"
    >
        <x-slot:description>
            {!! BaseHelper::clean(trans('plugins/ecommerce::customer.verify_email.confirm_description')) !!}
        </x-slot:description>
    </x-core::modal.action>
@endpush
