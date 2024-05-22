@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core::card>
        <div class="page page-center" style="min-height: calc(100vh - 25rem)">
            <div class="container container-tight py-4">
                <div class="empty">
                    <div class="empty-img">
                        <x-core::icon name="ti ti-basket-discount" />
                    </div>
                    <p class="empty-title">{{ trans('plugins/ecommerce::discount.intro.title') }}</p>
                    <p class="empty-subtitle text-secondary">
                        {{ trans('plugins/ecommerce::discount.intro.description') }}
                    </p>
                    <div class="empty-action">
                        <x-core::button
                            color="primary"
                            tag="a"
                            :href="route('discounts.create')"
                        >
                            {{ trans('plugins/ecommerce::discount.intro.button_text') }}
                        </x-core::button>
                    </div>
                </div>
            </div>
        </div>
    </x-core::card>
@endsection
