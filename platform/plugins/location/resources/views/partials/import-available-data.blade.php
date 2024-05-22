<x-core::card class="mb-3">
    <x-core::card.header>
        <x-core::card.title>{{ trans('plugins/location::bulk-import.import_available_data') }}</x-core::card.title>
    </x-core::card.header>

    <x-core::card.body>
        <x-core::form
            :url="route('location.bulk-import.import-location-data')"
            method="post"
            data-bb-toggle="import-available-data"
            :data-empty-selection-message="trans('plugins/location::bulk-import.choose_country')"
        >
            <x-core::alert type="warning">
                {!! BaseHelper::clean(
                    trans(
                        'plugins/location::bulk-import.import_available_data_help',
                         ['link' => Html::link(route('country.index'), trans('plugins/location::country.name'))]
                     )
                ) !!}
            </x-core::alert>
            <x-core::form.select
                name="country_code"
                :options="$countries"
                :searchable="true"
                :multiple="true"
                :data-placeholder="trans('plugins/location::bulk-import.choose_country')"
                :helper-text="trans('plugins/location::bulk-import.available_data_help', ['link' => Html::link('https://github.com/botble/locations', attributes: ['target' => '_blank'])])"
            />

            <x-core::button type="submit" color="primary">
                {{ trans('plugins/location::bulk-import.import') }}
            </x-core::button>
        </x-core::form>
    </x-core::card.body>
</x-core::card>
