<x-core::card class="report-chart-content" id="report-chart">
    <x-core::card.header>
        <h4 class="card-title">{{ trans('plugins/marketplace::marketplace.reports.sale_commissions') }}</h4>
    </x-core::card.header>

    <x-core::card.body>
        <div class="row">
            <div class="col-md-8 mb-2">
                <div id="sale-commissions-chart"></div>
            </div>

            <div class="col-md-4">
                @if ($count['revenues']->isNotEmpty())
                    <div class="rp-card-chart position-relative mb-3">
                        <div id="revenue-earnings-chart"></div>
                        <div class="rp-card-information">
                            <x-core::icon name="ti ti-wallet" />
                            <strong>{{ format_price($salesReport['totalFee']) }}</strong>
                            <small>{{ trans('plugins/ecommerce::reports.total_earnings') }}</small>
                        </div>
                    </div>
                    <div class="rp-card-status text-center">
                        @foreach ($count['revenues'] as $item)
                            <p>
                                <x-core::icon name="ti ti-circle-filled" class="mb-0 me-1" size="sm" style="color: {{ Arr::get($item, 'color') }}"/>
                                <strong>{{ format_price($item['value']) }}</strong>
                                <span>{{ $item['label'] }}</span>
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

@php
    $revenues = fn(string $key): array => $count['revenues']->pluck($key)->toArray();
@endphp

@if (request()->ajax())
    @include('plugins/marketplace::reports.widgets.chart-script')
@else
    @push('footer')
        @include('plugins/marketplace::reports.widgets.chart-script')
    @endpush
@endif
