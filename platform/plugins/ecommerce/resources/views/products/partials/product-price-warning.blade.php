    @if ($flashSale)
        <x-core::alert
            type="warning"
        >
            {!! BaseHelper::clean(
                trans('plugins/ecommerce::products.product_price_flash_sale_warning', [
                    'name' => $flashSale->name,
                    'price' => format_price($data->front_sale_price),
                ]),
            ) !!}
        </x-core::alert>
    @endif

    @if ($discount)
        <x-core::alert
            type="warning"
        >
            {!! BaseHelper::clean(
                trans('plugins/ecommerce::products.product_price_discount_warning', [
                    'name' => $discount->title,
                    'price' => format_price($data->front_sale_price),
                ]),
            ) !!}
        </x-core::alert>
    @endif
