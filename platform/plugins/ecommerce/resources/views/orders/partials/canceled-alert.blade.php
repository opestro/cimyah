@if ($order->status == Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED)
    <x-core::alert type="warning">
        <x-slot:title>
            {{ trans('plugins/ecommerce::order.order_canceled') }}
        </x-slot:title>

        {{ trans('plugins/ecommerce::order.order_was_canceled_at') }}
        <strong>{{ BaseHelper::formatDate($order->updated_at, 'H:i d/m/Y') }}</strong>.
    </x-core::alert>
@endif
