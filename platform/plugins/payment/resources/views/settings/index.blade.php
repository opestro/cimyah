@php
    use Botble\Payment\Enums\PaymentMethodEnum;
    use Botble\Payment\Models\Payment;
@endphp

@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    {!! $form->renderForm() !!}

    @php
        do_action(BASE_ACTION_META_BOXES, 'top', new Payment);
    @endphp

    <div class="my-5 d-block d-md-flex">
        <div class="col-12 col-md-3"></div>
        <div class="col-12 col-md-9">
            {!! apply_filters(PAYMENT_METHODS_SETTINGS_PAGE, null) !!}

            <x-core::card class="mb-3">
                <x-core::table :hover="false" :striped="false">
                    @php
                        $codStatus = get_payment_setting('status', PaymentMethodEnum::COD);
                    @endphp
                    <x-core::table.body>
                        <x-core::table.body.row>
                            <x-core::table.body.cell class="border-end">
                                <x-core::icon name="ti ti-wallet" />
                            </x-core::table.body.cell>
                            <x-core::table.body.cell style="width: 20%">
                                {{ trans('plugins/payment::payment.payment_methods') }}
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                <p class="mb-0">{{ trans('plugins/payment::payment.payment_methods_instruction') }}</p>
                            </x-core::table.body.cell>
                        </x-core::table.body.row>
                        <x-core::table.body.row>
                            <x-core::table.body.cell colspan="3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="payment-name-label-group">
                                            @if($codStatus)
                                                {{ trans('plugins/payment::payment.use') }}
                                            @endif
                                            <span class="method-name-label">{{ get_payment_setting('name', PaymentMethodEnum::COD, PaymentMethodEnum::COD()->label()) }}</span>
                                        </div>
                                    </div>

                                    <x-core::button @class(['toggle-payment-item edit-payment-item-btn-trigger', 'hidden' => !$codStatus])>
                                        {{ trans('plugins/payment::payment.edit') }}
                                    </x-core::button>
                                    <x-core::button @class(['toggle-payment-item save-payment-item-btn-trigger', 'hidden' => $codStatus])>
                                        {{ trans('plugins/payment::payment.settings') }}
                                    </x-core::button>
                                </div>
                            </x-core::table.body.cell>
                        </x-core::table.body.row>
                        <x-core::table.body.row class="payment-content-item hidden">
                            <x-core::table.body.cell colspan="3">
                                <x-core::form>
                                    {!! $codForm->renderForm() !!}

                                    <div class="btn-list justify-content-end">
                                        <x-core::button
                                            type="button"
                                            @class(['disable-payment-item', 'hidden' => !$codStatus])
                                        >
                                            {{ trans('plugins/payment::payment.deactivate') }}
                                        </x-core::button>

                                        <x-core::button
                                            @class(['save-payment-item btn-text-trigger-save', 'hidden' => $codStatus])
                                            type="button"
                                            color="info"
                                        >
                                            {{ trans('plugins/payment::payment.activate') }}
                                        </x-core::button>
                                        <x-core::button
                                            type="button"
                                            color="info"
                                            @class(['save-payment-item btn-text-trigger-update', 'hidden' => !$codStatus])
                                        >
                                            {{ trans('plugins/payment::payment.update') }}
                                        </x-core::button>
                                    </div>
                                </x-core::form>
                            </x-core::table.body.cell>
                        </x-core::table.body.row>
                    </x-core::table.body>

                    @php
                        $bankTransferStatus = setting('payment_bank_transfer_status');
                    @endphp
                    <x-core::table.body>
                        <x-core::table.body.row>
                            <x-core::table.body.cell colspan="3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="payment-name-label-group">
                                            @if($bankTransferStatus)
                                                {{ trans('plugins/payment::payment.use') }}
                                            @endif
                                            <span class="method-name-label">{{ get_payment_setting('name', 'bank_transfer', PaymentMethodEnum::BANK_TRANSFER()->label()) }}</span>
                                        </div>
                                    </div>

                                    <x-core::button @class(['toggle-payment-item edit-payment-item-btn-trigger', 'hidden' => !$bankTransferStatus])>
                                        {{ trans('plugins/payment::payment.edit') }}
                                    </x-core::button>
                                    <x-core::button @class(['toggle-payment-item save-payment-item-btn-trigger', 'hidden' => $bankTransferStatus])>
                                        {{ trans('plugins/payment::payment.settings') }}
                                    </x-core::button>
                                </div>
                            </x-core::table.body.cell>
                        </x-core::table.body.row>
                        <x-core::table.body.row class="payment-content-item hidden">
                            <x-core::table.body.cell colspan="3">
                                <x-core::form>
                                    {!! $bankTransferForm->renderForm() !!}

                                    <div class="btn-list justify-content-end">
                                        <x-core::button
                                            type="button"
                                            @class(['disable-payment-item', 'hidden' => !$bankTransferStatus])
                                        >
                                            {{ trans('plugins/payment::payment.deactivate') }}
                                        </x-core::button>

                                        <x-core::button
                                            @class(['save-payment-item btn-text-trigger-save', 'hidden' => $bankTransferStatus])
                                            type="button"
                                            color="info"
                                        >
                                            {{ trans('plugins/payment::payment.activate') }}
                                        </x-core::button>
                                        <x-core::button
                                            type="button"
                                            color="info"
                                            @class(['save-payment-item btn-text-trigger-update', 'hidden' => !$bankTransferStatus])
                                        >
                                            {{ trans('plugins/payment::payment.update') }}
                                        </x-core::button>
                                    </div>
                                </x-core::form>
                            </x-core::table.body.cell>
                        </x-core::table.body.row>
                    </x-core::table.body>
                </x-core::table>
            </x-core::card>
        </div>
    </div>

    @php
        do_action(BASE_ACTION_META_BOXES, 'main', new Payment);
    @endphp

    <div class="row">
        <div class="col-md-9 offset-col-md-3">
            @php
                do_action(BASE_ACTION_META_BOXES, 'advanced', new Payment);
            @endphp
        </div>
    </div>

    {!! apply_filters('payment_method_after_settings', null) !!}
@endsection

@push('footer')
    <x-core::modal.action
        id="confirm-disable-payment-method-modal"
        :title="trans('plugins/payment::payment.deactivate_payment_method')"
        :description="trans('plugins/payment::payment.deactivate_payment_method_description')"
        :submit-button-attrs="['id' => 'confirm-disable-payment-method-button']"
        :submit-button-label="trans('plugins/payment::payment.agree')"
    />
@endpush
