<?php

namespace Botble\SimpleSlider\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Language\Facades\Language;
use Botble\Setting\PanelSections\SettingOthersPanelSection;
use Botble\SimpleSlider\Models\SimpleSlider;
use Botble\SimpleSlider\Models\SimpleSliderItem;
use Botble\SimpleSlider\Repositories\Eloquent\SimpleSliderItemRepository;
use Botble\SimpleSlider\Repositories\Eloquent\SimpleSliderRepository;
use Botble\SimpleSlider\Repositories\Interfaces\SimpleSliderInterface;
use Botble\SimpleSlider\Repositories\Interfaces\SimpleSliderItemInterface;

class SimpleSliderServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(SimpleSliderInterface::class, function () {
            return new SimpleSliderRepository(new SimpleSlider());
        });

        $this->app->bind(SimpleSliderItemInterface::class, function () {
            return new SimpleSliderItemRepository(new SimpleSliderItem());
        });
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/simple-slider')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadMigrations()
            ->publishAssets();

        DashboardMenu::default()->beforeRetrieving(function () {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-plugins-simple-slider',
                    'priority' => 390,
                    'name' => 'plugins/simple-slider::simple-slider.menu',
                    'icon' => 'ti ti-slideshow',
                    'route' => 'simple-slider.index',
                ]);
        });

        PanelSectionManager::default()->beforeRendering(function () {
            PanelSectionManager::registerItem(
                SettingOthersPanelSection::class,
                fn () => PanelSectionItem::make('simple_sliders')
                    ->setTitle(trans('plugins/simple-slider::simple-slider.settings.title'))
                    ->withIcon('ti ti-slideshow')
                    ->withPriority(430)
                    ->withDescription(trans('plugins/simple-slider::simple-slider.settings.description'))
                    ->withRoute('simple-slider.settings')
            );
        });

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            Language::registerModule(SimpleSlider::class);
        }

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });
    }
}
