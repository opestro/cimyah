<x-core::card>
    <x-core::card.header>
        <x-core::card.title>
            @if (!empty($icon))
                <i class="{{ $icon }}"></i>
            @endif
            {{ $title ?? apply_filters(BASE_ACTION_FORM_ACTIONS_TITLE, trans('core/base::forms.publish')) }}
        </x-core::card.title>
    </x-core::card.header>
    <x-core::card.body>
        <x-core::button
            type="submit"
            name="submit"
            value="save"
            color="primary"
            icon="ti ti-coin"
        >
            {{ $saveTitle ?? __('Request') }}
        </x-core::button>
    </x-core::card.body>
</x-core::card>

<div
    data-bb-waypoint
    data-bb-target="#form-actions"
></div>

<header
    class="top-0 w-100 position-fixed end-0 z-1000"
    id="form-actions"
    style="display: none"
>
    <div class="navbar">
        <div class="{{ AdminAppearance::getContainerWidth() }}">
            <div class="row g-2 align-items-center w-100">
                @if(is_in_admin(true))
                    <div class="col">
                        <div class="page-pretitle">
                            {!! Breadcrumbs::render('main', PageTitle::getTitle(false)) !!}
                        </div>
                    </div>
                @endif
                <div class="col-auto ms-auto d-print-none">
                    <x-core::button
                        type="submit"
                        name="submit"
                        value="save"
                        color="primary"
                        icon="ti ti-coin"
                    >
                        {{ $saveTitle ?? __('Request') }}
                    </x-core::button>
                </div>
            </div>
        </div>
    </div>
</header>
