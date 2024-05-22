<?php

namespace Botble\Location;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseQueryBuilder;
use Botble\Base\Supports\Helper;
use Botble\Base\Supports\Zipper;
use Botble\Language\Facades\Language;
use Botble\Location\Events\DownloadedCities;
use Botble\Location\Events\DownloadedCountry;
use Botble\Location\Events\DownloadedStates;
use Botble\Location\Models\City;
use Botble\Location\Models\Country;
use Botble\Location\Models\State;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class Location
{
    public function getStates(): array
    {
        return State::query()
            ->wherePublished()
            ->orderBy('order')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }

    public function getCitiesByState(int|string|null $stateId): array
    {
        if (! $stateId) {
            return [];
        }

        return City::query()
            ->wherePublished()
            ->where('state_id', $stateId)
            ->orderBy('order')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }

    public function getCityById(int|string|null $cityId): City|Model|null
    {
        if (! $cityId) {
            return null;
        }

        return City::query()->where([
            'id' => $cityId,
            'status' => BaseStatusEnum::PUBLISHED,
        ])->first();
    }

    public function getCityNameById(int|string|null $cityId): ?string
    {
        if (! $cityId) {
            return null;
        }

        $city = $this->getCityById($cityId);

        return $city?->name;
    }

    public function getStateNameById(int|string|null $stateId): ?string
    {
        if (! $stateId) {
            return null;
        }

        return State::query()
            ->wherePublished()
            ->where('id', $stateId)
            ->value('name');
    }

    public function isSupported(string|object $model): bool
    {
        if (! $model) {
            return false;
        }

        if (is_object($model)) {
            $model = get_class($model);
        }

        return in_array($model, $this->supportedModels());
    }

    public function supportedModels(): array
    {
        return array_keys($this->getSupported());
    }

    public function getSupported(string|object $model = null): array
    {
        if (! $model) {
            return config('plugins.location.general.supported', []);
        }

        if (is_object($model)) {
            $model = get_class($model);
        }

        return Arr::get(config('plugins.location.general.supported', []), $model, []);
    }

    public function registerModule(string $model, array $keys = []): bool
    {
        $keys = array_filter(
            array_merge([
                'country' => 'country_id',
                'state' => 'state_id',
                'city' => 'city_id',
            ], $keys)
        );

        config([
            'plugins.location.general.supported' => array_merge($this->getSupported(), [$model => $keys]),
        ]);

        return true;
    }

    public function getRemoteAvailableLocations(): array
    {
        try {
            $url = 'https://api.github.com/repos/botble/locations/git/trees/master';

            $data = Cache::remember($url, 60 * 60, function () use ($url) {
                $response = Http::withoutVerifying()
                    ->asJson()
                    ->acceptJson()
                    ->get($url);

                if ($response->failed()) {
                    return ['us', 'ca', 'vn'];
                }

                return $response->json('tree');
            });

            $availableLocations = [];

            foreach ($data as $tree) {
                if (in_array($tree['path'], ['.gitignore', 'README.md'])) {
                    continue;
                }

                $availableLocations[] = $tree['path'];
            }
        } catch (Throwable) {
            $availableLocations = ['us', 'ca', 'vn'];
        }

        return $availableLocations;
    }

    public function downloadRemoteLocation(string $countryCode, bool $continue = false): array
    {
        $repository = 'https://github.com/botble/locations';

        $destination = storage_path('app/location-files.zip');

        $availableLocations = $this->getRemoteAvailableLocations();

        if (! in_array($countryCode, $availableLocations)) {
            return [
                'error' => true,
                'message' => sprintf('This country locations data is not available on %s', $repository),
            ];
        }

        if (! File::exists($destination)) {
            try {
                $response = Http::withoutVerifying()
                    ->sink(Utils::tryFopen($destination, 'w'))
                    ->get($repository . '/archive/refs/heads/master.zip');

                if (! $response->ok()) {
                    return [
                        'error' => true,
                        'message' => $response->reason(),
                    ];
                }
            } catch (Throwable $exception) {
                return [
                    'error' => true,
                    'message' => $exception->getMessage(),
                ];
            }

            $zip = new Zipper();

            $zip->extract($destination, storage_path('app'));

            File::delete($destination);
        }

        $dataPath = storage_path('app/locations-master/' . $countryCode);

        if (! File::isDirectory($dataPath)) {
            abort(404);
        }

        $country = file_get_contents($dataPath . '/country.json');
        $country = json_decode($country, true);

        $country = Country::query()->create($country);

        if ($country) {
            event(new DownloadedCountry());
        }

        $states = file_get_contents($dataPath . '/states.json');
        $states = json_decode($states, true);

        $statesForInserting = [];

        $now = Carbon::now();

        foreach ($states as $state) {
            $state['country_id'] = $country->id;

            $statesForInserting[] = array_merge($state, [
                'slug' => Str::slug($state['name']),
                'country_id' => $country->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        if (! empty($statesForInserting)) {
            collect($statesForInserting)
                ->chunk(1000)
                ->each(fn ($items) => State::query()->insertOrIgnore($items->toArray()));

            event(new DownloadedStates());
        }

        $cities = file_get_contents($dataPath . '/cities.json');
        $cities = json_decode($cities, true);

        $citiesForInserting = [];

        foreach ($cities as $item) {
            $stateId = State::query()->where('name', $item['name'])->value('id');

            if (! $stateId) {
                continue;
            }

            foreach ($item['cities'] as $cityName) {
                $citiesForInserting[] = [
                    'name' => $cityName,
                    'slug' => Str::slug($cityName),
                    'state_id' => $stateId,
                    'country_id' => $country->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (! empty($citiesForInserting)) {
            collect($citiesForInserting)
                ->chunk(1000)
                ->each(fn ($items) => City::query()->insertOrIgnore($items->toArray()));

            event(new DownloadedCities());
        }

        if (! $continue) {
            File::deleteDirectory(storage_path('app/locations-master'));
        }

        return [
            'error' => false,
            'message' => trans('plugins/location::bulk-import.imported_successfully'),
        ];
    }

    public function filter($model, int|string $cityId = null, string $location = null, int|string $stateId = null)
    {
        if ($model instanceof BaseQueryBuilder) {
            $className = get_class($model->getModel());
        } else {
            $className = get_class($model);
        }

        if ($this->isSupported($className)) {
            if ($cityId) {
                $model = $model->where('city_id', $cityId);
            } elseif ($stateId) {
                $model = $model->where('state_id', $stateId);
            } elseif ($location) {
                $locationData = explode(',', $location);

                $cityRelation = 'city';
                $stateRelation = 'state';

                if (
                    is_plugin_active('language') &&
                    is_plugin_active('language-advanced') &&
                    Language::getCurrentLocale() != Language::getDefaultLocale()
                ) {
                    $cityRelation = 'city.translations';
                    $stateRelation = 'state.translations';
                }

                if (count($locationData) > 1) {
                    $model = $model
                        ->whereHas($cityRelation, function ($query) use ($locationData) {
                            $query->where('name', 'LIKE', '%' . trim($locationData[0]) . '%');
                        })
                        ->whereHas($stateRelation, function ($query) use ($locationData) {
                            $query->where('name', 'LIKE', '%' . trim($locationData[1]) . '%');
                        });
                } else {
                    $model = $model
                        ->where(function (Builder $query) use ($cityRelation, $stateRelation, $location) {
                            $query
                                ->whereHas($cityRelation, function ($query) use ($location) {
                                    $query->where('name', 'LIKE', '%' . $location . '%');
                                })
                                ->orWhereHas($stateRelation, function ($query) use ($location) {
                                    $query->where('name', 'LIKE', '%' . $location . '%');
                                });
                        });
                }
            }
        }

        return $model;
    }

    public function getAvailableCountries(): array
    {
        $remoteLocations = $this->getRemoteAvailableLocations();

        $availableLocations = Country::query()->pluck('code')->all();

        $listCountries = Helper::countries();

        $locations = [];

        foreach ($remoteLocations as $location) {
            $location = strtoupper($location);

            if (in_array($location, $availableLocations)) {
                continue;
            }

            foreach ($listCountries as $key => $country) {
                if ($location === strtoupper($key)) {
                    $locations[$location] = $country;
                }
            }
        }

        return array_unique($locations);
    }
}
