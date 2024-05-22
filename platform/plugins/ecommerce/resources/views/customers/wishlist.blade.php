<x-core::table>
    <x-core::table.header>
        <x-core::table.header.cell>
            #
        </x-core::table.header.cell>
        <x-core::table.header.cell>
            {{ trans('plugins/ecommerce::ecommerce.product') }}
        </x-core::table.header.cell>
        <x-core::table.header.cell>
            {{ trans('core/base::tables.created_at') }}
        </x-core::table.header.cell>
    </x-core::table.header>

    <x-core::table.body>
        @forelse ($wishlist as $item)
            <x-core::table.body.row>
                <x-core::table.body.cell>
                    {{ $loop->iteration }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    <a href="{{ $item->product->url }}" target="_blank" class="d-flex gap-2 align-items-center">
                        {{ RvMedia::image($item->product->image, attributes: ['class' => 'rounded', 'width' => 38]) }}
                        {{ $item->product->name }}
                    </a>
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ BaseHelper::formatDateTime($item->created_at) }}
                </x-core::table.body.cell>
            </x-core::table.body.row>
        @empty
            <x-core::table.body.row class="text-center text-muted">
                <x-core::table.body.cell colspan="7">
                    {{ trans('core/table::table.no_data') }}
                </x-core::table.body.cell>
            </x-core::table.body.row>
        @endforelse
    </x-core::table.body>
</x-core::table>
