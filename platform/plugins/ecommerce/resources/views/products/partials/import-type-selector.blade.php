<x-core::form.select
    :label="trans('plugins/ecommerce::bulk-import.import_types.name')"
    name="type"
    :options="[
        'all' => trans('plugins/ecommerce::bulk-import.import_types.all'),
        'products' => trans('plugins/ecommerce::bulk-import.import_types.products'),
        'variations' => trans('plugins/ecommerce::bulk-import.import_types.variations'),
    ]"
    :required="true"
/>
