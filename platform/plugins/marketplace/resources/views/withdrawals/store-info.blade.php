<x-core::alert type="warning" class="approve-product-warning">
    {!! BaseHelper::clean(
        trans('plugins/marketplace::store.withdrawal_approval_notification', [
            'vendor' => Html::link(route('marketplace.store.view', $store->id), $store->name, ['target' => '_blank']),
            'balance' => format_price($store->customer->balance),
        ]),
    ) !!}
</x-core::alert>
