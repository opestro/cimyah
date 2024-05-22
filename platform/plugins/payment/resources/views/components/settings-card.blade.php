@props([
    'id',
    'name',
    'logo',
    'url' => null,
    'description' => null,
    'status' => get_payment_setting('status', $id),
    'defaultDescriptionValue' => __('Payment with :paymentType', ['paymentType' => $name]),
])

@php
    $id = $id ?? Str::slug($name);
@endphp

<x-core::card class="mb-3">
    <x-core::table :hover="false" :striped="false">
        <x-core::table.body>
            <x-core::table.body.row>
                <x-core::table.body.cell class="border-end" style="width: 5%">
                    <x-core::icon name="ti ti-wallet" />
                </x-core::table.body.cell>
                <x-core::table.body.cell style="width: 20%">
                    <img src="{{ $logo }}" alt="{{ $name }}" style="width: 8rem">
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    @if($url)
                        <a href="{{ $url }}" target="_blank">{{ $name }}</a>
                    @else
                        {{ $name }}
                    @endif
                    @if($description)
                        <p class="mb-0">{{ $description }}</p>
                    @endif
                </x-core::table.body.cell>
            </x-core::table.body.row>
            <x-core::table.body.row>
                <x-core::table.body.cell colspan="3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div @class(['payment-name-label-group', 'hidden' => !$status])>
                                {{ trans('plugins/payment::payment.use') }}
                                <span class="method-name-label">{{ get_payment_setting_key('name', $id) }}</span>
                            </div>
                        </div>

                        <x-core::button @class(['toggle-payment-item edit-payment-item-btn-trigger', 'hidden' => !$status])>
                            {{ trans('plugins/payment::payment.edit') }}
                        </x-core::button>
                        <x-core::button @class(['toggle-payment-item save-payment-item-btn-trigger', 'hidden' => $status])>
                            {{ trans('plugins/payment::payment.settings') }}
                        </x-core::button>
                    </div>
                </x-core::table.body.cell>
            </x-core::table.body.row>
            <x-core::table.body.row class="payment-content-item hidden">
                <x-core::table.body.cell colspan="3">
                    <x-core::form>
                        <input type="hidden" name="type" value="{{ $id }}" class="payment_type" />

                        <div class="row">
                            <div class="col-md-6">
                                <x-core::form.label>
                                    {{ trans('plugins/payment::payment.configuration_instruction', ['name' => $name]) }}
                                </x-core::form.label>

                                <p class="mb-2">{{ trans('plugins/payment::payment.configuration_requirement', ['name' => $name]) }}:</p>

                                {{ $instructions }}
                            </div>

                            <div class="col-md-6">
                                <x-core::form.text-input
                                    :label="trans('plugins/payment::payment.method_name')"
                                    :name="get_payment_setting_key('name', $id)"
                                    data-counter="400"
                                    :value="get_payment_setting('name', $id, trans('plugins/payment::payment.pay_online_via', ['name' => $name]))"
                                />

                                <x-core::form.textarea
                                    :label="trans('core/base::forms.description')"
                                    :name="get_payment_setting_key('description', $id)"
                                    :value="get_payment_setting('description', $id, $defaultDescriptionValue)"
                                />

                                <x-core::form-group>
                                    <x-core::form.label for="{{ $logoKey = get_payment_setting_key('logo', $id) }}">
                                        {{ trans('plugins/payment::payment.method_logo') }}
                                    </x-core::form.label>
                                    {{ Form::mediaImage($logoKey, get_payment_setting('logo', $id)) }}
                                </x-core::form-group>

                                <x-core::form.fieldset>
                                    <legend class="fs-4 fw-semibold mb-3">
                                        {{ trans('plugins/payment::payment.please_provide_information') }}
                                        <a href="{{ $url }}" target="_blank">{{ $name }}</a>:
                                    </legend>

                                    {{ $fields }}

                                    {!! apply_filters(PAYMENT_METHOD_SETTINGS_CONTENT, null, $id) !!}
                                </x-core::form.fieldset>
                            </div>
                        </div>

                        <div class="btn-list justify-content-end">
                            <x-core::button
                                type="button"
                                @class(['disable-payment-item', 'hidden' => !$status])
                            >
                                {{ trans('plugins/payment::payment.deactivate') }}
                            </x-core::button>

                            <x-core::button
                                @class(['save-payment-item btn-text-trigger-save', 'hidden' => $status])
                                type="button"
                                color="info"
                            >
                                {{ trans('plugins/payment::payment.activate') }}
                            </x-core::button>
                            <x-core::button
                                type="button"
                                color="info"
                                @class(['save-payment-item btn-text-trigger-update', 'hidden' => !$status])
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
