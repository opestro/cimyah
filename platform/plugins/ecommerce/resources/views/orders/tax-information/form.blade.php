<x-core::form :url="route('orders.update-tax-information', $tax->getKey())">
    <input
        name="order_id"
        type="hidden"
        value="{{ $orderId }}"
    >

    <x-core::form.text-input
        :required="true"
        :label="trans('plugins/ecommerce::order.tax_info.company_name')"
        name="company_name"
        :value="$tax->company_name"
        :placeholder="trans('plugins/ecommerce::order.tax_info.company_name')"
    />

    <x-core::form.text-input
        :label="trans('plugins/ecommerce::order.tax_info.company_email')"
        type="email"
        name="company_email"
        :value="$tax->company_email"
        :placeholder="trans('plugins/ecommerce::order.tax_info.company_email')"
    />

    <x-core::form.text-input
        :label="trans('plugins/ecommerce::order.tax_info.company_tax_code')"
        name="company_tax_code"
        :value="$tax->company_tax_code"
        :placeholder="trans('plugins/ecommerce::order.tax_info.company_tax_code')"
    />

    <x-core::form.text-input
        :label="trans('plugins/ecommerce::order.tax_info.company_address')"
        name="company_address"
        :value="$tax->company_address"
        :placeholder="trans('plugins/ecommerce::order.tax_info.company_address')"
    />
</x-core::form>
