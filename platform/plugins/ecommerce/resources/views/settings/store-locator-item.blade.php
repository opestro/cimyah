<x-core::form
    :url="$locator
    ? route('ecommerce.store-locators.edit.post', $locator->id)
    : route('ecommerce.store-locators.create')"
    method="post"
>
    <div class="row">
        <x-core::form.text-input
            wrapper-class="col-md-6"
            name="name"
            :value="$locator ? $locator->name : null"
            :placeholder="trans('plugins/ecommerce::store-locator.store_name')"
            :label="trans('plugins/ecommerce::store-locator.store_name')"
            :required="true"
        />

        <x-core::form.text-input
            wrapper-class="col-md-6"
            name="phone"
            :value="$locator ? $locator->phone : null"
            :placeholder="trans('plugins/ecommerce::ecommerce.phone')"
            :label="trans('plugins/ecommerce::ecommerce.phone')"
            :required="true"
        />

        <x-core::form.text-input
            type="email"
            name="email"
            :value="$locator ? $locator->email : null"
            :placeholder="trans('plugins/ecommerce::ecommerce.email')"
            :label="trans('plugins/ecommerce::ecommerce.email')"
        />

        <x-core::form.text-input
            name="address"
            :value="$locator ? $locator->address : null"
            :placeholder="trans('plugins/ecommerce::ecommerce.address')"
            :label="trans('plugins/ecommerce::ecommerce.address')"
            :required="true"
        />

        <x-core::form.select
            id="store_country"
            name="country"
            data-type="country"
            :searchable="true"
            :label="trans('plugins/ecommerce::ecommerce.country')"
            :options="EcommerceHelper::getAvailableCountries()"
            :value="$locator ? $locator->country : null"
            :required="true"
        />
        @if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation())
            @php
                $stateOptions = [];

                if ($locator ? $locator->country : null || !EcommerceHelper::isUsingInMultipleCountries()) {
                    $stateOptions = EcommerceHelper::getAvailableStatesByCountry($locator ? $locator->country : null);
                }
            @endphp

            <x-core::form.select
                wrapper-class="col-md-6"
                id="store_state"
                name="state"
                data-type="state"
                data-url="{{ route('ajax.states-by-country') }}"
                :label="trans('plugins/ecommerce::ecommerce.state')"
                :options="$stateOptions"
                :value="$locator ? $locator->state : null"
                :required="true"
            />
        @else
            <x-core::form.text-input
                wrapper-class="col-md-6"
                id="store_state"
                name="state"
                :value="$locator ? $locator->state : null"
                :placeholder="trans('plugins/ecommerce::ecommerce.state')"
                :label="trans('plugins/ecommerce::ecommerce.state')"
                :required="true"
            />
        @endif

        @if (EcommerceHelper::useCityFieldAsTextField())
            <x-core::form.text-input
                wrapper-class="col-md-6"
                id="store_city"
                name="city"
                :value="$locator ? $locator->city : null"
                :placeholder="trans('plugins/ecommerce::ecommerce.city')"
                :label="trans('plugins/ecommerce::ecommerce.city')"
                :required="true"
            />

        @else
            @php
                $cityOptions = [];

                if ($locator ? $locator->state : null) {
                    $cityOptions = EcommerceHelper::getAvailableCitiesByState($locator ? $locator->state : null);
                }
            @endphp
            <x-core::form.select
                wrapper-class="col-md-6"
                id="store_city"
                name="city"
                data-type="city"
                data-url="{{ route('ajax.cities-by-state') }}"
                :label="trans('plugins/ecommerce::ecommerce.city')"
                :options="$cityOptions"
                :value="$locator ? $locator->city : null"
                :required="true"
            />
        @endif

        @if (EcommerceHelper::isZipCodeEnabled())
            <x-core::form.text-input
                wrapper-class="col-md-6"
                id="store_zip_code"
                name="zip_code"
                :value="$locator ? $locator->zip_code : null"
                :placeholder="trans('plugins/ecommerce::store-locator.zip_code')"
                :label="trans('plugins/ecommerce::store-locator.zip_code')"
                :required="true"
            />
        @endif

        <x-core::form.on-off.checkbox
            name="is_shipping_location"
            label="{{ trans('plugins/ecommerce::store-locator.is_shipping_location') }}"
            checked="{{ !$locator || $locator->is_shipping_location }}"
        />
    </div>
</x-core::form>
