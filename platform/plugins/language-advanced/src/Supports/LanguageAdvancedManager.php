<?php

namespace Botble\LanguageAdvanced\Supports;

use Botble\Base\Contracts\BaseModel;
use Botble\Base\Facades\MacroableModels;
use Botble\Language\Facades\Language;
use Botble\LanguageAdvanced\Models\TranslationResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LanguageAdvancedManager
{
    public static function save(?Model $object, Request $request): bool
    {
        if (! self::isSupported($object)) {
            return false;
        }

        $language = $request->input('language');

        if (! $language) {
            $language = Language::getCurrentAdminLocaleCode();
        }

        $condition = [
            'lang_code' => $language,
            $object->getTable() . '_id' => $object->getKey(),
        ];

        $table = $object->getTable() . '_translations';

        $data = [];
        foreach (DB::getSchemaBuilder()->getColumnListing($table) as $column) {
            if (! in_array($column, array_keys($condition))) {
                $data[$column] = $request->input($column);
            }
        }

        $data = array_merge($data, $condition);

        $data = apply_filters('language_advanced_before_save', $data, $object, $request);

        $translate = DB::table($table)->where($condition)->first();

        if ($translate) {
            DB::table($table)->where($condition)->update($data);
        } else {
            DB::table($table)->insert($data);
        }

        $defaultLocale = Language::getDefaultLocaleCode();

        if ($language != $defaultLocale) {
            $defaultTranslation = DB::table($table)
                ->where([
                    'lang_code' => $defaultLocale,
                    $object->getTable() . '_id' => $object->getKey(),
                ])
                ->first();

            if ($defaultTranslation) {
                foreach (DB::getSchemaBuilder()->getColumnListing($table) as $column) {
                    if (! in_array($column, array_keys($condition))) {
                        $object->{$column} = $defaultTranslation->{$column};
                    }
                }

                $object->save();
            }
        }

        return true;
    }

    public static function isSupported(BaseModel|Model|string|null $model): bool
    {
        if (! $model) {
            return false;
        }

        if (is_object($model)) {
            $model = get_class($model);
        }

        return in_array($model, self::supportedModels());
    }

    public static function supportedModels(): array
    {
        return array_keys(self::getSupported());
    }

    public static function getSupported(): array
    {
        return config('plugins.language-advanced.general.supported', []);
    }

    public static function getConfigs(): array
    {
        return config('plugins.language-advanced.general', []);
    }

    public static function getTranslatableColumns(BaseModel|Model|string|null $model): array
    {
        if (! $model) {
            return [];
        }

        if (is_object($model)) {
            $model = get_class($model);
        }

        return Arr::get(self::getSupported(), $model, []);
    }

    public static function registerModule(string $model, array $columns): bool
    {
        config([
            'plugins.language-advanced.general.supported' => array_merge(self::getSupported(), [$model => $columns]),
        ]);

        return true;
    }

    public static function delete(BaseModel|Model|string|null $object): bool
    {
        if (! self::isSupported($object)) {
            return false;
        }

        $table = $object->getTable() . '_translations';

        DB::table($table)->where([$object->getTable() . '_id' => $object->getKey()])->delete();

        return true;
    }

    public static function isTranslatableMetaBox(string $metaBoxKey): bool
    {
        return in_array($metaBoxKey, Arr::get(self::getConfigs(), 'translatable_meta_boxes', []));
    }

    public static function addTranslatableMetaBox(string $metaBoxKey): bool
    {
        $metaBoxes = array_merge(Arr::get(self::getConfigs(), 'translatable_meta_boxes', []), [$metaBoxKey]);

        config(['plugins.language-advanced.general.translatable_meta_boxes' => $metaBoxes]);

        return true;
    }

    public static function initModelRelations(): void
    {
        $locale = is_in_admin() ? Language::getCurrentAdminLocaleCode() : Language::getCurrentLocaleCode();

        $isDefaultLocale = $locale == Language::getDefaultLocaleCode();

        foreach (self::getSupported() as $item => $columns) {
            if (! class_exists($item)) {
                continue;
            }
            /**
             * @var Model $item
             */
            $item::resolveRelationUsing('translations', function ($model) use ($locale) {
                $instance = tap(
                    new TranslationResolver(),
                    function ($instance) {
                        if (! $instance->getConnectionName()) {
                            $instance->setConnection(DB::getDefaultConnection());
                        }
                    }
                );

                $modelTable = $model->getTable();

                $instance->setTable($modelTable . '_translations');

                $instance->fillable(array_merge([
                    'lang_code',
                    $modelTable . '_id',
                ], self::getTranslatableColumns($model::class)));

                return new HasMany(
                    $instance->newQuery(),
                    $model,
                    $modelTable . '_translations.' . $modelTable . '_id',
                    $model->getKeyName()
                );
            });

            foreach ($columns as $column) {
                MacroableModels::addMacro(
                    $item,
                    'get' . ucfirst(Str::camel($column)) . 'Attribute',
                    function () use ($column, $locale, $isDefaultLocale) {
                        if (
                            ! $this->lang_code && // @phpstan-ignore-line
                            ! $isDefaultLocale &&
                            $translation = $this->translations->where('lang_code', $locale)->value($column) // @phpstan-ignore-line
                        ) {
                            return $translation;
                        }

                        // @phpstan-ignore-next-line
                        return $this->getAttribute($column);
                    }
                );
            }
        }
    }
}
