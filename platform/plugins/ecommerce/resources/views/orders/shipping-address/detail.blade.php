<dd>{{ $address->name }}</dd>
@if ($address->phone)
    <dd>
        <a href="tel:{{ $address->phone }}">
            <x-core::icon name="ti ti-phone" />
            <span dir="ltr">{{ $address->phone }}</span>
        </a>
    </dd>
@endif

@if ($address->email)
    <dd><a href="mailto:{{ $address->email }}">{{ $address->email }}</a></dd>
@endif
@if ($address->address)
    <dd>{!! BaseHelper::clean($address->address) !!}</dd>
@endif
@if ($address->city)
    <dd>{{ $address->city_name }}</dd>
@endif
@if ($address->state)
    <dd>{{ $address->state_name }}</dd>
@endif
@if ($address->country_name)
    <dd>{{ $address->country_name }}</dd>
@endif
@if (EcommerceHelper::isZipCodeEnabled() && $address->zip_code)
    <dd>{{ $address->zip_code }}</dd>
@endif
@if ($address->country || $address->state || $address->city || $address->address)
    <dd>
        <a href="https://maps.google.com/?q={{ $address->full_address }}" target="_blank">
            {{ trans('plugins/ecommerce::order.see_on_maps') }}
        </a>
    </dd>
@endif
