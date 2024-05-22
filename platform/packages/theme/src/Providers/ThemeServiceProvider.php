<?php

namespace Botble\Theme\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\DashboardMenu as DashboardMenuSupport;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Setting\PanelSections\SettingCommonPanelSection;
use Botble\Theme\Commands\ThemeActivateCommand;
use Botble\Theme\Commands\ThemeAssetsPublishCommand;
use Botble\Theme\Commands\ThemeAssetsRemoveCommand;
use Botble\Theme\Commands\ThemeOptionCheckMissingCommand;
use Botble\Theme\Commands\ThemeRemoveCommand;
use Botble\Theme\Commands\ThemeRenameCommand;
use Botble\Theme\Contracts\Theme as ThemeContract;
use Botble\Theme\Events\RenderingAdminBar;
use Botble\Theme\Theme;

class ThemeServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->singleton(ThemeContract::class, Theme::class);
    }

    public function boot(): void
    {
        $this
            ->setNamespace('packages/theme')
            ->loadAndPublishConfigurations(['general', 'permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadHelpers()
            ->loadRoutes()
            ->publishAssets();

        DashboardMenu::default()->beforeRetrieving(function (DashboardMenuSupport $menu) {
            $config = $this->app['config'];

            $menu
                ->registerItem([
                    'id' => 'cms-core-appearance',
                    'priority' => 2000,
                    'parent_id' => null,
                    'name' => 'packages/theme::theme.appearance',
                    'icon' => 'ti ti-brush',
                    'url' => '#',
                    'permissions' => [],
                ])
                ->when(
                    $config->get('packages.theme.general.display_theme_manager_in_admin_panel', true),
                    function (DashboardMenuSupport $menu) {
                        $menu->registerItem([
                            'id' => 'cms-core-theme',
                            'priority' => 1,
                            'parent_id' => 'cms-core-appearance',
                            'name' => 'packages/theme::theme.name',
                            'icon' => null,
                            'url' => fn () => route('theme.index'),
                            'permissions' => ['theme.index'],
                        ]);
                    }
                )
                ->registerItem([
                    'id' => 'cms-core-theme-option',
                    'priority' => 4,
                    'parent_id' => 'cms-core-appearance',
                    'name' => 'packages/theme::theme.theme_options',
                    'icon' => null,
                    'url' => fn () => route('theme.options'),
                    'permissions' => ['theme.options'],
                ])
                ->registerItem([
                    'id' => 'cms-core-appearance-custom-css',
                    'priority' => 5,
                    'parent_id' => 'cms-core-appearance',
                    'name' => 'packages/theme::theme.custom_css',
                    'icon' => null,
                    'url' => fn () => route('theme.custom-css'),
                    'permissions' => ['theme.custom-css'],
                ])
                ->when(
                    $config->get('packages.theme.general.enable_custom_js'),
                    function (DashboardMenuSupport $menu) {
                        $menu->registerItem([
                            'id' => 'cms-core-appearance-custom-js',
                            'priority' => 6,
                            'parent_id' => 'cms-core-appearance',
                            'name' => 'packages/theme::theme.custom_js',
                            'icon' => null,
                            'url' => fn () => route('theme.custom-js'),
                            'permissions' => ['theme.custom-js'],
                        ]);
                    }
                )
                ->when(
                    $config->get('packages.theme.general.enable_custom_html'),
                    function (DashboardMenuSupport $menu) {
                        $menu->registerItem([
                            'id' => 'cms-core-appearance-custom-html',
                            'priority' => 6,
                            'parent_id' => 'cms-core-appearance',
                            'name' => 'packages/theme::theme.custom_html',
                            'icon' => null,
                            'url' => fn () => route('theme.custom-html'),
                            'permissions' => ['theme.custom-html'],
                        ]);
                    }
                )
                ->when(
                    $config->get('packages.theme.general.enable_robots_txt_editor'),
                    function (DashboardMenuSupport $menu) {
                        $menu->registerItem([
                            'id' => 'cms-core-appearance-robots-txt',
                            'priority' => 6,
                            'parent_id' => 'cms-core-appearance',
                            'name' => 'packages/theme::theme.robots_txt_editor',
                            'icon' => null,
                            'url' => fn () => route('theme.robots-txt'),
                            'permissions' => ['theme.robots-text'],
                        ]);
                    }
                );
        });

        PanelSectionManager::default()->beforeRendering(function () {
            PanelSectionManager::registerItem(
                SettingCommonPanelSection::class,
                fn () => PanelSectionItem::make('website_tracking')
                    ->setTitle(trans('packages/theme::theme.settings.website_tracking.title'))
                    ->withIcon('ti ti-world')
                    ->withDescription(trans('packages/theme::theme.settings.website_tracking.description'))
                    ->withPriority(140)
                    ->withRoute('settings.website-tracking'),
            );
        });

        $this->app['events']->listen(RenderingAdminBar::class, function () {
            admin_bar()
                ->registerLink(trans('packages/theme::theme.name'), route('theme.index'), 'appearance', 'theme.index')
                ->registerLink(
                    trans('packages/theme::theme.theme_options'),
                    route('theme.options'),
                    'appearance',
                    'theme.options'
                );
        });

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });

        $this->app->register(ThemeManagementServiceProvider::class);
        $this->app->register(EventServiceProvider::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ThemeActivateCommand::class,
                ThemeRemoveCommand::class,
                ThemeAssetsPublishCommand::class,
                ThemeOptionCheckMissingCommand::class,
                ThemeAssetsRemoveCommand::class,
                ThemeRenameCommand::class,
            ]);
        }
    }
}
