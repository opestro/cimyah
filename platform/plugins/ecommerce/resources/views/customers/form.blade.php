@extends('core/base::forms.form-tabs')

@section('form_end')
    <x-core::modal
        id="add-address-modal"
        :title="trans('plugins/ecommerce::addresses.add_address')"
        :form-action="route('customers.addresses.create.store')"
        form-method="POST"
        size="md"
    >
        <input
            name="customer_id"
            type="hidden"
            value="{{ $form->getModel()->id }}"
        >

        @include('plugins/ecommerce::customers.addresses.form', [
            'address' => new Botble\Ecommerce\Models\Address(),
        ])

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
                id="confirm-add-address-button"
            >
                {{ trans('plugins/ecommerce::addresses.add') }}
            </x-core::button>
        </x-slot:footer>
    </x-core::modal>

    <x-core::modal
        id="edit-address-modal"
        :title="trans('plugins/ecommerce::addresses.edit_address')"
        size="md"
    >
        <div class="modal-loading-block d-none">
            <x-core::loading />
        </div>

        <div class="modal-form-content"></div>

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
                id="confirm-edit-address-button"
            >
                {{ trans('plugins/ecommerce::addresses.save') }}
            </x-core::button>
        </x-slot:footer>
    </x-core::modal>

    <x-core::modal
        :title="trans('core/base::tables.confirm_delete')"
        name="modal-confirm-delete"
        id="delete-address-modal"
        class="modal-confirm-delete"
    >
        {{ trans('core/base::tables.confirm_delete_msg') }}

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
                class="delete-crud-entry"
            >
                {{ trans('core/base::tables.delete') }}
            </x-core::button>
        </x-slot:footer>
    </x-core::modal>
@endsection

@section('form_main_end')
    @if ($customerId = $form->getModel()->id)
        <div class="customer-reviews-table widget meta-boxes">
            <x-core::card>
                <x-core::card.header>
                    <h4 class="card-title">{{ trans('plugins/ecommerce::review.name') }}</h4>
                </x-core::card.header>

                <div>
                    {!! app(Botble\Ecommerce\Tables\CustomerReviewTable::class)->customerId($customerId)->setAjaxUrl(route('customers.ajax.reviews', $customerId))->renderTable() !!}
                </div>
            </x-core::card>
        </div>
    @endif
@endsection
