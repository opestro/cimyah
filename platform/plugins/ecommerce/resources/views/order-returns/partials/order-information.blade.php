<x-core::card class="mb-3">
    <x-core::card.header>
        <x-core::card.title>
            {{ trans('plugins/ecommerce::order.order_information') }}
        </x-core::card.title>
    </x-core::card.header>
    <x-core::card.body>
        <x-core::table :striped="false" :hover="false">
            <x-core::table.body>
                @php
                    $returnRequest->load(['items.product', 'items.orderProduct']);
                @endphp

                @foreach ($returnRequest->items as $returnRequestItem)
                    @php
                        $orderProduct = $returnRequestItem->orderProduct;
                        $product = $orderProduct->product;
                    @endphp

                    <x-core::table.body.row>
                        <x-core::table.body.cell style="width: 80px">
                            <img
                                src="{{ RvMedia::getImageUrl($orderProduct->product_image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                alt="{{ $orderProduct->product_name }}"
                            >
                        </x-core::table.body.cell>
                        <x-core::table.body.cell>
                            @if ($product->id && $product->original_product->id)
                                <a
                                    href="{{ route($productEditRouteName, $product->original_product->id) }}"
                                    title="{{ $returnRequestItem->product_name }}"
                                    target="_blank"
                                >{{ $returnRequestItem->product_name }}</a>
                            @else
                                <span>{{ $returnRequestItem->product_name }}</span>
                            @endif
                            @if ($orderProduct->options)
                                @if ($sku = Arr::get($orderProduct->options, 'sku'))
                                    <p class="mb-0">
                                        {{ trans('plugins/ecommerce::order.sku') }}:
                                        <strong>{{ $sku }}</strong>
                                    </p>
                                @endif

                                @if ($attributes = Arr::get($orderProduct->options, 'attributes'))
                                    <div>
                                        <small>{{ $attributes }}</small>
                                    </div>
                                @endif
                            @endif
                        </x-core::table.body.cell>
                        <x-core::table.body.cell class="text-end">
                            {{ format_price($returnRequestItem->price_with_tax) }}
                        </x-core::table.body.cell>
                        <x-core::table.body.cell class="text-center">
                            x
                        </x-core::table.body.cell>
                        <x-core::table.body.cell class="text-start text-danger">
                            {{ $returnRequestItem->qty }}
                        </x-core::table.body.cell>
                        <x-core::table.body.cell class="text-end">
                            {{ format_price($returnRequestItem->refund_amount) }}
                        </x-core::table.body.cell>
                    </x-core::table.body.row>
                @endforeach
            </x-core::table.body>
        </x-core::table>

        <div class="offset-md-6">
            <x-core::table :striped="false" :hover="false" class="table-borderless">
                <x-core::table.body>
                    <x-core::table.body.row>
                        <x-core::table.body.cell colspan="3" class="text-end">
                            {{ trans('plugins/ecommerce::order.total_return_amount') }}:
                        </x-core::table.body.cell>
                        <x-core::table.body.cell class="text-end">
                            {{ format_price($returnRequest->items->sum('refund_amount')) }}
                        </x-core::table.body.cell>
                    </x-core::table.body.row>
                    <x-core::table.body.row>
                        <x-core::table.body.cell
                            class="text-end"
                            colspan="3"
                        >
                            {{ trans('plugins/ecommerce::order.status') }}:
                        </x-core::table.body.cell>
                        <x-core::table.body.cell class="text-end">
                            {!! BaseHelper::clean($returnRequest->return_status->toHtml()) !!}
                        </x-core::table.body.cell>
                    </x-core::table.body.row>
                </x-core::table.body>
            </x-core::table>
        </div>
    </x-core::card.body>
</x-core::card>

