@extends(MarketplaceHelper::viewPath('vendor-dashboard.layouts.master'))

@section('content')
    @if ($totalProducts)
        <div class="text-end mb-5">
            <x-core::button
                type="button"
                color="primary"
                :outlined="true"
                class="date-range-picker"
                :data-format-value="trans('plugins/ecommerce::reports.date_range_format_value', ['from' => '__from__', 'to' => '__to__'])"
                :data-format="Str::upper(config('core.base.general.date_format.js.date'))"
                :data-href="route('marketplace.vendor.dashboard')"
                :data-start-date="$data['startDate']"
                :data-end-date="$data['endDate']"
                icon="ti ti-calendar"
            >
                {{ trans('plugins/ecommerce::reports.date_range_format_value', [
                    'from' => BaseHelper::formatDate($data['startDate']),
                    'to' => BaseHelper::formatDate($data['endDate']),
                ]) }}
            </x-core::button>
        </div>
    @endif

    <section
        class="ps-dashboard report-chart-content"
        id="report-chart"
    >
        @include(MarketplaceHelper::viewPath('vendor-dashboard.partials.dashboard-content'))
    </section>
@stop

@push('footer')
    <script>
        'use strict';

        var BotbleVariables = BotbleVariables || {};
        BotbleVariables.languages = BotbleVariables.languages || {};
        BotbleVariables.languages.reports = {!! json_encode(trans('plugins/ecommerce::reports.ranges'), JSON_HEX_APOS) !!}
    </script>
@endpush
