@if ($shipment->histories->isNotEmpty())
    <x-core::card>
        <x-core::card.header>
            <x-core::card.title>
                {{ trans('plugins/ecommerce::shipping.history') }}
            </x-core::card.title>
        </x-core::card.header>

        <x-core::card.body>
            <ul class="steps steps-vertical" id="order-history-wrapper">
                @foreach ($shipment->histories->sortByDesc('created_at') as $history)
                    <li @class(['step-item', 'user-action' => $history->user_id])>
                        <div class="h4 m-0">
                            {!! BaseHelper::clean(OrderHelper::processHistoryVariables($history)) !!}
                        </div>
                        <div class="text-secondary">{{ BaseHelper::formatDateTime($history->created_at) }}</div>
                    </li>
                @endforeach
            </ul>
        </x-core::card.body>
    </x-core::card>
@endif
