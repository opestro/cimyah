<div id="address-histories">
    <x-core::table>
        <x-core::table.header>
            <x-core::table.header.cell>
                #
            </x-core::table.header.cell>
            <x-core::table.header.cell>
                {{ trans('plugins/ecommerce::addresses.address') }}
            </x-core::table.header.cell>
            <x-core::table.header.cell>
                {{ trans('plugins/ecommerce::addresses.zip') }}
            </x-core::table.header.cell>
            <x-core::table.header.cell>
                {{ trans('plugins/ecommerce::addresses.country') }}
            </x-core::table.header.cell>
            <x-core::table.header.cell>
                {{ trans('plugins/ecommerce::addresses.state') }}
            </x-core::table.header.cell>
            <x-core::table.header.cell>
                {{ trans('plugins/ecommerce::addresses.city') }}
            </x-core::table.header.cell>
            <x-core::table.header.cell>
                {{ trans('plugins/ecommerce::addresses.action') }}
            </x-core::table.header.cell>
        </x-core::table.header>

        <x-core::table.body>
        @forelse ($addresses as $address)
            <x-core::table.body.row>
                <x-core::table.body.cell>
                    {{ $loop->iteration }}
                </x-core::table.body.cell>
                <x-core::table.body.cell class="text-start">
                    {{ $address->address }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ $address->zip_code }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ $address->country_name }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ $address->state_name }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ $address->city_name }}
                </x-core::table.body.cell>
                <x-core::table.body.cell class="text-center">
                    <x-core::button
                        :title="trans('core/base::forms.edit')"
                        :data-section="route('customers.addresses.edit', $address->id)"
                        icon="ti ti-edit"
                        class="me-1 btn-trigger-edit-address"
                        color="primary"
                        size="sm"
                        :icon-only="true"
                    />

                    <x-core::button
                        :title="trans('core/base::forms.delete')"
                        :data-section="route('customers.addresses.destroy', $address->id)"
                        icon="ti ti-trash"
                        class="deleteDialog"
                        size="sm"
                        color="danger"
                        :icon-only="true"
                    />
                </x-core::table.body.cell>
            </x-core::table.body.row>
        @empty
            <x-core::table.body.row class="text-center text-muted">
                <x-core::table.body.cell colspan="7">
                    {{ trans('plugins/ecommerce::addresses.no_data') }}
                </x-core::table.body.cell>
            </x-core::table.body.row>
        @endforelse
        </x-core::table.body>
    </x-core::table>
</div>
