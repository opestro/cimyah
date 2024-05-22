@if (!$order->dont_show_order_info_in_product_list)
    <a
        class="button button-blue"
        href="{{ route('public.orders.tracking', ['order_id' => $order->code, 'email' => $order->user->email ?: $order->address->email]) }}"
    >{{ trans('plugins/ecommerce::email.view_order') }}</a>
    {!! trans('plugins/ecommerce::email.link_go_to_our_shop', ['link' => BaseHelper::getHomepageUrl()]) !!}

    <br />
@endif

<table class="bb-table" cellspacing="0" cellpadding="0">
    <thead>
        <tr>
            <th colspan="2"></th>
            <th>{{ trans('plugins/ecommerce::products.form.quantity') }}</th>
            <th class="bb-text-right">{{ trans('plugins/ecommerce::products.form.price') }}</th>
        </tr>
    </thead>

    <tbody>
        @foreach($products ?? $order->products as $orderProduct)
        <tr>
            <td class="bb-pr-0">
                <a href="">
                    <img src="{{ RvMedia::getImageUrl($orderProduct->product_image, 'thumb') }}" class=" bb-rounded" width="64" height="64" alt="" />
                </a>
            </td>
            <td class="bb-pl-md bb-w-100p">
                <strong>{{ $orderProduct->product_name }}</strong><br />
                @if ($attributes = Arr::get($orderProduct->options, 'attributes'))
                    <span class="bb-text-muted">{{ $attributes }}</span>
                @endif
            </td>
            <td class="bb-text-center">x {{ $orderProduct->qty }}</td>
            <td class="bb-text-right">{{ format_price($orderProduct->price) }}</td>
        </tr>
    @endforeach

        @if (!$order->dont_show_order_info_in_product_list)
            <tr>
                <td colspan="2" class="bb-border-top bb-text-right">{{ trans('plugins/ecommerce::products.form.sub_total') }}</td>
                <td colspan="2" class="bb-border-top bb-text-right">{{ format_price($order->sub_total) }}</td>
            </tr>

            @if ($order->shipping_amount)
                <tr>
                    <td colspan="2" class="bb-text-right">{{ trans('plugins/ecommerce::products.form.shipping_fee') }}</td>
                    <td colspan="2" class="bb-text-right">{{ format_price($order->shipping_amount) }}</td>
                </tr>
            @endif

            @if($order->tax_amount)
                <tr>
                    <td colspan="2" class="bb-text-right">{{ trans('plugins/ecommerce::products.form.tax') }}</td>
                    <td colspan="2" class="bb-text-right">{{ format_price($order->tax_amount) }}</td>
                </tr>
            @endif

            @if ((float)$order->discount_amount)
                <tr>
                    <td colspan="2" class="bb-text-right">{{ trans('plugins/ecommerce::products.form.discount') }}</td>
                    <td colspan="2" class="bb-text-right">{{ format_price($order->discount_amount) }}</td>
                </tr>
            @endif
            <tr>
                <td colspan="2" class="bb-text-right bb-font-strong bb-h3 bb-m-0">{{ trans('plugins/ecommerce::products.form.total') }}</td>
                <td colspan="2" class="bb-font-strong bb-h3 bb-m-0 bb-text-right">{{ format_price($order->amount) }}</td>
            </tr>
        @endif
    </tbody>
</table>
