@extends(EcommerceHelper::viewPath('customers.master'))

@section('title', __('Order Return Requests'))

@section('content')
    <div class="table-responsive customer-list-order">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>{{ __('ID number') }}</th>
                <th>{{ __('Order ID number') }}</th>
                <th>{{ __('Items Count') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @if ($requests->isNotEmpty())
                @foreach ($requests as $item)
                    <tr>
                        <td>{{ $item->code }}</td>
                        <td><a
                                href="{{ route('customer.orders.view', $item->order_id) }}"
                                title="Click to show detail"
                            >{{ $item->order->code }}</a></td>
                        <td>{{ $item->items_count }}</td>
                        <td>{{ $item->created_at->translatedFormat('M d, Y h:m') }}</td>
                        <td>{!! BaseHelper::clean($item->return_status->toHtml()) !!}</td>
                        <td>
                            <a
                                class="btn btn-primary btn-sm"
                                href="{{ route('customer.order_returns.detail', $item->id) }}"
                            >{{ __('View') }}</a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td
                        class="text-center"
                        colspan="6"
                    >{{ __('No order return requests!') }}</td>
                </tr>
            @endif
            </tbody>
        </table>

        {!! $requests->links() !!}
    </div>
@stop
