@extends(BaseHelper::getAdminMasterLayoutTemplate())

@push('header-action')
    <x-core::button
        type="button"
        color="primary"
        :outlined="true"
        class="date-range-picker"
        data-format-value="{{ trans('plugins/ecommerce::reports.date_range_format_value', ['from' => '__from__', 'to' => '__to__']) }}"
        data-format="{{ Str::upper(config('core.base.general.date_format.js.date')) }}"
        data-href="{{ route('marketplace.reports.index') }}"
        data-start-date="{{ $startDate }}"
        data-end-date="{{ $endDate }}"
        icon="ti ti-calendar"
    >
        {{ trans('plugins/ecommerce::reports.date_range_format_value', [
            'from' => BaseHelper::formatDate($startDate),
            'to' => BaseHelper::formatDate($endDate),
        ]) }}
    </x-core::button>
@endpush

@section('content')
    <div>
        {!! $widget->render(MARKETPLACE_MODULE_SCREEN_NAME) !!}
    </div>
@endsection

@push('footer')
    <script>
        var BotbleVariables = BotbleVariables || {};
        BotbleVariables.languages = BotbleVariables.languages || {};
        BotbleVariables.languages.reports = @json(trans('plugins/ecommerce::reports.ranges'))
    </script>
@endpush
