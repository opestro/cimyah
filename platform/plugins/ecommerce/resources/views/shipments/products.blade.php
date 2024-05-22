<x-core::card class="mb-3">
    <x-core::table :striped="false" :hover="false" class="table-bordered">
        <x-core::table.body>
            @foreach ($shipment->order->products as $orderProduct)
                @php
                    $product = $orderProduct->product->original_product;
                @endphp
                <x-core::table.body.row>
                    <x-core::table.body.cell class="text-center" style="width: 5%">
                        <x-core::icon name="ti ti-truck-delivery" />
                    </x-core::table.body.cell>
                    <x-core::table.body.cell>
                        <div class="d-flex align-items-start gap-2">
                            <img
                                src="{{ RvMedia::getImageUrl($orderProduct->product_image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                alt="{{ $product->name }}"
                                width="60"
                            />
                            <div>
                                <a
                                    class="d-print-none"
                                    href="{{ $productEditRouteName && $product && $product->id ? route($productEditRouteName, $product->id) : '#' }}"
                                    title="{{ $orderProduct->product_name }}"
                                >
                                    {{ $orderProduct->product_name }}
                                </a>

                                @if ($attributes = Arr::get($orderProduct->options, 'attributes'))
                                    <div class="small my-1">
                                        <small>{{ $attributes }}</small>
                                    </div>
                                @endif

                                @if ($sku = Arr::get($orderProduct->options, 'sku') ?: ($product && $product->sku ? $product->sku : null))
                                    <p class="small mb-0">
                                        {{ trans('plugins/ecommerce::shipping.sku') }}: <strong>{{ $sku }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </x-core::table.body.cell>
                    <x-core::table.body.cell class="text-center">
                        <strong>{{ $orderProduct->qty }}</strong>
                        <span>×</span>
                        <strong>{{ format_price($orderProduct->price) }}</strong>
                    </x-core::table.body.cell>
                    <x-core::table.body.cell class="text-center">
                        <span>{{ format_price($orderProduct->price * $orderProduct->qty) }}</span>
                    </x-core::table.body.cell>
                </x-core::table.body.row>
            @endforeach
        </x-core::table.body>
    </x-core::table>

    @if ($orderEditRouteName)
        <x-core::card.footer class="text-center py-2">
            <a
                href="{{ route($orderEditRouteName, $shipment->order_id) }}"
                target="_blank"
            >
                {{ trans('plugins/ecommerce::shipping.view_order', ['order_id' => $shipment->order->code]) }}
                <x-core::icon name="ti ti-external-link" />
            </a>
        </x-core::card.footer>
    @endif
</x-core::card>
