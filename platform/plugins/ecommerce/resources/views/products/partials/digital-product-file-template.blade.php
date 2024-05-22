<x-core::custom-template id="digital_attachment_template">
    <x-core::table.body.row data-id="__id__">
        <x-core::table.body.cell>
            <a href="javascript:void(0)" class="remove-attachment-input text-danger text-decoration-none">
                <x-core::icon name="ti ti-x" />
            </a>
        </x-core::table.body.cell>
        <x-core::table.body.cell>
            <x-core::icon name="ti ti-paperclip" />
            <span>__file_name__</span>
        </x-core::table.body.cell>
        <x-core::table.body.cell>
            __file_size__
        </x-core::table.body.cell>
        <x-core::table.body.cell>
            -
        </x-core::table.body.cell>
        <x-core::table.body.cell>
            <small class="text-warning">{{ trans('plugins/ecommerce::products.digital_attachments.unsaved') }}</small>
        </x-core::table.body.cell>
    </x-core::table.body.row>
</x-core::custom-template>

<x-core::custom-template id="digital_attachment_external_template">
    <x-core::table.body.row data-id="__id__">
        <x-core::table.body.cell>
            <a href="javascript:void(0)" class="remove-attachment-input text-danger text-decoration-none">
                <x-core::icon name="ti ti-x" />
            </a>
        </x-core::table.body.cell>
        <x-core::table.body.cell>
            <x-core::form.text-input
                name="product_files_external[__id__][name]"
                :placeholder="trans('plugins/ecommerce::products.digital_attachments.enter_file_name')"
            />
            <x-core::form.text-input
                name="product_files_external[__id__][link]"
                :placeholder="trans('plugins/ecommerce::products.digital_attachments.enter_external_link_download') . ' (*)'"
            />
        </x-core::table.body.cell>
        <x-core::table.body.cell colspan="2">
            <div class="input-group">
                <input type="number" name="product_files_external[__id__][size]" class="form-control"
                       placeholder="{{ trans('plugins/ecommerce::products.digital_attachments.enter_file_size') }}" value="0" min="0">
                <span class="input-group-text">
                    {!! Form::select('product_files_external[__id__][unit]', ['B' => 'B', 'kB' => 'kB', 'MB' => 'MB', 'GB' => 'GB', 'TB' => 'TB'], 'kB', ['class' => 'form-select form-select-sm bg-transparent border-0']) !!}
                </span>
            </div>
        </x-core::table.body.cell>
        <x-core::table.body.cell>
            <small class="text-warning">{{ trans('plugins/ecommerce::products.digital_attachments.unsaved') }}</small>
        </x-core::table.body.cell>
    </x-core::table.body.row>
</x-core::custom-template>
