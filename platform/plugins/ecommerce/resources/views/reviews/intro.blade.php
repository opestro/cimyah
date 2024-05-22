@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-plugins-ecommerce::intro
        :title="trans('plugins/ecommerce::review.intro.title')"
        :subtitle="trans('plugins/ecommerce::review.intro.description')"
        :action-url="route('reviews.create')"
        :action-label="trans('plugins/ecommerce::review.create_review')"
    >
        <x-slot:icon>
            <img
                src="{{ asset('vendor/core/plugins/ecommerce/images/empty-customer.png') }}"
                alt="image"
            >
        </x-slot:icon>
    </x-plugins-ecommerce::intro>
@stop
