@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core::alert
        type="warning"
    >
        {!! BaseHelper::clean(
            trans('plugins/marketplace::unverified-vendor.vendor_approval_notification', [
                'approve_link' => Html::link(
                    route('marketplace.unverified-vendors.approve-vendor', $vendor->id),
                    trans('plugins/marketplace::store.approve_here'),
                    ['class' => 'approve-vendor-for-selling-button'],
                ),
            ]),
        ) !!}
    </x-core::alert>

    <div class="row">
        <div class="col-md-3">
            <x-core::card>
                <x-core::card.header>
                    <x-core::card.title>
                        {{ trans('plugins/marketplace::store.information') }}
                    </x-core::card.title>
                </x-core::card.header>

                <x-core::card.body class="p-0">
                    <div class="p-3 text-center">
                        <div class="mb-2">
                            <img
                                src="{{ RvMedia::getImageUrl($vendor->store->logo, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                alt="{{ $vendor->store->name }}"
                                class="avatar avatar-rounded avatar-xl"
                            />
                        </div>

                        @if ($vendor->store->id)
                            <a href="{{ route('marketplace.store.edit', $vendor->store->id) }}" target="_blank">
                                {{ $vendor->store->name }}
                                <x-core::icon name="ti ti-external-link" />
                            </a>
                        @endif
                    </div>

                    @if($vendor->store->phone)
                        <div class="hr my-3"></div>

                        <dl class="row p-3 pt-0">
                            <dt class="col">{{ trans('plugins/marketplace::store.store_phone') }}</dt>
                            <dd class="col-auto">{{ $vendor->store->phone }}</dd>
                        </dl>
                    @endif
                </x-core::card.body>
            </x-core::card>
        </div>
        <div class="col-md-9">
            <x-core::card>
                <x-core::card.header>
                    {{ trans('plugins/marketplace::store.vendor_information') }}
                </x-core::card.header>

                <x-core::card.body>
                    <x-core::datagrid>
                        <x-core::datagrid.item>
                            <x-slot:title>
                                {{ trans('plugins/marketplace::store.vendor_name') }}
                            </x-slot:title>

                            <a href="{{ route('customers.edit', $vendor->id) }}" target="_blank">
                                {{ $vendor->name }}
                                <x-core::icon name="ti ti-external-link" />
                            </a>
                        </x-core::datagrid.item>

                        <x-core::datagrid.item>
                            <x-slot:title>
                                {{ trans('plugins/marketplace::unverified-vendor.forms.email') }}
                            </x-slot:title>
                            {{ $vendor->email }}
                        </x-core::datagrid.item>

                        @if ($vendor->phone)
                            <x-core::datagrid.item>
                                <x-slot:title>
                                    {{ trans('plugins/marketplace::unverified-vendor.forms.vendor_phone') }}
                                </x-slot:title>
                                {{ $vendor->phone }}
                            </x-core::datagrid.item>
                        @endif

                        <x-core::datagrid.item>
                            <x-slot:title>
                                {{ trans('plugins/marketplace::unverified-vendor.forms.registered_at') }}
                            </x-slot:title>
                            {{ BaseHelper::formatDateTime($vendor->created_at) }}
                        </x-core::datagrid.item>
                    </x-core::datagrid>
                </x-core::card.body>
            </x-core::card>
        </div>
    </div>
@endsection

@push('footer')
    <x-core::modal.action
        id="approve-vendor-for-selling-modal"
        type="warning"
        :title="trans('plugins/marketplace::unverified-vendor.approve_vendor_confirmation')"
        :description="trans('plugins/marketplace::unverified-vendor.approve_vendor_confirmation_description', [
            'vendor' => $vendor->name,
        ])"
        :submit-button-attrs="['id' => 'confirm-approve-vendor-for-selling-button']"
        :submit-button-label="trans('plugins/marketplace::unverified-vendor.approve')"
    />
@endpush
