<?php

namespace Botble\Location\Exporters;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\DataSynchronize\Exporter\ExportColumn;
use Botble\DataSynchronize\Exporter\ExportCounter;
use Botble\DataSynchronize\Exporter\Exporter;
use Botble\Language\Facades\Language;
use Botble\Location\Enums\ImportType;
use Botble\Location\Models\City;
use Botble\Location\Models\Country;
use Botble\Location\Models\State;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LocationExporter extends Exporter
{
    public function getLabel(): string
    {
        return trans('plugins/location::location.name');
    }

    public function columns(): array
    {
        $columns = [
            ExportColumn::make('name'),
            ExportColumn::make('slug'),
            ExportColumn::make('abbreviation'),
            ExportColumn::make('state'),
            ExportColumn::make('country'),
            ExportColumn::make('import_type')
                ->dropdown(ImportType::values()),
            ExportColumn::make('status')
                ->dropdown(BaseStatusEnum::values()),
            ExportColumn::make('order'),
            ExportColumn::make('nationality'),
        ];

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            $defaultLanguage = Language::getDefaultLanguage(['lang_code'])->lang_code;
            $supportedLocales = Language::getSupportedLocales();

            foreach ($supportedLocales as $properties) {
                if ($properties['lang_code'] != $defaultLanguage) {
                    $columns[] = ExportColumn::make('name_' . $properties['lang_code'])
                        ->label('Name (' . strtoupper($properties['lang_code']) . ')');
                }
            }
        }

        return $columns;
    }

    public function counters(): array
    {
        $countries = Country::query()->count();
        $states = State::query()->count();
        $cities = City::query()->count();

        return [
            ExportCounter::make()
                ->label(trans('plugins/location::location.export.total'))
                ->value(number_format($countries + $states + $cities)),
            ExportCounter::make()
                ->label(trans('plugins/location::location.export.total_countries'))
                ->value(number_format($countries)),
            ExportCounter::make()
                ->label(trans('plugins/location::location.export.total_states'))
                ->value(number_format($states)),
            ExportCounter::make()
                ->label(trans('plugins/location::location.export.total_cities'))
                ->value(number_format($cities)),
        ];
    }

    public function hasDataToExport(): bool
    {
        return Country::query()->exists() && State::query()->exists() && City::query()->exists();
    }

    public function collection(): Collection
    {
        $supportedLocales = [];
        $defaultLanguage = null;

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            $defaultLanguage = Language::getDefaultLanguage(['lang_code'])->lang_code;

            $supportedLocales = Language::getSupportedLocales();
        }

        $with = [
            'states',
            'states.cities',
        ];

        if (count($supportedLocales)) {
            $with = [
                'translations',
                'states',
                'states.cities',
                'states.translations',
                'states.cities.translations',
            ];
        }

        $countries = Country::query()->with($with)->get();

        $locations = collect();

        foreach ($countries as $country) {
            $countryData = [
                'name' => $country->name,
                'slug' => $country->slug ?: Str::slug($country->name),
                'abbreviation' => '',
                'state' => '',
                'country' => '',
                'import_type' => 'country',
                'status' => $country->status,
                'order' => $country->order,
                'nationality' => $country->nationality,
            ];

            foreach ($supportedLocales as $properties) {
                if ($properties['lang_code'] != $defaultLanguage) {
                    $countryData['name_' . $properties['lang_code']] = $country->translations->where('lang_code', $properties['lang_code'])->get('name');
                }
            }

            $locations->push($countryData);

            foreach ($country->states as $state) {
                $stateData = [
                    'name' => $state->name,
                    'slug' => $state->slug ?: Str::slug($state->name),
                    'abbreviation' => $state->abbreviation,
                    'state' => '',
                    'country' => $country->name,
                    'import_type' => 'state',
                    'status' => $state->status,
                    'order' => $state->order,
                    'nationality' => '',
                ];

                foreach ($supportedLocales as $properties) {
                    if ($properties['lang_code'] != $defaultLanguage) {
                        $stateData['name_' . $properties['lang_code']] = $state->translations->where('lang_code', $properties['lang_code'])->get('name');
                    }
                }

                $locations->push($stateData);

                foreach ($state->cities as $city) {
                    $cityData = [
                        'name' => $city->name,
                        'slug' => $city->slug ?: Str::slug($state->name),
                        'abbreviation' => '',
                        'state' => $state->name,
                        'country' => $city->country->name,
                        'import_type' => 'city',
                        'status' => $city->status,
                        'order' => $city->order,
                        'nationality' => '',
                    ];

                    foreach ($supportedLocales as $properties) {
                        if ($properties['lang_code'] != $defaultLanguage) {
                            $stateData['name_' . $properties['lang_code']] = $city->translations->where('lang_code', $properties['lang_code'])->get('name');
                        }
                    }

                    $locations->push($cityData);
                }
            }
        }

        return $locations;
    }
}
