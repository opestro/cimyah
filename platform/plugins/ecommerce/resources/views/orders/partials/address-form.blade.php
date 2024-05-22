<div class="customer-address-payment-form">
    @if (EcommerceHelper::isEnabledGuestCheckout() && !auth('customer')->check())
        <div class="mb-3 form-group">
            <p>{{ __('Already have an account?') }} <a href="{{ route('customer.login') }}">{{ __('Login') }}</a></p>
        </div>
    @endif

    {!! apply_filters('ecommerce_checkout_address_form_before') !!}

    @auth('customer')
        <div class="mb-3 form-group">
            @if ($isAvailableAddress)
                <label
                    class="mb-2 form-label"
                    for="address_id"
                >{{ __('Select available addresses') }}:</label>
            @endif
            @php
                $oldSessionAddressId = old('address.address_id', $sessionAddressId);
            @endphp
            <div class="list-customer-address @if (!$isAvailableAddress) d-none @endif">
                <div class="select--arrow">
                    <select
                        class="form-control"
                        id="address_id"
                        name="address[address_id]"
                        @required($isAvailableAddress)
                    >
                        <option
                            value="new"
                            @selected($oldSessionAddressId == 'new')
                        >{{ __('Add new address...') }}</option>
                        @if ($isAvailableAddress)
                            @foreach ($addresses as $address)
                                <option
                                    value="{{ $address->id }}"
                                    @selected($oldSessionAddressId == $address->id)
                                >{{ $address->full_address }}</option>
                            @endforeach
                        @endif
                    </select>
                    <x-core::icon name="ti ti-chevron-down" />
                </div>
                <br>
                <div class="address-item-selected @if (!$sessionAddressId) d-none @endif">
                    @if ($isAvailableAddress && $oldSessionAddressId != 'new')
                        @if ($oldSessionAddressId && $addresses->contains('id', $oldSessionAddressId))
                            @include('plugins/ecommerce::orders.partials.address-item', [
                                'address' => $addresses->firstWhere('id', $oldSessionAddressId),
                            ])
                        @elseif ($defaultAddress = get_default_customer_address())
                            @include('plugins/ecommerce::orders.partials.address-item', [
                                'address' => $defaultAddress,
                            ])
                        @else
                            @include('plugins/ecommerce::orders.partials.address-item', [
                                'address' => Arr::first($addresses),
                            ])
                        @endif
                    @endif
                </div>
                <div class="list-available-address d-none">
                    @if ($isAvailableAddress)
                        @foreach ($addresses as $address)
                            <div
                                class="address-item-wrapper"
                                data-id="{{ $address->id }}"
                            >
                                @include(
                                    'plugins/ecommerce::orders.partials.address-item',
                                    compact('address'))
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endauth

    <div class="address-form-wrapper @if (auth('customer')->check() && $oldSessionAddressId !== 'new' && $isAvailableAddress) d-none @endif">
        <div class="form-group mb-3 @error('address.name') has-error @enderror">
            <div class="form-input-wrapper">
                <input
                    class="form-control"
                    id="address_name"
                    name="address[name]"
                    autocomplete="name"
                    type="text"
                    value="{{ old('address.name', Arr::get($sessionCheckoutData, 'name')) ?: (auth('customer')->check() ? auth('customer')->user()->name : null) }}"
                    required
                >
                <label for="address_name">{{ __('Full Name') }}</label>
            </div>
            {!! Form::error('address.name', $errors) !!}
        </div>

        <div class="row">
            @if (!in_array('email', EcommerceHelper::getHiddenFieldsAtCheckout()))
                <div @class([
                    'col-12',
                    'col-lg-8' => !in_array(
                        'phone',
                        EcommerceHelper::getHiddenFieldsAtCheckout()),
                ])>
                    <div class="form-group mb-3 @error('address.email') has-error @enderror">
                        <div class="form-input-wrapper">
                            <input
                                class="form-control"
                                id="address_email"
                                name="address[email]"
                                autocomplete="email"
                                type="email"
                                value="{{ old('address.email', Arr::get($sessionCheckoutData, 'email')) ?: (auth('customer')->check() ? auth('customer')->user()->email : null) }}"
                                required
                            >
                            <label for="address_email">{{ __('Email') }}</label>
                        </div>
                        {!! Form::error('address.email', $errors) !!}
                    </div>
                </div>
            @endif
            @if (!in_array('phone', EcommerceHelper::getHiddenFieldsAtCheckout()))
                <div @class([
                    'col-12',
                    'col-lg-4' => !in_array(
                        'email',
                        EcommerceHelper::getHiddenFieldsAtCheckout()),
                ])>
                    <div class="form-group mb-3 @error('address.phone') has-error @enderror">
                        <div class="form-input-wrapper">
                            <input
                                class="form-control"
                                id="address_phone"
                                name="address[phone]"
                                autocomplete="phone"
                                type="tel"
                                value="{{ old('address.phone', Arr::get($sessionCheckoutData, 'phone')) ?: (auth('customer')->check() ? auth('customer')->user()->phone : null) }}"
                            >
                            <label for="address_phone">{{ __('Phone') }}</label>
                        </div>
                        {!! Form::error('address.phone', $errors) !!}
                    </div>
                </div>
            @endif
        </div>

        {!! apply_filters('ecommerce_checkout_address_form_inside', null) !!}

        @if (EcommerceHelper::isUsingInMultipleCountries() && !in_array('country', EcommerceHelper::getHiddenFieldsAtCheckout()))
            <div class="form-group mb-3 @error('address.country') has-error @enderror">
                <div class="select--arrow form-input-wrapper">
                    <select
                        class="form-control"
                        id="address_country"
                        name="address[country]"
                        autocomplete="country"
                        data-form-parent=".customer-address-payment-form"
                        data-type="country"
                        required
                    >
                        @foreach (EcommerceHelper::getAvailableCountries() as $countryCode => $countryName)
                            <option
                                value="{{ $countryCode }}"
                                @if (old('address.country', Arr::get($sessionCheckoutData, 'country')) == $countryCode) selected @endif
                            >{{ $countryName }}</option>
                        @endforeach
                    </select>
                    <x-core::icon name="ti ti-chevron-down" />
                    <label for="address_country">{{ __('Country') }}</label>
                </div>
                {!! Form::error('address.country', $errors) !!}
            </div>
        @else
            <input
                id="address_country"
                name="address[country]"
                type="hidden"
                value="{{ EcommerceHelper::getFirstCountryId() }}"
            >
        @endif

        <div class="row">
            @if (!in_array('state', EcommerceHelper::getHiddenFieldsAtCheckout()))
                <div class="col-sm-6 col-12">
                    <div class="form-group mb-3 @error('address.state') has-error @enderror">
                        @if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation())
                            <div class="select--arrow form-input-wrapper">
                                <select
                                    class="form-control"
                                    id="address_state"
                                    name="address[state]"
                                    autocomplete="state"
                                    data-form-parent=".customer-address-payment-form"
                                    data-type="state"
                                    data-url="{{ route('ajax.states-by-country') }}"
                                    required
                                >
                                    <option value="">{{ __('Select state...') }}</option>
                                    @if (old('address.country', Arr::get($sessionCheckoutData, 'country')) || !EcommerceHelper::isUsingInMultipleCountries())
                                        @foreach (EcommerceHelper::getAvailableStatesByCountry(old('address.country', Arr::get($sessionCheckoutData, 'country')) ?: EcommerceHelper::getFirstCountryId()) as $stateId => $stateName)
                                            <option
                                                value="{{ $stateId }}"
                                                @if (old('address.state', Arr::get($sessionCheckoutData, 'state')) == $stateId) selected @endif
                                            >{{ $stateName }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <x-core::icon name="ti ti-chevron-down" />
                                <label for="address_state">{{ __('State') }}</label>
                            </div>
                        @else
                            <div class="form-input-wrapper">
                                <input
                                    class="form-control"
                                    id="address_state"
                                    name="address[state]"
                                    autocomplete="state"
                                    type="text"
                                    value="{{ old('address.state', Arr::get($sessionCheckoutData, 'state')) }}"
                                    required
                                >
                                <label for="address_state">{{ __('State') }}</label>
                            </div>
                        @endif
                        {!! Form::error('address.state', $errors) !!}
                    </div>
                </div>
            @endif

            @if (!in_array('city', EcommerceHelper::getHiddenFieldsAtCheckout()))
                <div @class(['col-sm-6 col-12' => ! in_array('state', EcommerceHelper::getHiddenFieldsAtCheckout()), 'col-12' => in_array('state', EcommerceHelper::getHiddenFieldsAtCheckout())])>
                    <div class="form-group mb-3 @error('address.city') has-error @enderror">
                        @if (EcommerceHelper::useCityFieldAsTextField())
                            <div class="form-input-wrapper">
                                <input
                                    class="form-control"
                                    id="address_city"
                                    name="address[city]"
                                    autocomplete="city"
                                    type="text"
                                    value="{{ old('address.city', Arr::get($sessionCheckoutData, 'city')) }}"
                                    required
                                >
                                <label for="address_city">{{ __('City') }}</label>
                            </div>
                        @else
                            <div class="select--arrow form-input-wrapper">
                                <select
                                    class="form-control"
                                    id="address_city"
                                    name="address[city]"
                                    autocomplete="city"
                                    data-type="city"
                                    data-using-select2="false"
                                    data-url="{{ route('ajax.cities-by-state') }}"
                                    required
                                >
                                    <option value="">{{ __('Select city...') }}</option>
                                    @if (old('address.state', Arr::get($sessionCheckoutData, 'state')) || in_array('state', EcommerceHelper::getHiddenFieldsAtCheckout()))
                                        @foreach (EcommerceHelper::getAvailableCitiesByState(old('address.state', Arr::get($sessionCheckoutData, 'state')), old('address.country', Arr::get($sessionCheckoutData, 'country', EcommerceHelper::getFirstCountryId()))) as $cityId => $cityName)
                                            <option
                                                value="{{ $cityId }}"
                                                @if (old('address.city', Arr::get($sessionCheckoutData, 'city')) == $cityId) selected @endif
                                            >{{ $cityName }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <x-core::icon name="ti ti-chevron-down" />
                                <label for="address_city">{{ __('City') }}</label>
                            </div>
                        @endif
                        {!! Form::error('address.city', $errors) !!}
                    </div>
                </div>
            @endif
        </div>

        @if (!in_array('address', EcommerceHelper::getHiddenFieldsAtCheckout()))
            <div class="form-group mb-3 @error('address.address') has-error @enderror">
                <div class="form-input-wrapper">
                    <input
                        class="form-control"
                        id="address_address"
                        name="address[address]"
                        autocomplete="address"
                        type="text"
                        value="{{ old('address.address', Arr::get($sessionCheckoutData, 'address')) }}"
                        required
                    >
                    <label for="address_address">{{ __('Address') }}</label>
                </div>
                {!! Form::error('address.address', $errors) !!}
            </div>
        @endif

        @if (EcommerceHelper::isZipCodeEnabled())
            <div class="form-group mb-3 @error('address.zip_code') has-error @enderror">
                <div class="form-input-wrapper">
                    <input
                        class="form-control"
                        id="address_zip_code"
                        name="address[zip_code]"
                        autocomplete="postal-code"
                        type="text"
                        value="{{ old('address.zip_code', Arr::get($sessionCheckoutData, 'zip_code')) }}"
                        required
                    >
                    <label for="address_zip_code">{{ __('Zip code') }}</label>
                </div>
                {!! Form::error('address.zip_code', $errors) !!}
            </div>
        @endif
    </div>

    @if (!auth('customer')->check())
        <div id="register-an-account-wrapper">
            <div class="mb-3 form-group">
                <input
                    id="create_account"
                    name="create_account"
                    type="checkbox"
                    value="1"
                    @if (old('create_account') == 1) checked @endif
                >
                <label
                    class="form-label"
                    for="create_account"
                >{{ __('Register an account with above information?') }}</label>
            </div>

            <div class="password-group @if (!$errors->has('password') && !$errors->has('password_confirmation')) d-none @endif">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="form-group  @error('password') has-error @enderror">
                            <div class="form-input-wrapper">
                                <input
                                    class="form-control"
                                    id="password"
                                    name="password"
                                    type="password"
                                    autocomplete="password"
                                >
                                <label for="password">{{ __('Password') }}</label>
                            </div>
                            {!! Form::error('password', $errors) !!}
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group @error('password_confirmation') has-error @enderror">
                            <div class="form-input-wrapper">
                                <input
                                    class="form-control"
                                    id="password-confirm"
                                    name="password_confirmation"
                                    type="password"
                                    autocomplete="password-confirmation"
                                >
                                <label for="password-confirm">{{ __('Password confirmation') }}</label>
                            </div>
                            {!! Form::error('password_confirmation', $errors) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {!! apply_filters('ecommerce_checkout_address_form_after', null, $sessionCheckoutData) !!}
</div>
