<?php

namespace Botble\Location\Listeners;

use Botble\Language\Facades\Language;
use Botble\Location\Events\ImportedStateEvent;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateStateTranslationListener
{
    public function handle(ImportedStateEvent $event): void
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

            DB::table('states_translations')->insertOrIgnore([
                'states_id' => $event->state->getKey(),
                'lang_code' => $language->lang_code,
                'name' => $name = Arr::get($row, "name_$language->lang_code") ?: Arr::get($row, 'name'),
                'slug' => Str::slug($name),
                'abbreviation' => Arr::get($row, "abbreviation_$language->lang_code", Arr::get($row, 'abbreviation')),
            ]);
        }
    }
}
