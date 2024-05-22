<?php

namespace Botble\Translation\Providers;

use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Translation\Console\DownloadLocaleCommand;
use Botble\Translation\Console\RemoveLocaleCommand;
use Botble\Translation\Console\RemoveUnusedTranslationsCommand;
use Botble\Translation\Console\UpdateThemeTranslationCommand;
use Botble\Translation\PanelSections\LocalizationPanelSection;

class TranslationServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/translation')
            ->loadAndPublishConfigurations(['general', 'permissions'])
            ->loadMigrations()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets();

        PanelSectionManager::beforeRendering(function () {
            PanelSectionManager::register(LocalizationPanelSection::class);
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                UpdateThemeTranslationCommand::class,
                RemoveUnusedTranslationsCommand::class,
                DownloadLocaleCommand::class,
                RemoveLocaleCommand::class,
            ]);
        }
    }
}
