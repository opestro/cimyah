<?php

namespace Botble\SalePopup\Providers;

use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Ecommerce\PanelSections\SettingEcommercePanelSection;
use Botble\Theme\Facades\Theme;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SalePopupServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this->setNamespace('plugins/sale-popup')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->publishAssets()
            ->loadRoutes();

        PanelSectionManager::beforeRendering(function () {
            PanelSectionManager::default()
                ->registerItem(
                    SettingEcommercePanelSection::class,
                    fn () => PanelSectionItem::make('sale-popup')
                        ->setTitle(trans('plugins/ecommerce::setting.sale_popup.name'))
                        ->withIcon('ti ti-shopping-cart')
                        ->withDescription(trans('plugins/ecommerce::setting.sale_popup.description'))
                        ->withPriority(190)
                        ->withRoute('sale-popup.settings')
                );
        });

        $this->app['events']->listen(RouteMatched::class, function () {
            if (
                defined('THEME_FRONT_FOOTER') &&
                setting('sale_popup_enabled', 1) &&
                in_array(Route::currentRouteName(), json_decode(setting('sale_popup_display_pages', '["public.index"]'), true))
            ) {
                Theme::asset()
                    ->usePath(false)
                    ->add(
                        'sale-popup-css',
                        asset('vendor/core/plugins/sale-popup/css/sale-popup.css'),
                        [],
                        [],
                        '1.0.0'
                    );

                Theme::asset()
                    ->container('footer')
                    ->usePath(false)
                    ->add(
                        'sale-popup-js',
                        asset('vendor/core/plugins/sale-popup/js/sale-popup.js'),
                        ['jquery'],
                        [],
                        '1.0.0'
                    );

                add_filter(
                    THEME_FRONT_FOOTER,
                    fn (?string $html) => $html . view('plugins/sale-popup::front')->render(),
                    1457
                );
            }
        });

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });
    }
}
