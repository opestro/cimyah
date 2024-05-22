@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row row-cards">
        <div class="col-md-3">
            <x-core::card>
                <x-core::card.header>
                    <x-core::card.title>
                        {{ trans('plugins/marketplace::revenue.store_information') }}
                    </x-core::card.title>
                </x-core::card.header>

                <x-core::card.body class="p-0">
                    <div class="text-center p-3">
                        <div class="mb-2">
                            <img
                                src="{{ RvMedia::getImageUrl($store->logo, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                alt="{{ $store->name }}"
                                class="avatar avatar-rounded avatar-xl"
                            />
                        </div>

                        <a href="{{ $store->url }}" target="_blank">
                            {{ $store->name }}
                            <x-core::icon name="ti ti-external-link" />
                        </a>
                    </div>

                    <div class="hr my-2"></div>

                    <div class="p-3">
                        <dl class="row">
                            <dt class="col">{{ trans('plugins/marketplace::revenue.vendor_name') }}</dt>
                            <dd class="col-auto">
                                <a href="{{ route('customers.edit', $customer->id) }}" target="_blank">
                                    {{ $customer->name }}
                                    <x-core::icon name="ti ti-external-link" />
                                </a>
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col">{{ trans('plugins/marketplace::revenue.balance') }}</dt>
                            <dd class="col-auto">
                            <span class="vendor-balance">
                                {{ format_price($customer->balance) }}
                                <a
                                    data-bs-toggle="modal"
                                    data-bs-target="#update-balance-modal"
                                    href="javascript:void(0)"
                                    class="text-decoration-none"
                                >
                                    <x-core::icon name="ti ti-edit" />
                                </a>
                            </span>
                            </dd>
                        </dl>

                        <dl class="row">
                            <dt class="col">{{ trans('plugins/marketplace::revenue.products') }}</dt>
                            <dd class="col-auto">{{ number_format($store->products()->count()) }}</dd>
                        </dl>
                    </div>
                </x-core::card.body>
            </x-core::card>
        </div>

        <div class="col-md-9">
            <x-core::card>
                <x-core::card.header>
                    <x-core::card.title>
                        {{ trans('plugins/marketplace::revenue.statements') }}
                    </x-core::card.title>
                    <x-core::card.actions>
                        <a
                            data-bs-toggle="modal"
                            data-bs-target="#update-balance-modal"
                            href="javascript:void(0)"
                            class="small"
                        >
                            <x-core::icon name="ti ti-edit" />
                            {{ trans('plugins/marketplace::revenue.update_balance') }}
                        </a>
                    </x-core::card.actions>
                </x-core::card.header>

                {!! $table->renderTable() !!}
            </x-core::card>
        </div>
    </div>
@endsection

@push('footer')
    <x-core::modal
        id="update-balance-modal"
        :title="trans('plugins/marketplace::revenue.update_balance_title')"
        button-id="confirm-update-amount-button"
        :button-label="trans('core/base::tables.submit')"
        size="md"
    >
        <x-core::form :url="route('marketplace.store.revenue.create', $store->id)">
            <x-core::form.text-input
                :label="trans('plugins/marketplace::revenue.forms.amount')"
                name="amount"
                type="number"
                :placeholder="trans('plugins/marketplace::revenue.forms.amount_placeholder')"
                :group-flat="true"
            >
                <x-slot:prepend>
                    <span class="input-group-text">{{ get_application_currency()->symbol }}</span>
                </x-slot:prepend>
            </x-core::form.text-input>

            <x-core::form.radio-list
                :label="trans('plugins/marketplace::revenue.forms.type')"
                name="type"
                :options="Botble\Marketplace\Enums\RevenueTypeEnum::adjustLabels()"
                :value="Botble\Marketplace\Enums\RevenueTypeEnum::ADD_AMOUNT"
            />

            <x-core::form.textarea
                :label="trans('core/base::forms.description')"
                name="description"
                :placeholder="trans('plugins/marketplace::revenue.forms.description_placeholder')"
                rows="3"
            />
        </x-core::form>
    </x-core::modal>
@endpush
