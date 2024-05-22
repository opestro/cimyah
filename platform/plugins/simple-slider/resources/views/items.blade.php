@include('core/table::simple-table')

@push('footer')
    <x-core::modal id="simple-slider-item-modal">
        <x-core::loading />

        <x-slot:footer>
            <x-core::button
                data-bs-dismiss="modal"
            >
                {{ trans('core/base::forms.cancel') }}
            </x-core::button>

            <x-core::button
                type="submit"
                color="primary"
            >
                {{ trans('core/base::forms.save_and_continue') }}
            </x-core::button>
        </x-slot:footer>
    </x-core::modal>

    <x-core::modal.action
        type="danger"
        class="single-action-confirm-modal"
        title="''"
        description="''"
        submit-button-label=""
        :submit-button-attrs="['class' => 'confirm-trigger-single-action-button']"
    />
@endpush
