<?php

namespace Botble\Translation\Tables;

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\Html;
use Botble\Base\Supports\Language;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\BulkChanges\SelectBulkChange;
use Botble\Table\CollectionDataTable;
use Botble\Table\Columns\FormattedColumn;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class TranslationTable extends TableAbstract
{
    protected string $locale = 'en';

    public function setup(): void
    {
        parent::setup();

        $this->hasOperations = false;
        $this->setView('core/table::base-table');
        $this->pageLength = 100;

        Assets::addScripts(['bootstrap-editable'])
            ->addStyles(['bootstrap-editable']);

        $this->useDefaultSorting = false;

        $this
            ->onAjax(function () {
                $translations = [];

                $langLoader = Lang::getLoader();

                foreach ($this->getGroups() as $group) {
                    if (! str_contains($group, DIRECTORY_SEPARATOR)) {
                        $trans = $langLoader->load('en', $group);
                    } else {
                        $trans = $langLoader->load('en', Str::afterLast($group, DIRECTORY_SEPARATOR), Str::beforeLast($group, DIRECTORY_SEPARATOR));
                    }

                    if ($trans && is_array($trans)) {
                        foreach (Arr::dot($trans) as $key => $value) {
                            if (empty($value)) {
                                continue;
                            }

                            $translations[$group][$key] = $value;
                        }
                    }
                }

                $translationsCollection = collect();

                foreach ($translations as $group => $items) {
                    foreach (Arr::dot($items) as $key => $value) {
                        $translationsCollection->push([
                            'group' => $group,
                            'key' => $key,
                            'value' => $value,
                        ]);
                    }
                }

                if ($this->isFiltering()) {
                    $translationsCollection = $translationsCollection->filter(function ($item) {
                        $filterColumns = $this->request()->query('filter_columns');
                        $filterOperator = $this->request()->query('filter_operators');
                        $filterValues = $this->request()->query('filter_values');

                        if (empty($filterColumns) || empty($filterOperator) || empty($filterValues)) {
                            return true;
                        }

                        foreach ($filterColumns as $index => $filterColumn) {
                            $filterOperator = $filterOperator[$index];
                            $filterValue = $filterValues[$index];

                            if ($filterOperator === '=') {
                                if (empty($filterValue) || empty($filterColumn)) {
                                    return true;
                                }

                                if ($filterValue === $item['group']) {
                                    return true;
                                }

                                return false;
                            }
                        }

                        return false;
                    });
                }

                if ($this->request()->filled('group')) {
                    $translationsCollection = $translationsCollection->filter(function ($item) {
                        return $item['group'] === $this->request()->query('group');
                    });
                }

                return $this->toJson(
                    $this
                        ->table
                        ->of($translationsCollection)
                        ->filter(function (CollectionDataTable $query) {
                            if ($keyword = $this->request->input('search.value')) {
                                $query->collection = $query->collection->filter(function ($item) use ($keyword) {
                                    return str_contains($item['value'], $keyword);
                                });
                            }

                            return $query;
                        })
                );
            });
    }

    public function getFilters(): array
    {
        return [
            'group' => SelectBulkChange::make()
                ->name('group')
                ->title(trans('plugins/translation::translation.group'))
                ->choices($this->getGroups())
                ->validate(['required', 'string'])->toArray(),
        ];
    }

    public function columns(): array
    {
        return [
            FormattedColumn::make('group')
                ->title(trans('plugins/translation::translation.group'))
                ->alignStart()
                ->searchable(false)
                ->getValueUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();

                    $group = $item->group;
                    $groupDisplay = $group;

                    if (Str::startsWith($group, 'core/') || Str::startsWith($group, 'packages/')) {
                        $name = Str::headline(Str::slug(Str::afterLast($group, '/')));

                        $groupDisplay = $name . ' (core)';
                    } elseif (Str::startsWith($group, 'plugins/')) {
                        $plugin = Str::beforeLast(Str::after($group, 'plugins/'), '/');

                        $name = Str::afterLast($group, '/');

                        if ($plugin !== $name) {
                            $name = Str::headline(Str::slug($name));

                            $groupDisplay = $name . ' (' . Str::beforeLast(Str::after($group, 'plugins/'), '/') . ')';
                        } else {
                            $groupDisplay = Str::headline(Str::slug($name));
                        }
                    }

                    return Html::tag(
                        'code',
                        $groupDisplay,
                        [
                            'data-bs-toggle' => 'tooltip',
                            'data-bs-original-title' => $group,
                        ]
                    );
                }),
            FormattedColumn::make('key')
                ->title(Arr::get(Language::getAvailableLocales(), 'en.name', 'en'))
                ->alignStart()
                ->searchable(false)
                ->getValueUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();

                    $trans = trans(Str::of($item->group)->replaceLast(DIRECTORY_SEPARATOR, '::')->append(".$item->key")->toString(), [], 'en');

                    return $this->formatKeyAndValue(is_array($trans) ? $item->key : $trans);
                }),
            FormattedColumn::make('value')
                ->title(Arr::get(Language::getAvailableLocales(), "{$this->locale}.name", $this->locale))
                ->alignStart()
                ->getValueUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();

                    $trans = trans(Str::of($item->group)->replaceLast(DIRECTORY_SEPARATOR, '::')->append(".$item->key")->toString(), [], $this->locale);

                    $value = $this->formatKeyAndValue(is_array($trans) ? $item->value : $trans);

                    return Html::link('#edit', $value, [
                        'class' => sprintf('editable locale-%s', $this->locale),
                        'data-locale' => $this->locale,
                        'data-name' => sprintf('%s|%s', $this->locale, $item->key),
                        'data-type' => 'textarea',
                        'data-pk' => $item->key,
                        'data-title' => trans('plugins/translation::translation.edit_title'),
                        'data-url' => route('translations.group.edit', ['group' => $item->group]),
                    ]);
                }),
        ];
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    protected function formatKeyAndValue(?string $value): ?string
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
    }

    protected function getGroups(): array
    {
        $groups = [];

        if (File::isDirectory(lang_path('en'))) {
            foreach (File::allFiles(lang_path('en')) as $directory) {
                $group = File::name($directory);

                $groups[$group] = $group;
            }
        }

        foreach (Lang::getLoader()->namespaces() as $namespace => $langPath) {
            $defaultLanguage = $langPath . DIRECTORY_SEPARATOR . 'en';

            if (! File::isDirectory($defaultLanguage)) {
                continue;
            }

            foreach (File::allFiles($defaultLanguage) as $directory) {
                $group =  $namespace . DIRECTORY_SEPARATOR . File::name($directory);

                $groups[$group] = $group;
            }
        }

        ksort($groups);

        return $groups;
    }

    public function htmlDrawCallbackFunction(): ?string
    {
        return parent::htmlDrawCallbackFunction() . 'Botble.initEditable()';
    }
}