@if (! in_array($returnRequest->return_status, [
    Botble\Ecommerce\Enums\OrderReturnStatusEnum::COMPLETED,
    Botble\Ecommerce\Enums\OrderReturnStatusEnum::CANCELED,
]))
    <x-core::card>
        <x-core::card.header>
            <x-core::card.title>
                {{ trans('plugins/ecommerce::order.change_return_order_status') }}
            </x-core::card.title>
        </x-core::card.header>
        <x-core::card.body>
            @if ($returnRequest->return_status != Botble\Ecommerce\Enums\OrderReturnStatusEnum::PROCESSING)
                <x-core::button
                    type="button"
                    color="primary"
                    icon="ti ti-circle-check"
                    data-bs-toggle="modal"
                    data-bs-target="#approve-order-return-modal"
                >
                    {{ trans('plugins/ecommerce::order.order_return_moderation.approve_button') }}
                </x-core::button>

                <x-core::button
                    type="button"
                    color="danger"
                    :outlined="true"
                    icon="ti ti-x"
                    data-bs-toggle="modal"
                    data-bs-target="#reject-order-return-modal"
                >
                    {{ trans('plugins/ecommerce::order.order_return_moderation.reject_button') }}
                </x-core::button>
            @else
                <x-core::button
                    type="button"
                    color="success"
                    icon="ti ti-circle-check"
                    data-bs-toggle="modal"
                    data-bs-target="#mark-as-completed-order-return-modal"
                >
                    {{ trans('plugins/ecommerce::order.order_return_moderation.mark_as_completed_button') }}
                </x-core::button>
            @endif
        </x-core::card.body>
    </x-core::card>

    <x-core::modal
        id="approve-order-return-modal"
        :title="trans('plugins/ecommerce::order.order_return_moderation.approve_confirmation_title')"
        type="success"
    >
        <p class="text-secondary">
            {{ trans('plugins/ecommerce::order.order_return_moderation.approve_confirmation_description') }}
        </p>

        {!!
            \Botble\Ecommerce\Forms\ModerateOrderReturnForm::create()
                ->setUrl(route($orderReturnEditRouteName, $returnRequest->getKey()))
                ->addHiddenStatus(Botble\Ecommerce\Enums\OrderReturnStatusEnum::PROCESSING)
                ->addSubmitButton(trans('plugins/ecommerce::order.order_return_moderation.approve_button'), 'primary')
                ->renderForm()
        !!}
    </x-core::modal>

    <x-core::modal
        id="reject-order-return-modal"
        :title="trans('plugins/ecommerce::order.order_return_moderation.reject_confirmation_title')"
        type="danger"
    >
        <p class="text-secondary">
            {{ trans('plugins/ecommerce::order.order_return_moderation.reject_confirmation_description') }}
        </p>

        {!!
            \Botble\Ecommerce\Forms\ModerateOrderReturnForm::create()
                ->setUrl(route($orderReturnEditRouteName, $returnRequest->getKey()))
                ->addHiddenStatus(Botble\Ecommerce\Enums\OrderReturnStatusEnum::CANCELED)
                ->addSubmitButton(trans('plugins/ecommerce::order.order_return_moderation.reject_button'), 'danger')
                ->renderForm()
        !!}
    </x-core::modal>

    <x-core::modal
        id="mark-as-completed-order-return-modal"
        :title="trans('plugins/ecommerce::order.order_return_moderation.mark_as_completed_confirmation_title')"
        type="success"
    >
        <p class="text-secondary">
            {{ trans('plugins/ecommerce::order.order_return_moderation.mark_as_completed_confirmation_description') }}
        </p>

            {!!
                \Botble\Ecommerce\Forms\ModerateOrderReturnForm::create()
                    ->setUrl(route($orderReturnEditRouteName, $returnRequest->getKey()))
                    ->addHiddenStatus(Botble\Ecommerce\Enums\OrderReturnStatusEnum::COMPLETED)
                    ->addSubmitButton(trans('plugins/ecommerce::order.order_return_moderation.mark_as_completed_button'), 'success')
                    ->renderForm()
            !!}
    </x-core::modal>
@endif

<x-core::card>
    <x-core::card.header>
        <x-core::card.title>
            {{ trans('plugins/ecommerce::order.history') }}
        </x-core::card.title>
    </x-core::card.header>

    <x-core::card.body>
        <ul class="steps steps-vertical">
            @foreach($returnRequest->histories as $history)
                <li @class(['step-item', 'user-action' => $loop->first])>
                    <div class="h4 m-0">{{ $history->description }}</div>
                    <div class="d-flex justify-content-between">
                        <div class="text-secondary">
                            @if($history->reason)
                                {{ trans('plugins/ecommerce::order.cancellation_reason', ['reason' => $history->reason]) }}
                            @endif
                        </div>
                        <div class="text-secondary">{{ $history->created_at }}</div>
                    </div>
                </li>
            @endforeach
        </ul>
    </x-core::card.body>
</x-core::card>
