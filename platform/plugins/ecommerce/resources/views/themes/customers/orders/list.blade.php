@extends(EcommerceHelper::viewPath('customers.master'))

@section('title', __('Orders'))

@section('content')
    <div class="table-responsive customer-list-order">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>{{ __('Order number') }}</th>
                    <th>{{ __('Created at') }}</th>
                    <th>{{ __('Total') }}</th>
                    <th>{{ __('Payment method') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @if ($orders->isNotEmpty())
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->code }}</td>
                            <td>{{ $order->created_at->format('d M Y H:i:s') }}</td>
                            <td>{{ __(':price for :total item(s)', ['price' => $order->amount_format, 'total' => $order->products_count]) }}</td>
                            <td>
                                @if(is_plugin_active('payment') && $order->payment->id && $order->payment->payment_channel->label())
                                    {{ $order->payment->payment_channel->label() }}
                                @else
                                    &mdash;
                                @endif
                            </td>

                            <td>{!! BaseHelper::clean($order->status->toHtml()) !!}</td>

                            <td>
                                <a
                                    class="btn btn-primary btn-sm"
                                    href="{{ route('customer.orders.view', $order->id) }}"
                                >{{ __('View') }}</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td
                            class="text-center"
                            colspan="6"
                        >{{ __('No orders yet!') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        {!! $orders->links() !!}
    </div>
@stop
