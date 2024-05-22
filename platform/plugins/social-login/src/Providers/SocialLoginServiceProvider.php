<?php

namespace Botble\SocialLogin\Providers;

use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Setting\PanelSections\SettingOthersPanelSection;
use Botble\SocialLogin\Facades\SocialService;
use Illuminate\Foundation\AliasLoader;

class SocialLoginServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/social-login')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->publishAssets();

        AliasLoader::getInstance()->alias('SocialService', SocialService::class);

        PanelSectionManager::default()->beforeRendering(function () {
            PanelSectionManager::registerItem(
                SettingOthersPanelSection::class,
                fn () => PanelSectionItem::make('social-login')
                    ->setTitle(trans('plugins/social-login::social-login.menu'))
                    ->withDescription(trans('plugins/social-login::social-login.description'))
                    ->withIcon('ti ti-social')
                    ->withPriority(100)
                    ->withRoute('social-login.settings')
            );
        });

        $this->app->register(HookServiceProvider::class);
    }

    public function register(): void
    {
        $this->app->bind(SocialService::class);
    }
}
