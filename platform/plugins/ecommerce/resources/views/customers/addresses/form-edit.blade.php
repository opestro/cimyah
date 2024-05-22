<x-core::form :url="route('customers.addresses.edit.update', $address->id)" method="post">
    <input
        name="customer_id"
        type="hidden"
        value="{{ $address->customer_id }}"
    >

    @include('plugins/ecommerce::customers.addresses.form', ['address' => $address])
</x-core::form>
