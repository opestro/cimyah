@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core-setting::section
        :title="trans('plugins/ecommerce::setting.store_locator.name')"
        :description="trans('plugins/ecommerce::setting.store_locator.store_locator_description')"
        :card="false"
    >
        <x-core::card>
            <div class="table-responsive">
                <x-core::table class="store-locator-table">
                    <x-core::table.header>
                        <x-core::table.header.cell>
                            {{ trans('core/base::tables.name') }}
                        </x-core::table.header.cell>
                        <x-core::table.header.cell>
                            {{ trans('core/base::tables.email') }}
                        </x-core::table.header.cell>
                        <x-core::table.header.cell>
                            {{ trans('plugins/ecommerce::ecommerce.phone') }}
                        </x-core::table.header.cell>
                        <x-core::table.header.cell>
                            {{ trans('plugins/ecommerce::ecommerce.address') }}
                        </x-core::table.header.cell>
                        <x-core::table.header.cell>
                            {{ trans('plugins/ecommerce::store-locator.is_primary') }}
                        </x-core::table.header.cell>
                        <x-core::table.header.cell></x-core::table.header.cell>
                    </x-core::table.header>
                    <x-core::table.body>
                        @foreach ($storeLocators as $storeLocator)
                            <x-core::table.body.row>
                                <x-core::table.body.cell>
                                    {{ $storeLocator->name }}
                                </x-core::table.body.cell>
                                <x-core::table.body.cell>
                                    <a href="mailto:{{ $storeLocator->email }}">{{ $storeLocator->email }}</a>
                                </x-core::table.body.cell>
                                <x-core::table.body.cell>
                                    {{ $storeLocator->phone }}
                                </x-core::table.body.cell>
                                <x-core::table.body.cell>
                                    {{ $storeLocator->full_address }}
                                </x-core::table.body.cell>
                                <x-core::table.body.cell>
                                    {{ $storeLocator->is_primary ? trans('core/base::base.yes') : trans('core/base::base.no') }}
                                </x-core::table.body.cell>
                                <x-core::table.body.cell class="text-end">
                                    <x-core::button
                                        data-bb-toggle="store-locator-show"
                                        icon="ti ti-edit"
                                        color="primary"
                                        data-type="update"
                                        size="sm"
                                        :icon-only="true"
                                        data-load-form="{{ route('ecommerce.store-locators.form', $storeLocator->id) }}"
                                    />
                                    @if (!$storeLocator->is_primary && $storeLocators->count() > 1)
                                        <x-core::button
                                            type="button"
                                            data-target="{{ route('ecommerce.store-locators.destroy', $storeLocator->id) }}"
                                            icon="ti ti-trash"
                                            class="btn-trigger-delete-store-locator"
                                            color="danger"
                                            size="sm"
                                            :icon-only="true"
                                        />
                                    @endif
                                </x-core::table.body.cell>
                            </x-core::table.body.row>
                        @endforeach
                    </x-core::table.body>
                </x-core::table>
            </div>
            <x-core::card.body>
                <x-core::button
                    tag="a"
                    data-bb-toggle="store-locator-show"
                    class="btn-link p-0"
                    data-type="create"
                    data-load-form="{{ route('ecommerce.store-locators.form') }}"
                >
                    {{ trans('plugins/ecommerce::setting.store_locator.form.add_new') }}
                </x-core::button>
                @if (count($storeLocators) > 1)
                    {{ trans('plugins/ecommerce::ecommerce.or') }}
                    <x-core::button
                        tag="a"
                        class="btn-link p-0"
                        data-bs-toggle="modal"
                        data-bs-target="#change-primary-store-locator-modal"
                        href="#"
                    >
                        {{ trans('plugins/ecommerce::setting.store_locator.form.change_primary_store') }}
                    </x-core::button>
                @endif
            </x-core::card.body>
        </x-core::card>
    </x-core-setting::section>
@endsection

@push('footer')
    <x-core::modal
        id="add-store-locator-modal"
        :title="trans('plugins/ecommerce::setting.store_locator.form.add_location')"
        size="md"
    >
        @include('plugins/ecommerce::settings.store-locator-item', ['locator' => null])
        <x-slot:footer>
            <x-core::button
                data-bs-dismiss="modal"
                class="me-2"
            >
                {{ trans('core/base::forms.cancel') }}
            </x-core::button>

            <x-core::button
                type="submit"
                color="primary"
            >
                {{ trans('plugins/ecommerce::setting.store_locator.form.save_location') }}
            </x-core::button>
        </x-slot:footer>
    </x-core::modal>

    <x-core::modal
        id="update-store-locator-modal"
        :title="trans('plugins/ecommerce::setting.store_locator.form.edit_location')"
        size="md"
    >
        @include('plugins/ecommerce::settings.store-locator-item', ['locator' => null])
        <x-slot:footer>
            <x-core::button
                data-bs-dismiss="modal"
                class="me-2"
            >
                {{ trans('core/base::forms.cancel') }}
            </x-core::button>

            <x-core::button
                type="submit"
                color="primary"
            >
                {{ trans('plugins/ecommerce::setting.store_locator.form.save_location') }}
            </x-core::button>
        </x-slot:footer>
    </x-core::modal>

    <x-core::modal
        id="delete-store-locator-modal"
        :title="trans('plugins/ecommerce::setting.store_locator.form.delete_location')"
        size="md"
    >
        {!! trans('plugins/ecommerce::setting.store_locator.form.delete_location_confirmation') !!}
        <x-slot:footer>
            <x-core::button
                data-bs-dismiss="modal"
                class="me-2"
            >
                {{ trans('core/base::tables.cancel') }}
            </x-core::button>

            <x-core::button
                type="submit"
                color="danger"
                id="delete-store-locator-button"
            >
                {{ trans('plugins/ecommerce::ecommerce.accept') }}
            </x-core::button>
        </x-slot:footer>
    </x-core::modal>

    @if (count($storeLocators) > 1)
        <x-core::modal
            id="change-primary-store-locator-modal"
            :title="trans('plugins/ecommerce::setting.store_locator.form.change_primary_location')"
            size="sm"
        >
            @include('plugins/ecommerce::settings.store-locator-change-primary', compact('storeLocators'))

            <x-slot:footer>
                <x-core::button
                    data-bs-dismiss="modal"
                    class="me-2"
                >
                    {{ trans('core/base::tables.cancel') }}
                </x-core::button>

                <x-core::button
                    type="submit"
                    color="primary"
                    id="change-primary-store-locator-button"
                >
                    {{ trans('plugins/ecommerce::ecommerce.accept') }}
                </x-core::button>
            </x-slot:footer>
        </x-core::modal>
    @endif
@endpush
