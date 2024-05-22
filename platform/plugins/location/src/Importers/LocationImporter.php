<?php

namespace Botble\Location\Importers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\DataSynchronize\Importer\ImportColumn;
use Botble\DataSynchronize\Importer\Importer;
use Botble\Language\Facades\Language;
use Botble\Location\Enums\ImportType;
use Botble\Location\Services\ImportLocationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LocationImporter extends Importer
{
    public function columns(): array
    {
        $columns = [
            ImportColumn::make('name')
                ->rules(['required', 'string', 'max:120'], trans('plugins/location::location.import.rules.name')),
            ImportColumn::make('slug')
                ->rules(['nullable', 'string', 'max:120'], trans('plugins/location::location.import.rules.slug')),
            ImportColumn::make('import_type')
                ->rules(['required', Rule::in(ImportType::values())], trans('plugins/location::location.import.rules.import_type')),
            ImportColumn::make('order')
                ->rules(['nullable', 'integer', 'min:0', 'max:127'], trans('plugins/location::location.import.rules.order')),
            ImportColumn::make('abbreviation')
                ->rules(['nullable', 'string', 'max:10'], trans('plugins/location::location.import.rules.abbreviation')),
            ImportColumn::make('status')
                ->rules(['required', 'string', Rule::in(BaseStatusEnum::values())], trans('plugins/location::location.import.rules.status')),
            ImportColumn::make('country')
                ->rules(['required_if:import_type,state,city'], trans('plugins/location::location.import.rules.country')),
            ImportColumn::make('state')
                ->rules(['required_if:import_type,city'], trans('plugins/location::location.import.rules.state')),
            ImportColumn::make('nationality')
                ->rules(['nullable', 'string', 'max:120'], trans('plugins/location::location.import.rules.nationality')),
        ];

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            $defaultLanguage = Language::getDefaultLanguage(['lang_code'])->lang_code;
            $supportedLocales = Language::getSupportedLocales();

            foreach ($supportedLocales as $properties) {
                if ($properties['lang_code'] != $defaultLanguage && $properties['lang_code'] == 'vi') {
                    $columns[] = ImportColumn::make('name_vi')
                        ->rules(['nullable', 'string', 'max:120'], trans('plugins/location::location.import.rules.name'));
                }
            }
        }

        return $columns;
    }

    public function chunkSize(): int
    {
        return 200;
    }

    public function getLabel(): string
    {
        return trans('plugins/location::location.name');
    }

    public function getValidateUrl(): string
    {
        return route('location.bulk-import.validate');
    }

    public function getImportUrl(): string
    {
        return route('location.bulk-import.store');
    }

    public function getDownloadExampleUrl(): ?string
    {
        return route('location.bulk-import.download-example');
    }

    public function getExportUrl(): ?string
    {
        return Auth::user()->hasPermission('location.export.index')
            ? route('location.export.index')
            : null;
    }

    public function handle(array $data): int
    {
        /** @var ImportLocationService $service */
        $service = app(ImportLocationService::class);

        $service->handle($data);

        return $service->count();
    }

    public function examples(): array
    {
        $locations = [
            [
                'name' => 'United States of America',
                'slug' => '',
                'abbreviation' => '',
                'state' => '',
                'country' => '',
                'import_type' => 'country',
                'status' => BaseStatusEnum::PUBLISHED,
                'order' => 0,
                'nationality' => 'Americans',
            ],
            [
                'name' => 'Texas',
                'slug' => '',
                'abbreviation' => 'TX',
                'state' => '',
                'country' => 'United States of America',
                'import_type' => 'state',
                'status' => BaseStatusEnum::PUBLISHED,
                'order' => 0,
                'nationality' => '',
            ],
            [
                'name' => 'Washington',
                'slug' => '',
                'abbreviation' => 'WA',
                'state' => '',
                'country' => 'United States of America',
                'import_type' => 'state',
                'status' => BaseStatusEnum::PUBLISHED,
                'order' => 0,
                'nationality' => '',
            ],
            [
                'name' => 'Houston',
                'slug' => 'houston',
                'abbreviation' => '',
                'state' => 'Texas',
                'country' => 'United States of America',
                'import_type' => 'city',
                'status' => BaseStatusEnum::PUBLISHED,
                'order' => 0,
                'nationality' => '',
            ],
            [
                'name' => 'San Antonio',
                'slug' => 'san-antonio',
                'abbreviation' => '',
                'state' => 'Texas',
                'country' => 'United States of America',
                'import_type' => 'city',
                'status' => BaseStatusEnum::PUBLISHED,
                'order' => 0,
                'nationality' => '',
            ],
        ];

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            $defaultLanguage = Language::getDefaultLanguage(['lang_code'])->lang_code;

            $supportedLocales = Language::getSupportedLocales();
            foreach ($supportedLocales as $properties) {
                if ($properties['lang_code'] != $defaultLanguage && $properties['lang_code'] == 'vi') {
                    $locations[1]['name_vi'] = 'Bang Texas';
                    $locations[2]['name_vi'] = 'Bang Washington';
                    $locations[3]['name_vi'] = 'Thành phố Houston';
                    $locations[4]['name_vi'] = 'Thành phố San Antonio';
                }
            }
        }

        return $locations;
    }
}
