@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-plugins-ecommerce::intro
        :title="trans('plugins/ecommerce::customer.intro.title')"
        :subtitle="trans('plugins/ecommerce::customer.intro.description')"
        :action-url="route('customers.create')"
        :action-label="trans('plugins/ecommerce::customer.intro.button_text')"
    >
        <x-slot:icon>
            <img
                src="{{ asset('vendor/core/plugins/ecommerce/images/empty-customer.png') }}"
                alt="image"
            >
        </x-slot:icon>
    </x-plugins-ecommerce::intro>
@stop
