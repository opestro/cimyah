@extends($layout ?? BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core::card>
        <x-core::card.header>
            <x-core::tab class="card-header-tabs">
                <x-core::tab.item
                    id="information-tab"
                    :label="trans('plugins/marketplace::store.store')"
                    :is-active="true"
                />
                @if($store && $store->customer->is_vendor)
                    @include('plugins/marketplace::customers.tax-info-tab')
                    @include('plugins/marketplace::customers.payout-info-tab')
                @endif
                {!! apply_filters(BASE_FILTER_REGISTER_CONTENT_TABS, null, $store) !!}
                {!! apply_filters('marketplace_vendor_settings_register_content_tabs', null, $store) !!}
            </x-core::tab>
        </x-core::card.header>

        <x-core::card.body>
            <x-core::tab.content>
                <x-core::tab.pane id="information-tab" :is-active="true">
                    {!! $form !!}
                </x-core::tab.pane>
                @if($store && $store->customer->is_vendor)
                    @include('plugins/marketplace::customers.tax-form', ['model' => $store->customer])
                    @include('plugins/marketplace::customers.payout-form', ['model' => $store->customer])
                @endif
                {!! apply_filters(BASE_FILTER_REGISTER_CONTENT_TAB_INSIDE, null, $store) !!}
                {!! apply_filters('marketplace_vendor_settings_register_content_tab_inside', null, $store) !!}
            </x-core::tab.content>
        </x-core::card.body>
    </x-core::card>
@stop
