<?php

namespace Botble\Ads\Providers;

use Botble\Ads\Facades\AdsManager;
use Botble\Ads\Forms\AdsForm;
use Botble\Ads\Models\Ads;
use Botble\Ads\Repositories\Eloquent\AdsRepository;
use Botble\Ads\Repositories\Interfaces\AdsInterface;
use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\Setting\PanelSections\SettingOthersPanelSection;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AdsServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(AdsInterface::class, function () {
            return new AdsRepository(new Ads());
        });

        AliasLoader::getInstance()->alias('AdsManager', AdsManager::class);
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/ads')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadHelpers()
            ->loadAndPublishViews();

        DashboardMenu::beforeRetrieving(function () {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-plugins-ads',
                    'priority' => 8,
                    'icon' => 'ti ti-ad-circle',
                    'name' => 'plugins/ads::ads.name',
                    'permissions' => ['ads.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ads-list',
                    'parent_id' => 'cms-plugins-ads',
                    'priority' => 1,
                    'name' => 'plugins/ads::ads.name',
                    'url' => fn () => route('ads.index'),
                    'permissions' => ['ads.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ads-setting',
                    'parent_id' => 'cms-plugins-ads',
                    'priority' => 2,
                    'name' => 'plugins/ads::ads.settings.title',
                    'url' => fn () => route('ads.settings'),
                    'permissions' => ['ads.index'],
                ]);
        });

        PanelSectionManager::default()->beforeRendering(function () {
            PanelSectionManager::registerItem(
                SettingOthersPanelSection::class,
                fn () => PanelSectionItem::make('ads')
                    ->setTitle(trans('plugins/ads::ads.settings.title'))
                    ->withIcon('ti ti-ad-circle')
                    ->withPriority(480)
                    ->withDescription(trans('plugins/ads::ads.settings.description'))
                    ->withRoute('ads.settings')
            );
        });

        $this->app['events']->listen(RouteMatched::class, function () {
            if (class_exists(Shortcode::class)) {
                Shortcode::register('ads', __('Ads'), __('Ads'), function ($shortcode) {
                    if (! $shortcode->key) {
                        return null;
                    }

                    return AdsManager::displayAds((string) $shortcode->key);
                });

                Shortcode::setAdminConfig('ads', function ($attributes) {
                    $ads = Ads::query()
                        ->wherePublished()
                        ->pluck('name', 'key')
                        ->all();

                    return ShortcodeForm::createFromArray($attributes)
                        ->withLazyLoading()
                        ->add(
                            'key',
                            SelectField::class,
                            SelectFieldOption::make()
                                ->label(trans('plugins/ads::ads.select_ad'))
                                ->choices($ads)
                                ->toArray()
                        );
                });
            }
        });

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            LanguageAdvancedManager::registerModule(Ads::class, [
                'name',
                'image',
                'url',
            ]);
        }

        if (defined('THEME_FRONT_HEADER')) {
            add_filter(THEME_FRONT_HEADER, function ($html) {
                $autoAds = setting('ads_google_adsense_auto_ads');

                if (! $autoAds) {
                    return $html;
                }

                return $html . $autoAds;
            }, 128);

            add_filter(THEME_FRONT_HEADER, function ($html) {
                $clientId = setting('ads_google_adsense_unit_client_id');

                if (! $clientId) {
                    return $html;
                }

                return $html . view('plugins/ads::partials.google-adsense.unit-ads-header', compact('clientId'))->render();
            }, 128);

            add_filter(THEME_FRONT_HEADER, function ($html) {
                $clientId = setting('ads_google_adsense_unit_client_id');

                if (! $clientId) {
                    return $html;
                }

                return $html . view('plugins/ads::partials.google-adsense.unit-ads-footer')->render();
            }, 128);
        }

        add_filter('ads_render', function (string $location, array $attributes = []) {
            return AdsManager::display($location, $attributes);
        }, 128, 2);

        AdsForm::beforeRendering(function () {
            add_action(BASE_ACTION_TOP_FORM_CONTENT_NOTIFICATION, function ($request, $data = null) {
                if (! $data instanceof Ads || ! in_array(Route::currentRouteName(), ['ads.create', 'ads.edit'])) {
                    return false;
                }

                echo view('plugins/ads::partials.notification')
                    ->render();

                return true;
            }, 45, 2);
        });
    }
}
