<x-core::card class="report-chart-content" id="report-chart">
    <x-core::card.header>
        <h4 class="card-title">{{ trans('plugins/ecommerce::reports.sales_reports') }}</h4>
    </x-core::card.header>
    <x-core::card.body>
        <div class="row">
            <div class="col-md-8 mb-2">
                <div id="sales-report-chart"></div>
                @if ($earningSales = $salesReport['earningSales'])
                    <div class="row">
                        <div class="col-12">
                            <ul class="list-unstyled mb-0">
                                @foreach ($earningSales as $earningSale)
                                    <li>
                                        <x-core::icon name="ti ti-circle-filled" class="mb-0 me-1" style="color: {{ $earningSale['color'] }}" />
                                        {{ $earningSale['text'] }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-4">
                @if (collect($count['revenues'])->count())
                    <div class="rp-card-chart position-relative mb-3">
                        <div id="revenue-chart"></div>
                        <div class="rp-card-information">
                            <x-core::icon name="ti ti-wallet" />
                            @foreach (collect($count['revenues'])->where('status') as $item)
                                <strong>{{ format_price($item['value']) }}</strong>
                            @endforeach
                            <small>{{ trans('plugins/ecommerce::reports.total_earnings') }}</small>
                        </div>
                    </div>
                    <div class="rp-card-status text-center">
                        @foreach ($count['revenues'] as $item)
                            <p>
                                <x-core::icon name="ti ti-circle-filled" class="mb-0 me-1" size="sm" style="color: {{ Arr::get($item, 'color') }}" />
                                <strong>{{ format_price($item['value']) }}</strong>
                                <span class="ms-1">{{ $item['label'] }}</span>
                            </p>
                        @endforeach
                    </div>
                @else
                    <div>
                        @include('core/dashboard::partials.no-data')
                    </div>
                @endif
            </div>
        </div>
    </x-core::card.body>
</x-core::card>

@if (request()->ajax())
    @include('plugins/ecommerce::reports.widgets.chart-script')
@else
    @push('footer')
        @include('plugins/ecommerce::reports.widgets.chart-script')
    @endpush
@endif
