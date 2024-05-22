<x-core::form :url="route('ecommerce.store-locators.update-primary-store')" method="post">
    <x-core::form.select
        name="primary_store_id"
        :options="$storeLocators->pluck('name', 'id')->all()"
        :value="($defaultStoreLocator = $storeLocators->where('is_primary', true)->first()) ? $defaultStoreLocator->id : null"
        :label="trans('plugins/ecommerce::store-locator.primary_store_is')"
    />
</x-core::form>
