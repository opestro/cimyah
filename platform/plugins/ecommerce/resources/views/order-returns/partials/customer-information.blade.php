<x-core::card>
    <x-core::card.header>
        <x-core::card.title>
            {{ trans('plugins/ecommerce::order.customer_label') }}
        </x-core::card.title>
    </x-core::card.header>
    <x-core::card.body class="p-0">
        <div class="p-3">
            <div class="mb-3">
                <span class="avatar avatar-lg avatar-rounded" style="background-image: url('{{ $returnRequest->customer->id ? $returnRequest->customer->avatar_url : $returnRequest->order->address->avatar_url }}')"></span>
            </div>

            <p class="mb-1 fw-semibold">{{ $returnRequest->customer->name ?: $returnRequest->order->address->name }}</p>

            @if ($returnRequest->customer->id)
                <p class="mb-1">
                    <x-core::icon name="ti ti-inbox" />
                    {{ $returnRequest->customer->orders()->count() }}
                    {{ trans('plugins/ecommerce::order.orders') }}
                </p>
            @endif

            <p class="mb-1">
                <a href="mailto:{{ $email = ($returnRequest->customer->email ?: $returnRequest->order->address->email) }}">
                    {{ $email }}
                </a>
            </p>

            @if ($returnRequest->customer->id)
                <p class="mb-1">{{ trans('plugins/ecommerce::order.have_an_account_already') }}</p>
            @else
                <p class="mb-1">{{ trans('plugins/ecommerce::order.dont_have_an_account_yet') }}</p>
            @endif
        </div>

        <div class="hr my-1"></div>

        <div class="p-3">
            <h4>{{ trans('plugins/ecommerce::order.address') }}</h4>

            <dl class="mb-0">
                <dd>{{ $returnRequest->order->address->name }}</dd>
                <dd>
                    <a href="tel:{{ $phone = $returnRequest->order->address->phone }}">
                        <x-core::icon name="ti ti-phone" />
                        <span dir="ltr">{{ $phone }}</span>
                    </a>
                </dd>
                <dd>{{ $returnRequest->order->address->full_address }}</dd>
                <dd>
                    <a
                        href="https://maps.google.com/?q={{ $returnRequest->full_address }}"
                        target="_blank"
                    >
                        {{ trans('plugins/ecommerce::order.see_maps') }}
                    </a>
                </dd>
            </dl>
        </div>

        <div class="hr my-1"></div>

        <div class="p-3">
            <h4 class="mb-2">{{ trans('plugins/ecommerce::order.return_reason') }}</h4>

            <p class="text-danger mb-0">
                @if($returnRequest->reason->label())
                    {!! BaseHelper::clean($returnRequest->reason->toHtml()) !!}
                @else
                    @foreach($returnRequest->items as $returnItem)
                        {!! BaseHelper::clean($returnItem->reason->toHtml()) !!}@if (! $loop->last), @endif
                    @endforeach
                @endif
            </p>
        </div>
    </x-core::card.body>
</x-core::card>
