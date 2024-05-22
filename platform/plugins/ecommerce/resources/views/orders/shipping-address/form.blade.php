<x-core::form :url="$url">
    <input name="order_id" type="hidden" value="{{ $orderId }}" />

    <div class="row">
        <div class="col-md-6">
            <x-core::form.text-input
                :label="trans('plugins/ecommerce::shipping.form_name')"
                :required="true"
                name="name"
                :value="$address->name"
                :placeholder="trans('plugins/ecommerce::shipping.form_name')"
            />
        </div>

        <div class="col-md-6">
            <x-core::form.text-input
                :label="trans('plugins/ecommerce::shipping.phone')"
                name="phone"
                :value="$address->phone"
                :placeholder="trans('plugins/ecommerce::shipping.phone')"
            />
        </div>
    </div>

    <x-core::form.text-input
        :label="trans('plugins/ecommerce::shipping.email')"
        type="email"
        name="email"
        :value="$address->email"
        :placeholder="trans('plugins/ecommerce::shipping.email')"
    />

    @if (EcommerceHelper::isUsingInMultipleCountries())
        <x-core::form.select
            :label="trans('plugins/ecommerce::shipping.country')"
            name="country"
            data-type="country"
            :options="EcommerceHelper::getAvailableCountries()"
            :value="$address->country"
            :searchable="true"
        />
    @else
        <input
            name="country"
            type="hidden"
            value="{{ EcommerceHelper::getFirstCountryId() }}"
        >
    @endif

    @if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation())
        <x-core::form.select
            :label="trans('plugins/ecommerce::shipping.state')"
            name="state"
            data-type="state"
            :data-url="route('ajax.states-by-country')"
            :searchable="true"
        >
            <option value="">{{ __('Select state...') }}</option>
            @if ($address->state || !EcommerceHelper::isUsingInMultipleCountries())
                @foreach (EcommerceHelper::getAvailableStatesByCountry($address->country) as $stateId => $stateName)
                    <option
                        value="{{ $stateId }}"
                        @if ($address->state == $stateId) selected @endif
                    >{{ $stateName }}</option>
                @endforeach
            @endif
        </x-core::form.select>
    @else
        <x-core::form.text-input
            :label="trans('plugins/ecommerce::shipping.state')"
            name="state"
            :value="$address->state"
            placeholder="{{ trans('plugins/ecommerce::shipping.state') }}"
        />
    @endif

    @if (! EcommerceHelper::useCityFieldAsTextField())
        <x-core::form.select
            :label="trans('plugins/ecommerce::shipping.city')"
            name="city"
            data-type="city"
            data-using-select2="false"
            :data-url="route('ajax.cities-by-state')"
        >
            <option value="">{{ __('Select city...') }}</option>
            @if ($address->city)
                @foreach (EcommerceHelper::getAvailableCitiesByState($address->state) as $cityId => $cityName)
                    <option
                        value="{{ $cityId }}"
                        @if ($address->city == $cityId) selected @endif
                    >{{ $cityName }}</option>
                @endforeach
            @endif
        </x-core::form.select>
    @else
        <x-core::form.text-input
            :label="trans('plugins/ecommerce::shipping.city')"
            name="city"
            :value="$address->city"
            placeholder="{{ trans('plugins/ecommerce::shipping.city') }}"
        />
    @endif

    <x-core::form.text-input
        :label="trans('plugins/ecommerce::shipping.address')"
        :required="true"
        name="address"
        :value="$address->address"
        :placeholder="trans('plugins/ecommerce::shipping.address')"
    />

    @if (EcommerceHelper::isZipCodeEnabled())
        <x-core::form.text-input
            :label="trans('plugins/ecommerce::shipping.zip_code')"
            name="zip_code"
            :value="$address->zip_code"
            :placeholder="trans('plugins/ecommerce::shipping.zip_code')"
        />
    @endif
</x-core::form>
