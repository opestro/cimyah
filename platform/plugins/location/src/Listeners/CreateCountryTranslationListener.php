<?php

namespace Botble\Location\Listeners;

use Botble\Language\Facades\Language;
use Botble\Location\Events\ImportedCountryEvent;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CreateCountryTranslationListener
{
    public function handle(ImportedCountryEvent $event): void
    {
        if (! defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            return;
        }

        $languages = Language::getActiveLanguage(['lang_code', 'lang_is_default']);

        /** @var \Botble\Language\Models\Language $language */
        foreach ($languages as $language) {
            if ($language->lang_is_default) {
                continue;
            }

            $row = $event->row;

            DB::table('countries_translations')->insertOrIgnore([
                'countries_id' => $event->country->getKey(),
                'lang_code' => $language->lang_code,
                'name' => Arr::get($row, "name_$language->lang_code", Arr::get($row, 'name')),
                'nationality' => Arr::get($row, "nationality_$language->lang_code", Arr::get($row, 'nationality')),
            ]);
        }
    }
}
