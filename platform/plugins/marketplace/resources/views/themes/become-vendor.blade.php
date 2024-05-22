@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')
    {!! Form::open(['route' => 'marketplace.vendor.become-vendor', 'method' => 'POST']) !!}
    <div class="form__header">
        <h3>{{ SeoHelper::getTitle() }}</h3>
    </div>

    <div class="form__content">
        <input
            name="is_vendor"
            type="hidden"
            value="1"
        >
        @include('plugins/marketplace::themes.includes.become-vendor-form', [
            'isRegister' => true,
        ])

        <div class="form-group">
            <button class="btn btn-primary">{{ __('Register') }}</button>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
